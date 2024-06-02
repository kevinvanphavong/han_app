<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileTransactionsController extends AbstractController
{
    #[Route('/upload-your-transactions', name: 'upload_file_transactions')]
    public function index(): Response
    {
        return $this->render('file_transactions/index.html.twig', [
            'controller_name' => 'FileTransactionsController',
        ]);
    }
}
