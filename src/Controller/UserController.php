<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\TripUser;
use App\Entity\TripInvitation;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password before saving the user
            if ($form->get('password')->getData()) {
                $hashedPassword = $this->passwordHasher->hashPassword($user, $form->get('password')->getData());
                $user->setPassword($hashedPassword);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $this->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce profil.');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('password')->getData()) {
                $hashedPassword = $this->passwordHasher->hashPassword($user, $form->get('password')->getData());
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_trip_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/user/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
    public function deleteUser(Request $request, User $user, EntityManagerInterface $em): Response
    {
        // Vérification des droits
        if (!$this->isGranted('ROLE_ADMIN') && $this->getUser() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer cet utilisateur.');
        }
    
        // Récupération de l'utilisateur anonyme pour réassigner les entités
        $anonymousUser = $em->getRepository(User::class)->findOneBy(['email' => 'anonymous@forum.com']);
        if (!$anonymousUser) {
            throw new \Exception('Utilisateur anonyme non trouvé.');
        }
    
        // Réassignation des posts et des réponses à l'utilisateur anonyme
        foreach ($user->getForumTopics() as $topic) {
            $topic->setAuthor($anonymousUser);
        }

        // Gestion des voyages liés via TripUser
    $tripUserEntries = $em->getRepository(TripUser::class)->findBy(['user' => $user]);

    foreach ($tripUserEntries as $tripUser) {
        $trip = $tripUser->getTrip();

        // Vérifier si l'utilisateur est le créateur du voyage via le rôle dans TripUser
        if ($tripUser->getRole() === 'créateur') {
            // Si l'utilisateur est le créateur, on réassigne le voyage à l'utilisateur anonyme
            $tripCreatorEntry = $em->getRepository(TripUser::class)->findOneBy([
                'trip' => $trip,
                'user' => $user,
                'role' => 'créateur'
            ]);

            if ($tripCreatorEntry) {
                // Réassigner le rôle de créateur à l'utilisateur anonyme
                $tripCreatorEntry->setUser($anonymousUser);
                $em->persist($tripCreatorEntry);
            }
        }
        // Suppression des invitations créées par cet utilisateur
        $invitationsAsCreator = $em->getRepository(TripInvitation::class)->findBy(['creator' => $user]);
        foreach ($invitationsAsCreator as $invitation) {
        $em->remove($invitation);
    }
        // On retire l'utilisateur du voyage
        $em->remove($tripUser);
    }
    
        // Suppression de l'utilisateur
        $em->remove($user);
        $em->flush();
    
        // Message flash pour confirmer la suppression
        $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');

        return $this->redirectToRoute('app_home');
    }
}
