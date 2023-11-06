<?php

namespace App\Helper;

use App\Entity\Month;
use App\Entity\Transaction;
use App\Entity\TransactionType;
use App\Entity\User;
use App\Repository\BudgetRepository;
use App\Repository\MonthRepository;
use App\Repository\TransactionRepository;

class MonthDataHelper
{
    const TRANSACTION_DELETE_ACTION = 'transaction_delete_action';
    const TRANSACTION_CREATE_ACTION = 'transaction_create_action';
    public function __construct(
        private TransactionRepository $transactionRepository,
        private MonthRepository $monthRepository,
    ){}

    // This function is to set total amount spent and earned for a month
    // This will be calculate every time I create a new transaction
    public function setTotalAmountSpentAndEarned(Month $month, User $user, string $action = null, ?Transaction $currentTransaction = null): void
    {
        $totalAmountEarned = 0;
        $transactions = $this->transactionRepository->findBy(['month' => $month, 'user' => $user]);
        $totalAmountSpent = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->getType()->getName() === TransactionType::TYPE_SPENT_NAME
                && $transaction->getType()->getAssociatedNumber() === TransactionType::TYPE_SPENT_NUMBER) {
                $totalAmountSpent += $transaction->getAmount();
            } elseif ($transaction->getType()->getName() === TransactionType::TYPE_COLLECTED
                && $transaction->getType()->getAssociatedNumber() === TransactionType::TYPE_COLLECTED_NUMBER) {
                $totalAmountEarned += $transaction->getAmount();
            }
        }

        if ($action === self::TRANSACTION_CREATE_ACTION) {
            if ($currentTransaction->getType()->getName() === TransactionType::TYPE_SPENT_NAME
                && $currentTransaction->getType()->getAssociatedNumber() === TransactionType::TYPE_SPENT_NUMBER) {
                $totalAmountSpent += $currentTransaction->getAmount();
            } elseif ($currentTransaction->getType()->getName() === TransactionType::TYPE_COLLECTED
                && $currentTransaction->getType()->getAssociatedNumber() === TransactionType::TYPE_COLLECTED_NUMBER) {
                $totalAmountEarned += $currentTransaction->getAmount();
            }
        } else if ($action === self::TRANSACTION_DELETE_ACTION) {
            if ($currentTransaction->getType()->getName() === TransactionType::TYPE_SPENT_NAME
                && $currentTransaction->getType()->getAssociatedNumber() === TransactionType::TYPE_SPENT_NUMBER) {
                $totalAmountSpent -= $currentTransaction->getAmount();
            } elseif ($currentTransaction->getType()->getName() === TransactionType::TYPE_COLLECTED
                && $currentTransaction->getType()->getAssociatedNumber() === TransactionType::TYPE_COLLECTED_NUMBER) {
                $totalAmountEarned -= $currentTransaction->getAmount();
            }
        }

        $month->setTotalAmountSpent($totalAmountSpent);
        $month->setTotalAmountEarned($totalAmountEarned);
    }

    public function calculateTotalAmountsForMonthsFromUserTransactions(User $user, $entityManager): bool
    {
        $transactions = $this->transactionRepository->findBy(['user' => $user]);
        $monthsDones = [];

        foreach ($transactions as $transaction) {
            $month = $transaction->getMonth();
            if (!array_key_exists($month->getDate()->format('F'), $monthsDones)) {
                $this->setTotalAmountSpentAndEarned($month, $user, null, null);
                $monthsDones[$month->getDate()->format('F')] = $month;
                $entityManager->persist($month);
            }
        }

        $entityManager->flush();
        return true;
    }
}
