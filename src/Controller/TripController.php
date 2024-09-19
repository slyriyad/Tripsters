<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\User;
use App\Form\TripType;
use App\Entity\Comment;
use App\Entity\Expense;
use App\Entity\Activity;
use App\Entity\TripUser;
use App\Entity\ForumTopic;
use App\Form\CommentType;
use App\Form\ExpenseType;
use App\Entity\TripActivity;
use App\Form\TripActivityType;
use Doctrine\ORM\EntityManager;
use App\Repository\TripRepository;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryExpenseRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryActivityRepository;
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
    
            // Créez une nouvelle instance de TripUser
            $tripUser = new TripUser();// Créez une nouvelle instance de TripUser
            $tripUser->setTrip($trip);// Définir le voyage pour le TripUser
            $tripUser->setUser($this->getUser()); // Définir l'utilisateur connecté pour le TripUser
    
            
            $entityManager->persist($tripUser);
    
            
            $entityManager->flush();
    
            $this->addFlash('success', 'Voyage créé avec succès !');
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

        $totalCostExpense = 0;
        foreach ($trip->getExpenses() as $expense) {
            $totalCostExpense += $expense->getAmount();
        }

        // Calcul du coût total
        $totalCost = $totalCostActivity + $totalCostExpense;

        // Vérification si le budget est dépassé
        $isBudgetExceeded = $totalCost > $trip->getBudget();

        $categories = $categoryRepository->findAll();

        return $this->render('trip/show.html.twig', [
            'trip' => $trip,
            'activities' => $activities,
            'days' => $days,
            'totalCost' => $totalCost,
            'categories' => $categories,
            'isBudgetExceeded' => $isBudgetExceeded, // Passer l'information à la vue
        ]);
    }


    #[Route('/{id}/edit', name: 'app_trip_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trip $trip, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ici, VichUploader doit gérer l'image via le setter
            if ($form->get('imageFile')->getData()) {
                $trip->setImageFile($form->get('imageFile')->getData());
            }

            $entityManager->flush();

            $this->addFlash('success', 'Le voyage a été modifié avec succès !');
            return $this->redirectToRoute('app_trip_show', ['id' => $trip->getId()]);
        }

        return $this->render('trip/edit.html.twig', [
            'trip' => $trip,
            'form' => $form->createView(),
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
            return new JsonResponse(['error' => 'Voyage non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);

        // Vérifier les dates de l'activité
        $startDate = new \DateTime($data['startDate']);
        $endDate = new \DateTime($data['endDate']);
        $tripStartDate = $trip->getStartDate();
        $tripEndDate = $trip->getEndDate();

        // Vérifier si l'activité est en dehors de la période du voyage
        if ($startDate < $tripStartDate || $endDate > $tripEndDate) {
            return new JsonResponse(['error' => 'Les dates de l\'activité doivent être comprises dans la période du voyage.'], 400);
        }

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
        $tripActivity->setStartDate($startDate);
        $tripActivity->setEndDate($endDate);

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

    #[Route('/activity/{id}', name: 'app_activity_show', methods: ['GET', 'POST'])]
    public function showAct(Request $request, Activity $activity, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setActivity($activity);
            $comment->setCreatedAt(new \DateTime());
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté.');
            return $this->redirectToRoute('app_activity_show', ['id' => $activity->getId()]);
        }

        return $this->render('activity/show.html.twig', [
            'activity' => $activity,
            'commentForm' => $form->createView(),
        ]);
    }

    #[Route('/{tripId}/expenses', name: 'app_expense_index', methods: ['GET', 'POST'])]
    public function indexexpense(Request $request, int $tripId, ExpenseRepository $expenseRepository, CategoryExpenseRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le voyage
        $trip = $entityManager->getRepository(Trip::class)->find($tripId);
        
        if (!$trip) {
            throw $this->createNotFoundException('Le voyage demandé n\'existe pas.');
        }

        // Obtenir les dépenses par catégorie
        $expensesByCategory = $expenseRepository->getSumByCategory($trip);

        // Calculer le total des dépenses
        $totalExpenses = array_sum(array_column($expensesByCategory, 'total'));

        // Calculer si le budget est dépassé
        $budgetExceeded = $totalExpenses > $trip->getBudget();

        // Récupérer les récentes dépenses
        $recentExpenses = $expenseRepository->findBy(['trip' => $trip], ['date' => 'DESC'], 5);

        // Récupérer toutes les catégories
        $allCategories = $categoryRepository->findAll();

        // Calcul du coût total des activités
        $totalCostActivity = 0;
        foreach ($trip->getTripActivities() as $tripActivity) {
            $totalCostActivity += $tripActivity->getActivity()->getCost();
        }

        // Création d'une nouvelle dépense
        $expense = new Expense();
        $expense->setTrip($trip);

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

        $totalCostExpense = 0;
        foreach ($trip->getExpenses() as $expense) {
            $totalCostExpense += $expense->getAmount();
        }

        // Calcul du coût total
        $totalCost = $totalCostActivity + $totalCostExpense;
        $isBudgetExceeded = $totalCost > $trip->getBudget();

        // Formulaire d'ajout de dépense
        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($expense);
            $entityManager->flush();

            $this->addFlash('success', 'Dépense ajoutée avec succès.');
            return $this->redirectToRoute('app_expense_index', ['tripId' => $tripId]);
        }

        // Rendu du template avec les données
        return $this->render('expense/index.html.twig', [
            'expenses' => $expenseRepository->findBy(['trip' => $trip]),
            'expenseForm' => $form->createView(),
            'trip' => $trip,
            'expensesByCategory' => $expensesByCategory,
            'totalExpenses' => $totalExpenses,
            'recentExpenses' => $recentExpenses,
            'totalCostActivity' => $totalCostActivity,
            'budgetExceeded' => $budgetExceeded, // Ajout de l'information de dépassement du budget
            'allCategories' => $allCategories,
            'totalCost' => $totalCost,
            'isBudgetExceeded' => $isBudgetExceeded,
        ]);
    }

}