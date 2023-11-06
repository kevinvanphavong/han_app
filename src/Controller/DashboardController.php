<?php

namespace App\Controller;

use App\Entity\Month;
use App\Entity\Transaction;
use App\Form\BudgetType;
use App\Form\MonthType;
use App\Form\TransactionFilterType;
use App\Form\TransactionType;
use App\Helper\MonthDataHelper;
use App\Helper\TransactionDataHelper;
use App\Repository\BudgetRepository;
use App\Repository\MonthRepository;
use App\Repository\TransactionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    private const TABLE_MAX_COLUMNS = 3;

    public function __construct(
        private ManagerRegistry $managerRegistry,
        private TransactionDataHelper $transactionDataHelper,
        private MonthDataHelper $monthDataHelper,
        private TransactionRepository $transactionRepository,
        private MonthRepository $monthRepository,
        private BudgetRepository $budgetRepository
    ) {}

    #[Route('/', name: 'dashboard_page')]
    public function index(
        Request $request,
    ): Response {
        $user = $this->getUser();
        $transactionFilterForm = $this->createForm(TransactionFilterType::class, [], [
            'user' => $user,
            'months' => $this->monthRepository->findBy(['user' => $user], ['date' => 'DESC']),
            'url' => $this->generateUrl('dashboard_page'),
        ]);
        $transactionFilterForm->remove('limit');
        $transactionFilterForm->handleRequest($request);
        $transactions = $this->transactionRepository->findBy(['user' => $user], ['month' => 'DESC'], 20);
        if ($transactionFilterForm->isSubmitted() && $transactionFilterForm->isValid()) {
            $transactions = $this->transactionDataHelper->getTransactionsWithFilters($transactionFilterForm->getData(), $user);
            $this->addFlash('success', 'Filters applied successfully');
        }

        return $this->render('dashboard/index.html.twig', [
            'transactions' => $transactions,
            'months' => $this->monthRepository->findBy(['user' => $user], ['date' => 'DESC']),
            'budgets' => $this->budgetRepository->findBy(['user' => $user]),
            'transactionFilterForm' => $transactionFilterForm->createView(),
            'isLoginReferer' => str_contains($request->headers->get('referer'), 'login'),
            'emojis' => ['🤍','🖤','💜','❤️‍🩹','💙','💚','❤️‍🔥','🫀','💘', '️❤️']
        ]);
    }

    #[Route('/form/creation', name: 'creation_form_page')]
    public function creation(Request $request): Response{
        $entityManager = $this->managerRegistry->getManager();
        $monthForm = $this->createForm(MonthType::class);
        $monthForm->handleRequest($request);
        $budgetForm = $this->createForm(BudgetType::class);
        $budgetForm->handleRequest($request);

        $months = $this->managerRegistry->getRepository(Month::class)->findBy(['user' => $this->getUser()]);
        $transactionForm = $this->createForm(TransactionType::class, [], [
            'user' => $this->getUser(),
            'months' => $months,
            'new_budget_is_enable' => true,
        ]);
        $transactionForm->handleRequest($request);

        if (
            $transactionForm->isSubmitted()
            || $budgetForm->isSubmitted()
            || $monthForm->isSubmitted()
        ) {
            if ($transactionForm->isSubmitted() && $transactionForm->isValid()) {
                $newTransaction = $this->transactionDataHelper->setNewDataTransaction($transactionForm, $this->getUser(), $entityManager);
                $this->monthDataHelper->setTotalAmountSpentAndEarned(
                    $newTransaction->getMonth(),
                    $this->getUser(),
                    MonthDataHelper::TRANSACTION_CREATE_ACTION,
                    $newTransaction
                );
                $newTransaction->setUser($this->getUser());
                $entityManager->persist($newTransaction);
                $this->addFlash('success', 'Transaction created successfully');
            } elseif ($monthForm->isSubmitted() && $monthForm->isValid()) {
                $monthForm->getData()->setUser($this->getUser());
                $entityManager->persist($monthForm->getData());
                $this->addFlash('success', 'Month created successfully');
            } elseif ($budgetForm->isSubmitted() && $budgetForm->isValid()) {
                $budgetForm->getData()->setUser($this->getUser());
                $entityManager->persist($budgetForm->getData());
                $this->addFlash('success', 'Budget created successfully');
            }
            $entityManager->flush();
            return $this->redirectToRoute('dashboard_page');
        }

        return $this->render('dashboard/new.html.twig', [
            'transactionForm' => $transactionForm->createView(),
            'budgetForm' => $budgetForm->createView(),
            'monthForm' => $monthForm->createView(),
        ]);
    }

    #[Route('/stats/expenses', name: 'stats_expenses_page')]
    public function displayMonthTableWithBudgets(
        TransactionRepository $transactionRepository,
        MonthRepository $monthRepository,
        BudgetRepository $budgetRepository
    ): Response {
        $user = $this->getUser();
        $transactions = $transactionRepository->findBy(['user' => $user]);
        $months = $monthRepository->findBy(['user' => $user], ['date' => 'DESC']);
        $budgets = $budgetRepository->findBy(['user' => $user]);
        $transactionsSumByBudgetForMonths = $this->transactionDataHelper->getTransactionsSumsForBudgetGroupByMonth($transactions, $months, $budgets);
        $transactionsSumByBudget = $this->transactionDataHelper->getTransactionsTotalByBudget($transactions);

        return $this->render('dashboard/stats-expenses.html.twig', [
            'transactions' => $transactions,
            'months' => $months,
            'budgetsCount' => count($budgets),
            'containerBudgets' => array_chunk($budgets, self::TABLE_MAX_COLUMNS),
            'budgetsNames' => array_map(function ($budget) {
                return $budget->getName();
            }, $budgets),
            'transactionsSum' => $transactionsSumByBudgetForMonths,
            'transactionsSumByBudget' => $transactionsSumByBudget
        ]);
    }

    #[Route('/transactions', name: 'more_transactions_page')]
    public function displayMoreTransactions(Request $request)
    {
        $user = $this->getUser();
        $transactionFilterForm = $this->createForm(TransactionFilterType::class, [], [
            'user' => $user,
            'months' => $this->monthRepository->findBy(['user' => $user], ['date' => 'DESC']),
            'url' => $this->generateUrl('dashboard_page'),
        ]);
        $transactionFilterForm->handleRequest($request);
        $transactions = $this->transactionRepository->findBy(['user' => $this->getUser()], ['date' => 'DESC']);
        if ($transactionFilterForm->isSubmitted() && $transactionFilterForm->isValid()) {
            $transactions = $this->transactionDataHelper->getTransactionsWithFilters($transactionFilterForm->getData(), $user);
            $this->addFlash('success', 'Filters applied successfully');
        }

        return $this->render('dashboard/more-transactions.html.twig', [
            'transactions' => $transactions,
            'totalCountTransactions' => count($transactions),
            'transactionFilterForm' => $transactionFilterForm->createView(),
        ]);
    }
}
