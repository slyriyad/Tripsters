<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\Expense;
use App\Form\ExpenseType;
use App\Entity\ExpenseSplit;
use App\Repository\TripRepository;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/expense')]
class ExpenseController extends AbstractController
{
    #[Route('/', name: 'app_expense_index', methods: ['GET'])]
    public function index(ExpenseRepository $expenseRepository): Response
    {
        return $this->render('expense/index.html.twig', [
            'expenses' => $expenseRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_expense_show', methods: ['GET'])]
    public function show(Expense $expense): Response
    {
        return $this->render('expense/show.html.twig', [
            'expense' => $expense,
        ]);
    }

    #[Route('/{id}', name: 'app_expense_delete', methods: ['POST'])]
    public function delete(Request $request, Expense $expense, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$expense->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($expense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_expense_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/expense/{id}/edit', name: 'app_expense_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Expense $expense, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $this->addFlash('success', 'La dépense a été mise à jour et répartie automatiquement.');
            return $this->redirectToRoute('app_expense_show', ['id' => $expense->getId()]);
        }
        
        return $this->render('expense/edit.html.twig', [
            'expense' => $expense,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new/{tripId}', name: 'app_expense_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, TripRepository $tripRepository, int $tripId): Response
    {
        $trip = $tripRepository->find($tripId);
        if (!$trip) {
            throw $this->createNotFoundException('Le voyage demandé n\'existe pas.');
        }

        $expense = new Expense();
        $expense->setTrip($trip);
        $expense->setDate(new \DateTime());

        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($expense);
            $entityManager->flush();

            $this->addFlash('success', 'La dépense a été créée et répartie automatiquement.');
            return $this->redirectToRoute('app_trip_show', ['id' => $tripId]);
        }

        return $this->render('expense/new.html.twig', [
            'expense' => $expense,
            'form' => $form->createView(),
            'trip' => $trip,
        ]);
    }
}