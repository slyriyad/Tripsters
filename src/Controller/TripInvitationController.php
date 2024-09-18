<?php

namespace App\Controller;

use App\Entity\TripInvitation;
use App\Entity\Trip;
use App\Entity\TripUser;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface; 
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TripInvitationController extends AbstractController
{
    #[Route('/trip/invitation', name: 'app_trip_invitation')]
    public function index(): Response
    {
        return $this->render('trip_invitation/index.html.twig', [
            'controller_name' => 'TripInvitationController',
        ]);
    }


    #[Route('/trip/{id}/invite', name: 'trip_invite', methods: ['POST'])]
    public function inviteUser(Request $request, Trip $trip, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        // Log: Début du processus d'invitation
        $logger->info('Début du processus d\'invitation pour le voyage : ' . $trip->getId());

        // Recherche de l'utilisateur à inviter par son e-mail
        $inviteeEmail = $request->request->get('email');
        $logger->info('Recherche de l\'utilisateur avec l\'email : ' . $inviteeEmail);

        $invitee = $entityManager->getRepository(User::class)->findOneBy(['email' => $inviteeEmail]);

        if (!$invitee) {
            $logger->error('Utilisateur non trouvé avec l\'email : ' . $inviteeEmail);
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('app_trip_show', ['id' => $trip->getId()]);
        }

        // Log: Utilisateur trouvé
        $logger->info('Utilisateur trouvé : ' . $invitee->getEmail());

        // Vérifier si l'utilisateur est déjà invité ou participant
        $existingTripUser = $entityManager->getRepository(TripInvitation::class)
            ->findOneBy(['trip' => $trip, 'invitee' => $invitee]);

        if ($existingTripUser) {
            $logger->warning('L\'utilisateur est déjà invité ou participe déjà au voyage.');
            $this->addFlash('error', 'Cet utilisateur est déjà invité ou participe déjà au voyage.');
            return $this->redirectToRoute('app_trip_show', ['id' => $trip->getId()]);
        }

        // Log: Création d'une nouvelle invitation
        $logger->info('Création d\'une nouvelle invitation pour : ' . $invitee->getEmail());

        // Création d'une invitation avec le statut 'pending'
        $invitation = new TripInvitation();
        $invitation->setTrip($trip);
        $invitation->setInvitee($invitee);
        $invitation->setCreator($this->getUser());
        $invitation->setStatus('pending');
        $invitation->setSentAt(new \DateTimeImmutable());

        // Persist and flush the invitation
        $entityManager->persist($invitation);
        $logger->info('Invitation persistée pour l\'utilisateur : ' . $invitee->getEmail());

        $entityManager->flush();
        $logger->info('Flush effectué : invitation sauvegardée dans la base de données.');

        // Ajout du message flash et redirection
        $this->addFlash('success', 'Invitation envoyée.');
        $logger->info('Invitation envoyée avec succès.');

        return $this->redirectToRoute('app_trip_show', ['id' => $trip->getId()]);
    }




    #[Route('/trip/{id}/invite', name: 'trip_invite_form', methods: ['GET'])]
    public function showInviteForm($id, EntityManagerInterface $em): Response
    {
        // Recherche du voyage
        $trip = $em->getRepository(Trip::class)->find($id);
        if (!$trip) {
            throw $this->createNotFoundException('Voyage non trouvé');
        }

        // Retourne la vue avec le formulaire d'invitation
        return $this->render('trip/invite.html.twig', [
            'trip' => $trip,
        ]);
    }

    #[Route('/trip/invitation/{id}/accept', name: 'trip_invitation_accept', methods: ['POST'])]
    public function acceptInvitation(TripInvitation $invitation, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Vérifie que l'utilisateur est bien l'invité
        if ($user !== $invitation->getInvitee()) {
            $this->addFlash('error', 'Accès refusé.');
            return $this->redirectToRoute('dashboard');
        }

        // Accepter l'invitation (changer le statut à "accepted")
        $invitation->setStatus('accepted');
        $em->persist($invitation);

        // Ajouter l'utilisateur en tant que participant au voyage
        $tripUser = new TripUser();
        $tripUser->setTrip($invitation->getTrip());
        $tripUser->setUser($user);
        $tripUser->setRole('participant');

        // Sauvegarder dans la base de données
        $em->persist($tripUser);
        $em->flush();

        // Ajouter un message flash de succès
        $this->addFlash('success', 'Invitation acceptée, vous avez rejoint le voyage.');

        // Redirection vers le tableau de bord ou la page du voyage
        return $this->redirectToRoute('dashboard');
    }


    #[Route('/trip/invitation/{id}/decline', name: 'trip_invitation_decline', methods: ['POST'])]
    public function declineInvitation(TripInvitation $invitation, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($user !== $invitation->getInvitee()) {
            return new JsonResponse(['success' => false, 'error' => 'Accès refusé.'], Response::HTTP_FORBIDDEN);
        }

        $em->remove($invitation);
        $em->flush();

        return $this->redirectToRoute('dashboard');
    }

    

}
