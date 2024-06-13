<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\TransactionType;
use App\Form\FileTransactionsType;
use App\Repository\BudgetRepository;
use App\Repository\MonthRepository;
use App\Repository\TransactionRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TransactionTypeRepository;

class FileTransactionsController extends AbstractController
{
    private const SAVE_ACTION = 'save';
    private const DELETE_ACTION = 'delete';

    public function __construct(
        private TransactionTypeRepository       $transactionTypeRepository,
        private MonthRepository                 $monthRepository,
        private BudgetRepository                $budgetRepository,
        private readonly EntityManagerInterface $entityManager, private readonly TransactionRepository $transactionRepository,
    ) {}

    #[Route('/upload-your-transactions', name: 'upload_file_transactions')]
    public function index(Request $request): Response
    {
        $unlockedMonths = $this->monthRepository->findBy(['user' => $this->getUser(), 'isLocked' => false]);
        // Gérer le formulaire FileTransactionsForm
        $fileTransactionsForm = $this->createForm(FileTransactionsType::class, null, [
            'months' => $unlockedMonths
        ]);

        $fileTransactionsForm->handleRequest($request);
        if ($fileTransactionsForm->isSubmitted() && $fileTransactionsForm->isValid()) {
            /** @var UploadedFile $file */
            $file = $fileTransactionsForm->get('file')->getData();
            $fileContent = $this->readAndGetTransactionsCsvFile($file);

            $currentMonth = $fileTransactionsForm->getData()['month'];
            array_pop($fileContent);
            if (count($fileContent) > 0) {
                foreach ($fileContent as $transaction) {
                    $newTransaction = $this->saveTransactionAfterUpload($transaction, $currentMonth);
                    $this->entityManager->persist($newTransaction);
                }
                $this->entityManager->flush();
            }

            $this->addFlash('success', 'File checked successfully - You just upload ' . count($fileContent) . ' transactions' );
            return $this->redirectToRoute('upload_file_transactions');
        }

        $newTransactionsForm = [];
        $transactions = $this->transactionRepository->findBy(['user' => $this->getUser(), 'from_upload' => true]);
        foreach ($transactions as $transaction) {
            $newTransactionForm = $this->createForm(\App\Form\TransactionType::class, $transaction, [
                'user' => $this->getUser(),
                'date' => $transaction->getDate(),
                'months' => $unlockedMonths,
                'data_class' => Transaction::class,
                'new_budget_is_enable' => false,
                'saveButton' => false,
            ]);

            $newTransactionsForm[] = $newTransactionForm->createView();
        }

        return $this->render('file_transactions/index.html.twig', [
            'uploadedTransactionsForm' => $newTransactionsForm,
            'fileTransactionsForm' => $fileTransactionsForm,
        ]);
    }

    #[Route('/update-your-uploaded-transaction', name: 'update_uploaded_transaction', methods: ['POST'])]
    public function updateUploadedTransaction(Request $request): JsonResponse
    {
        $transactionData = $request->request->all()['transaction'];
        $transactionId = $request->request->all()['transactionId'];
        $actionName = $request->request->all()['actionName'];

        $transaction = $this->entityManager->getRepository(Transaction::class)->find($transactionId);

        $message = 'Transaction not found - Error with ID returned';
        if (!$transaction) {
            return new JsonResponse(['status' => 400, 'class' => 'error', 'message' => $message], 400);
        }

        if ($actionName === self::SAVE_ACTION) {
            $message = 'Transaction updated successfully';

            // Vérifier que le user a bien rempli tout les champs
            foreach ($transactionData as $transactionPropertyName => $transactionPropertyValue) {
                if ($transactionPropertyValue === '') {
                    $message = sprintf('You have to complete all the field - %s', $transactionPropertyName);
                    return new JsonResponse(['status' => 400, 'class' => 'error', 'message' => $message], 400);
                }
            }
            // Appliquer les modifications du user sur l'entité
            $this->saveTransactionAfterChecked($transactionData, $transaction);
            return new JsonResponse(['status' => 200, 'class' => 'success', 'message' => $message], 200);
        } elseif ($actionName === self::DELETE_ACTION) {
            $message = 'Transaction deleted successfully';
            $this->entityManager->remove($transaction);
            $this->entityManager->flush();
        }

        return new JsonResponse(['status' => 200, 'class' => 'success', 'message' => $message], 200);
    }

    private function readAndGetTransactionsCsvFile($file)
    {
        $content = [];
        if (UploadedFile::class === get_class($file)) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            // Déplacer le fichier dans le répertoire où les fichiers téléchargés sont stockés
            try {
                $file->move(
                    $this->getParameter('uploads_directory'), // Assurez-vous de configurer ce paramètre
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer l'exception si quelque chose se passe mal pendant le téléchargement
            }

            // Vous pouvez maintenant utiliser le fichier, par exemple lire son contenu
            $filePath = $this->getParameter('uploads_directory').'/'.$newFilename;
            // Traiter le contenu du fichier ici

            $content = [];
            if (($handle = fopen($filePath, 'r')) !== false) {
                while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                    $content[] = $data;
                }
                fclose($handle);
            }

        }
        return $content;
    }

    private function getTransactionTypeByAmount($transactionAmount): TransactionType|null
    {
        if ($transactionAmount > 0) {
            $transactionType = $this->transactionTypeRepository->findOneBy(['associatedNumber' => true]);
        } else if ($transactionAmount < 0) {
            $transactionType = $this->transactionTypeRepository->findOneBy(['associatedNumber' => false]);
        }
        return $transactionType ?: null;
    }

    private function formatTransactionDate($transactionDate): string
    {
        return preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $transactionDate);
    }

    private function saveTransactionAfterUpload($transaction, $month): Transaction
    {
        $transactionDate = $this->formatTransactionDate($transaction[0]);
        $newDate = DateTime::createFromFormat('d/m/Y', $transactionDate);

        $newTransaction = new Transaction();
        $newTransaction->setUser($this->getUser());
        $newTransaction->setDate($newDate);
        $newTransaction->setMonth($month);
        $newTransaction->setName($transaction[4] . ' ' . $transaction[5]);
        $newTransaction->setAmount(abs(intval($transaction[1])));
        $newTransaction->setType(
            $this->getTransactionTypeByAmount($transaction[1])
        );
        $newTransaction->setFromUpload(true);

        return $newTransaction;
    }

    private function saveTransactionAfterChecked($transactionData, $transactionToUpdate): void
    {
        // Création d'une nouvelle instance de DateTime pour le champ 'date' de l'objet Transaction
        $date = new \DateTime();
        $date->setDate($transactionData['date']['year'], $transactionData['date']['month'], $transactionData['date']['day']);

        // Mise à jour de l'objet $transaction avec les données fournies
        $transactionToUpdate->setDate($date);
        $transactionToUpdate->setName($transactionData['name']);
        $transactionToUpdate->setAmount(abs((float)$transactionData['amount']));

        // Supposons que vous avez une méthode pour récupérer un TransactionType et un Budget par leurs ID
        // Vous auriez besoin de charger les objets correspondants avant de les attribuer
        $type = $this->transactionTypeRepository->find($transactionData['type']); // Supposition de l'existence d'un repository
        $budgetCategory = $this->budgetRepository->find($transactionData['budgetCategory']); // Idem
        $month = $this->monthRepository->find($transactionData['month']); // Supposons que 'month' est l'ID du mois

        // Assurez-vous que ces entités sont bien chargées
        if ($type !== null) {
            $transactionToUpdate->setType($type);
        }

        if ($budgetCategory !== null) {
            $transactionToUpdate->setBudgetCategory($budgetCategory);
        }

        if ($month !== null) {
            $transactionToUpdate->setMonth($month);
        }

        // Supposition que vous voulez aussi gérer un champ booléen 'from_upload'
        $transactionToUpdate->setFromUpload(false);  // Exemple statique, adaptez selon la logique de votre application

        // Après mise à jour, sauvegardez l'objet avec Doctrine EntityManager si vous êtes dans un contexte Symfony
        $this->entityManager->persist($transactionToUpdate);
        $this->entityManager->flush();
    }
}
