<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Entity\Activity;
use App\Entity\TripActivity;
use App\Entity\User;
use App\Form\TripActivityType;
use App\Repository\TripRepository;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryActivityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/trip')]
class TripController extends AbstractController
{
    #[Route('/', name: 'app_trip_index', methods: ['GET'])]
    public function index(TripRepository $tripRepository): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!$this->getUser() instanceof User) {
            return $this->redirectToRoute('app_login');
        }



        // Récupérer les voyages de l'utilisateur connecté
        $trips = $tripRepository->findTripsByUser($this->getUser());

        // Rendre la vue avec les voyages
        return $this->render('trip/index.html.twig', [
            'trips' => $trips,
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
    public function show(Trip $trip, CategoryActivityRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
        return $this->redirectToRoute('app_login');
    }
           
        
    // Récupérer les activités et dépenses associées au voyage
    $activities = $trip->getTripActivities();
    $startDate = $trip->getStartDate();
    $endDate = $trip->getEndDate();
    $interval = $startDate->diff($endDate);
    $days = $interval->days;

    $totalCostActivity = 0;
    foreach ($trip->getTripActivities() as $tripActivity) {
        $totalCostActivity += $tripActivity->getActivity()->getCost();
    }

    $categories = $categoryRepository->findAll();

        return $this->render('trip/show.html.twig', [
        'trip' => $trip,
        'activities' => $activities,
        'days' => $days,
        'totalCostActivity' => $totalCostActivity,
        'activities' => $activities,
        'categories' => $categories,
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

    #[Route('/{tripId}/add-activity', name: 'trip_add_activity', methods: ['POST'])]
    public function addActivity(Request $request, int $tripId, TripRepository $tripRepository, CategoryActivityRepository $categoryRepository, EntityManagerInterface $entityManager): JsonResponse
{
    $trip = $tripRepository->find($tripId);
    if (!$trip) {
        return new JsonResponse(['error' => 'Trip not found'], 404);
    }

    $data = json_decode($request->getContent(), true);

    $activity = new Activity();
    $activity->setName($data['name']);
    $activity->setDescription($data['description']);
    $activity->setCost($data['cost']);
    $activity->setCreatedBy($this->getUser());  

    if (isset($data['categoryId'])) {
        $category = $categoryRepository->find($data['categoryId']);
        if ($category) {
            $activity->setCategoryActivity($category);
        }
    }

    $tripActivity = new TripActivity();
    $tripActivity->setTrip($trip);
    $tripActivity->setActivity($activity);
    $tripActivity->setStartDate(new \DateTime($data['startDate']));
    $tripActivity->setEndDate(new \DateTime($data['endDate']));

    $entityManager->persist($activity);
    $entityManager->persist($tripActivity);
    $entityManager->flush();

    $createdBy = $activity->getCreatedBy();
    $profilePicture = null;
    if ($createdBy && $createdBy->getImageName()) {
        $profilePicture = $this->getParameter('app.path.avatars') . '/' . $createdBy->getImageName();
    }

    return new JsonResponse([
        'id' => $tripActivity->getId(),
        'name' => $activity->getName(),
        'description' => $activity->getDescription(),
        'cost' => $activity->getCost(),
        'startDate' => $tripActivity->getStartDate()->format('Y-m-d H:i:s'),
        'endDate' => $tripActivity->getEndDate()->format('Y-m-d H:i:s'),
        'createdBy' => [
            'id' => $createdBy ? $createdBy->getId() : null,
            'email' => $createdBy ? $createdBy->getEmail() : null,
            'profilePicture' => $profilePicture,
        ],
    ]);
}

    #[Route('/{id}/balances', name: 'app_trip_balances', methods: ['GET'])]
    public function showBalances(Trip $trip, ExpenseRepository $expenseRepository): Response
    {
        // Logique pour calculer les soldes
        $expenses = $expenseRepository->findBy(['trip' => $trip]);
        $balances = $this->calculateBalances($trip, $expenses);

        return $this->render('trip/balances.html.twig', [
            'trip' => $trip,
            'balances' => $balances,
        ]);
    }

    private function calculateBalances(Trip $trip, array $expenses): array
{
    $balances = [];
    foreach ($trip->getParticipants() as $participant) {
        $balances[$participant->getId()] = 0;
    }

    foreach ($expenses as $expense) {
        $paidBy = $expense->getPaidBy();
        if ($paidBy) {
            $paidById = $paidBy->getId();
            if (!isset($balances[$paidById])) {
                $balances[$paidById] = 0;
            }
            $balances[$paidById] += $expense->getAmount();
        }

        foreach ($expense->getExpenseSplits() as $split) {
            $user = $split->getUser();
            if ($user) {
                $userId = $user->getId();
                if (!isset($balances[$userId])) {
                    $balances[$userId] = 0;
                }
                $balances[$userId] -= $split->getAmount();
            }
        }
    }

    return $balances;
    }
}