<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Helper\MonthDataHelper;
use App\Helper\TransactionDataHelper;
use App\Repository\MonthRepository;
use App\Repository\TransactionRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class TransactionController extends AbstractController
{
    public function __construct(
        public TransactionRepository $transactionRepository,
        public ManagerRegistry $managerRegistry,
        public MonthRepository $monthRepository,
        public MonthDataHelper $monthDataHelper,
        public TransactionDataHelper $transactionDataHelper,
    ) {}

    #[Route('/transaction/{id}/delete', name: 'transaction_delete_action')]
    public function deleteTransaction($id): Response
    {
        $transaction = $this->transactionRepository->find($id);
        $this->monthDataHelper->setTotalAmountSpentAndEarned(
            $transaction->getMonth(),
            $this->getUser(),
            MonthDataHelper::TRANSACTION_DELETE_ACTION,
            $transaction
        );
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->remove($transaction);
        $entityManager->flush();
        $this->addFlash('success', 'Transaction deleted successfully');
        return $this->redirectToRoute('dashboard_page');
    }

    /**
     * @throws Exception
     */
    #[Route('/transaction/{id}/edit', name: 'transaction_edit_action')]
    public function editTransaction(Request $request, $id): Response
    {
        $transaction = $this->transactionRepository->find($id);
        $transactionForm = $this->createForm(TransactionType::class, $transaction, [
            'user' => $this->getUser(),
            'months' => $this->monthRepository->findBy(['user' => $this->getUser()]),
            'data_class' => Transaction::class,
            'new_budget_is_enable' => false
        ]);
        $transactionForm->handleRequest($request);

        if ($transactionForm->isSubmitted() && $transactionForm->isValid()) {
            $day = $transactionForm->getData()->getDate()->format('d');
            $monthYear = $transactionForm->getData()->getMonth()->getDate()->format('F Y');
            $transaction->setDate(new \DateTime($day . ' ' . $monthYear));

            $entityManager = $this->managerRegistry->getManager();
            $this->monthDataHelper->setTotalAmountSpentAndEarned(
                $transaction->getMonth(),
                $this->getUser(),
                null,
                $transaction
            );
            $entityManager->persist($transaction);
            $entityManager->flush();
            $this->addFlash('success', 'Transaction edited successfully');
            return $this->redirectToRoute('dashboard_page');
        }

        return $this->render('transaction/edit.html.twig', [
            'transactionForm' => $transactionForm->createView(),
        ]);
    }
}
