<?php

namespace App\Controller;

use App\Form\BudgetType;
use App\Repository\BudgetRepository;
use App\Repository\TransactionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BudgetController extends AbstractController
{
    public function __construct(
        private BudgetRepository      $budgetRepository,
        private TransactionRepository $transactionRepository,
        private ManagerRegistry       $managerRegistry,
    )
    {
    }

    #[Route('/budget/{id}/delete', name: 'budget_delete_action')]
    public function deleteBudget($id): Response
    {
        $budget = $this->budgetRepository->find($id);
        $entityManager = $this->managerRegistry->getManager();
        $transactions = $this->transactionRepository->findBy([
            'budgetCategory' => $budget,
            'user' => $this->getUser()
        ]);

        if (count($transactions) > 0) {
            $this->addFlash('error', 'You cannot delete a budget that has transactions linked to it');
            return $this->redirectToRoute('dashboard_page');
        }

        $entityManager->remove($budget);
        $entityManager->flush();
        $this->addFlash('success', 'Budget deleted successfully');
        return $this->redirectToRoute('dashboard_page');
    }

    #[Route('/budget/{id}/edit', name: 'budget_edit_action')]
    public function editBudget(Request $request, $id): Response
    {
        $budget = $this->budgetRepository->find($id);
        $budgetForm = $this->createForm(BudgetType::class, $budget);
        $budgetForm->handleRequest($request);

        if ($budgetForm->isSubmitted() && $budgetForm->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($budget);
            $entityManager->flush();
            $this->addFlash('success', 'Budget edited successfully');
            return $this->redirectToRoute('dashboard_page');
        }

        return $this->render('budget/edit.html.twig', [
            'budgetForm' => $budgetForm->createView(),
        ]);
    }
}

