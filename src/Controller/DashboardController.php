<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TripInvitation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{

    

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer les invitations en attente
        $invitations = $entityManager->getRepository(TripInvitation::class)
            ->findBy(['invitee' => $user, 'status' => 'pending']);

        return $this->render('dashboard/index.html.twig', [
            'invitations' => $invitations,
        ]);
    }

    


}
