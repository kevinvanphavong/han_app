<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\TransactionType;
use App\Repository\MonthRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TransactionTypeRepository;

class FileTransactionsController extends AbstractController
{
    public function __construct(
        private TransactionTypeRepository       $transactionTypeRepository,
        private MonthRepository                 $monthRepository,
        private readonly EntityManagerInterface $entityManager,
    )
    {}

    #[Route('/upload-your-transactions', name: 'upload_file_transactions')]
    public function index(): Response
    {
        // Récupérer le fichier qui se trouve dans le dossier public/files à la racine du projet
        $filePath = $this->getParameter('kernel.project_dir').'/public/files/transactions-mid-may-2024.csv';
        $fileContent = $this->readTransactionsCsvFile($filePath);

        // Récupérer le mois actuel pour l'ajouter aux transactions
        $currentMonth = $this->monthRepository->findOneBy(['user' => $this->getUser()], ['date' => 'DESC']);
        // Supprimer la dernière ligne qui n'est pas une transaction
        array_pop($fileContent);

        $newTransactions = [];
        $newTransactionsForm = [];
        foreach ($fileContent as $transaction) {
            $transactionDate = $this->formatTransactionDate($transaction[0]);
            $newDate = DateTime::createFromFormat('d/m/Y', $transactionDate);

            $newTransaction = new Transaction();
            $newTransaction->setUser($this->getUser());
            $newTransaction->setDate($newDate);
            $newTransaction->setName($transaction[4]);
            $newTransaction->setAmount(abs(intval($transaction[1])));
            $newTransaction->setType(
                $this->getTransactionTypeByAmount($transaction[1])
            );
            $newTransaction->setFromUpload(true);

            $newTransactionForm = $this->createForm(\App\Form\TransactionType::class, $newTransaction, [
                'user' => $this->getUser(),
                'date' => $newDate,
                'current_month' => $currentMonth,
                'months' => $this->monthRepository->findBy(['user' => $this->getUser()]),
                'data_class' => Transaction::class,
                'new_budget_is_enable' => false,
                'delete_button' => true
            ]);

            $newTransactionsForm[] = $newTransactionForm->createView();
            $newTransactions[] = $newTransaction;
        }

        return $this->render('file_transactions/index.html.twig', [
            'uploaded_transactions_form' => $newTransactionsForm,
            'uploaded_transactions' => $newTransactions,
            'controller_name' => 'FileTransactionsController',
        ]);
    }

    private function readTransactionsCsvFile($filePath): array
    {
        $csvData = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                $csvData[] = $data;
            }
            fclose($handle);
        }

        return $csvData;
    }

    private function getTransactionTypeByAmount($transactionAmount): TransactionType|null
    {
        if ($transactionAmount > 0) {
            $transactionType = $this->transactionTypeRepository->findOneBy(['name' => 'Collected']);
        } else if ($transactionAmount < 0) {
            $transactionType = $this->transactionTypeRepository->findOneBy(['name' => 'Spent']);
        }
        return $transactionType ?? null;
    }

    private function formatTransactionDate($transactionDate): string
    {
        return preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $transactionDate);
    }
}
