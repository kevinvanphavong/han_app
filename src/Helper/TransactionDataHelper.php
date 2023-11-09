<?php

namespace App\Helper;

use App\Entity\Budget;
use App\Entity\Transaction;
use App\Entity\User;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Doctrine\Persistence\ManagerRegistry;

class TransactionDataHelper
{
    private const NO_BUDGET_CHOSEN = 'NO_BUDGET_CHOSEN';
    private const NO_BUDGET_CHOSEN_CODE = 444;
    private const NEW_BUDGET_NOT_COMPLETED = 'NEW_BUDGET_NOT_COMPLETED';
    private const NEW_BUDGET_NOT_COMPLETED_CODE = 555;
    public function __construct(private TransactionRepository $transactionRepository)
    {}

    public function setNewDataTransaction($transactionForm, User $user, $entityManager): Transaction|array
    {
        $transactionFormData = $transactionForm->getData();

        $day = $transactionFormData['date']->format('d');
        $month = $transactionFormData['month']->getDate()->format('F');
        $year = $transactionFormData['month']->getDate()->format('Y');
        $date = new \DateTime("$day $month $year");
        $transaction = new Transaction();
        $transaction->setName($transactionFormData['name']);
        $transaction->setDate($date);
        $transaction->setMonth($transactionFormData['month']);
        $transaction->setAmount($transactionFormData['amount']);
        $transaction->setType($transactionFormData['type']);

        if ($transactionFormData['budgetCategory'] === null
            && $transactionFormData['newBudgetName'] === null
            && $transactionFormData['newBudgetAmount'] === null
        ) {
            return [
                'errorCode' => self::NO_BUDGET_CHOSEN_CODE,
                'errorMessage' => 'You have to add or create a budget for this transaction',
            ];
        } elseif (
            $transactionFormData['newBudgetName'] === null && $transactionFormData['newBudgetAmount'] !== null
            || $transactionFormData['newBudgetName'] !== null && $transactionFormData['newBudgetAmount'] === null
        ) {
            return [
                'errorCode' => self::NEW_BUDGET_NOT_COMPLETED_CODE,
                'errorMessage' => "You didn't complete the new budget informations (name and amount)",
            ];
        }

        if ($transactionFormData['budgetCategory'] !== null) {
            $transaction->setBudgetCategory($transactionFormData['budgetCategory']);
        } elseif ($transactionFormData['newBudgetName'] !== null) {
            $budget = new Budget();
            $budget->setName($transactionFormData['newBudgetName']);
            $budget->setAmount($transactionFormData['newBudgetAmount']);
            $budget->setUser($user);
            $transaction->setBudgetCategory($budget);
            $entityManager->persist($budget);
        }

        return $transaction;
    }

    public function getTransactionsSumsForBudgetGroupByMonth($transactions, $months, $budgets): array
    {
        $transactionsSumByBudgetForMonths = [];
        foreach ($months as $month) {
            $transactionsSumByBudgetForMonths[$month->getDate()->format('F-Y')] = [];
            foreach($transactions as $transaction) {
                if ($transaction->getType()->getName() === \App\Entity\TransactionType::TYPE_SPENT_NAME && $transaction->getMonth()->getDate()->format('F') === $month->getDate()->format('F')) {
                    $budgetName = $transaction->getBudgetCategory()->getName();
                    if (!isset($transactionsSumByBudgetForMonths[$month->getDate()->format('F-Y')][$budgetName])) {
                        $transactionsSumByBudgetForMonths[$month->getDate()->format('F-Y')][$budgetName] = ['amount' => $transaction->getAmount(), 'ratio' => 0];
                    } else {
                        $transactionsSumByBudgetForMonths[$month->getDate()->format('F-Y')][$budgetName]['amount'] += $transaction->getAmount();
                    }
                }
            }
        }
        $this->calculateRatioFromSumTransactionWithBudget($transactionsSumByBudgetForMonths, $budgets);

        return $transactionsSumByBudgetForMonths;
    }

    private function calculateRatioFromSumTransactionWithBudget(&$transactionsSumByBudgetForMonths, $budgets): array
    {
        foreach ($transactionsSumByBudgetForMonths as $monthName => $transactionsSumByBudget) {
            foreach($budgets as $budget) {
                $budgetName = $budget->getName();
                if (isset($transactionsSumByBudget[$budgetName])) {
                    $transactionsSumByBudgetForMonths[$monthName][$budgetName] = [
                        'amount' => $transactionsSumByBudget[$budgetName]['amount'],
                        'ratio' => $transactionsSumByBudget[$budgetName]['amount'] / $budget->getAmount() * 100,
                    ];
                } else {
                    $transactionsSumByBudgetForMonths[$monthName][$budgetName] = [
                        'amount' => 0,
                        'ratio' => 0,
                    ];
                }
            }
        }

        return $transactionsSumByBudgetForMonths;
    }

    public function getTransactionsTotalByBudget($transactions): array
    {
        $total = [];
        foreach ($transactions as $transaction) {
            if ($transaction->getType()->getName() === \App\Entity\TransactionType::TYPE_SPENT_NAME) {
                $budgetName = $transaction->getBudgetCategory()->getName();
                if (!isset($total[$budgetName])) {
                    $total[$budgetName] = $transaction->getAmount();
                } else {
                    $total[$budgetName] += $transaction->getAmount();
                }
            }
        }

        return $total;
    }

    public function getTransactionsWithFilters($filtersFormData, $user)
    {
        // Retirer tout les lignes du tableau qui ont une value null
        $filtersFormData = array_filter($filtersFormData, function ($value) {
            return $value !== null;
        });
        $filtersFormData['user'] = $user;
        return $this->transactionRepository->findWithFilters($filtersFormData);
    }
}
