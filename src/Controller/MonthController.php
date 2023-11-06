<?php

namespace App\Controller;

use App\Form\MonthType;
use App\Repository\BudgetRepository;
use App\Repository\MonthRepository;
use App\Repository\TransactionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonthController extends AbstractController
{
    public function __construct(
       private ManagerRegistry $managerRegistry,
       private MonthRepository $monthRepository,
       private BudgetRepository $budgetRepository,
       private TransactionRepository $transactionRepository,
    ) {}
    #[Route('/month/{id}/delete', name: 'month_delete_action')]
    public function deleteMonth($id): Response
    {
        $month = $this->monthRepository->find($id);
        $entityManager = $this->managerRegistry->getManager();
        $transactions = $this->transactionRepository->findBy([
            'month' => $month,
            'user' => $this->getUser()
        ]);

        if (count($transactions) > 0) {
            $this->addFlash('error', 'You cannot delete a month that has transactions linked to it');
            return $this->redirectToRoute('dashboard_page');
        }

        $entityManager->remove($month);
        $entityManager->flush();
        $this->addFlash('success', 'Month deleted successfully');
        return $this->redirectToRoute('dashboard_page');
    }

    #[Route('/month/{id}/edit', name: 'month_edit_action')]
    public function editMonth(Request $request, $id): Response
    {
        $month = $this->monthRepository->find($id);
        $monthForm = $this->createForm(MonthType::class, $month, [
            'budgets' => $this->budgetRepository->findBy(['user' => $this->getUser()])
        ]);
        $monthForm->handleRequest($request);

        if ($monthForm->isSubmitted() && $monthForm->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($month);
            $entityManager->flush();
            $this->addFlash('success', 'Month edited successfully');
            return $this->redirectToRoute('dashboard_page');
        }

        return $this->render('month/edit.html.twig', [
            'monthForm' => $monthForm->createView(),
        ]);
    }
}
