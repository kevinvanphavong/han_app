<?php

namespace App\Command;

use App\Entity\TransactionType;
use App\Repository\TransactionTypeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'create:transaction:type',
    description: 'Create transaction types',
)]
class CreateTransactionTypeCommand extends Command
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private TransactionTypeRepository $transactionTypeRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'types',
                InputArgument::REQUIRED,
                'Write the transaction types separated by comma (,) with no spaces between them. Example: Spent,Collected'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $transactionTypeNames = explode(',', $input->getArgument('types'));

        foreach ($transactionTypeNames as $transactionTypeNumber => $transactionTypeName) {
            $transactionType = new TransactionType();
            $transactionType->setName($transactionTypeName);
            $transactionType->setAssociatedNumber($transactionTypeNumber);
            $this->managerRegistry->getManager()->persist($transactionType);
        }
        $this->managerRegistry->getManager()->flush();
        $io->success(sprintf(
            'Transaction types (%s) created!',
            implode(', ', $transactionTypeNames)
        ));
        return Command::SUCCESS;
    }
}
