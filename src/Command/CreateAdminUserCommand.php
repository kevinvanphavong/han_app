<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'create:admin:user',
    description: 'Create the admin user',
)]
class CreateAdminUserCommand extends Command
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $userPasswordHasherInterface
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'User email')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'User password')
            ->addOption('username', null, InputOption::VALUE_REQUIRED, 'User username')
            ->addOption('role', null, InputOption::VALUE_REQUIRED, 'User role');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // sf-con create:admin:user --email="" --password="" --username="" --role=""
        $io = new SymfonyStyle($input, $output);

        $email = $input->getOption('email');
        $password = $input->getOption('password');
        $username = $input->getOption('username');
        $role = $input->getOption('role');

        $userExist = $this->userRepository->findOneBy(['email' => $email]);
        if ($userExist !== null && $userExist->getRoles() === ['ROLE_ADMIN']) {
            $io->error('You already have an admin user!');
            return Command::FAILURE;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles([$role]);
        $user->setUsername($username);
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, $password));
        $this->managerRegistry->getManager()->persist($user);
        $this->managerRegistry->getManager()->flush();
        $io->success("You just created a `$role` user for `$email`!");
        return Command::SUCCESS;
    }
}
