<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Entity\TripExpense;
use App\Entity\TripActivity;
use App\Form\TripExpenseType;
use App\Form\TripActivityType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/trip')]
class TripController extends AbstractController
{
    #[Route('/', name: 'app_trip_index', methods: ['GET'])]
    public function index(TripRepository $tripRepository): Response
    {
        return $this->render('trip/index.html.twig', [
            'trips' => $tripRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_trip_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trip = new Trip();
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trip);
            $entityManager->flush();

            return $this->redirectToRoute('app_trip_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trip/new.html.twig', [
            'trip' => $trip,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trip_show', methods: ['GET'])]
    public function show(Trip $trip): Response
    {
    // Récupérer les activités et dépenses associées au voyage
    $activities = $trip->getTripActivities();
    $expenses = $trip->getTripExpenses();

        return $this->render('trip/show.html.twig', [
        'trip' => $trip,
        'activities' => $activities,
        'expenses' => $expenses,
        ]);
        
    }

    #[Route('/{id}/edit', name: 'app_trip_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trip $trip, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_trip_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trip/edit.html.twig', [
            'trip' => $trip,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trip_delete', methods: ['POST'])]
    public function delete(Request $request, Trip $trip, EntityManagerInterface $entityManager): Response
    {
        //isCsrfTokenValid() est une méthode de Symfony qui vérifie si un token CSRF est valide
        if ($this->isCsrfTokenValid('delete'.$trip->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($trip);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trip_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/add-activity', name: 'trip_add_activity')]
    public function addActivity(Request $request, Trip $trip, EntityManagerInterface $entityManager): Response
    {
        $tripActivity = new TripActivity();
        $tripActivity->setTrip($trip);

        $form = $this->createForm(TripActivityType::class, $tripActivity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tripActivity);
            $entityManager->flush();

            return $this->redirectToRoute('app_trip_show', ['id' => $trip->getId()]);
        }

        return $this->render('trip/add_activity.html.twig', [
            'trip' => $trip,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/add-expense', name: 'trip_add_expense')]
    public function addExpense(Request $request, Trip $trip, EntityManagerInterface $entityManager): Response
    {
        $tripExpense = new TripExpense();
        $tripExpense->setTrip($trip);

        $form = $this->createForm(TripExpenseType::class, $tripExpense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tripExpense);
            $entityManager->flush();

            return $this->redirectToRoute('app_trip_show', ['id' => $trip->getId()]);
        }

        return $this->render('trip/add_expense.html.twig', [
            'trip' => $trip,
            'form' => $form->createView(),
        ]);
    }
}
