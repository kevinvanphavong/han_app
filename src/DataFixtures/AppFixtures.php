<?php

namespace App\DataFixtures;

use App\Entity\Budget;
use App\Entity\Month;
use App\Entity\Transaction;
use App\Entity\TransactionType;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $this->createUserFixtures($manager, $this->userPasswordHasher);
        $this->createTransactionTypeFixtures($manager);
        $this->createMonthFixtures($manager);
        $this->createBudgetFixtures($manager);
        $this->createTransactionsFixtures($manager);

        $manager->flush();
    }

    public function createUserFixtures(ObjectManager $manager, UserPasswordHasherInterface $userPasswordHasher): void
    {
        $users = [
            'tsehao@hotmail.fr' => 'tsehao',
            'han@hotmail.fr' => 'hanhan',
        ];


        $index = 0;
        foreach ($users as $email => $password) {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($userPasswordHasher->hashPassword($user, $password));
            $email === 'han@hotmail.fr' ? $user->setRoles(['ROLE_ADMIN']) : $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $this->setReference('user_' . $index, $user);
        }
    }

    public function createMonthFixtures(ObjectManager $manager): void
    {
        $months = [
            '10/01/2023',
            '11/01/2023',
            '12/01/2023',
        ];

        foreach ($months as $number) {
            $date = new \DateTime($number);
            $month = new Month();
            $month->setDate($date);
            $month->setUser($this->getReference('user_0'));
            $this->setReference($number, $month);
            $manager->persist($month);
        }
    }

    public function createTransactionTypeFixtures(ObjectManager $manager): void
    {
        $types = [
            'Spent' => 0,
            'Collected' => 1,
        ];

        foreach ($types as $name => $number) {
            $type = new TransactionType();
            $type->setName($name);
            $type->setAssociatedNumber($number);
            $this->setReference($name, $type);
            $manager->persist($type);
        }
    }

    public function createBudgetFixtures(ObjectManager $manager): void
    {
        $budgets = [
            'Logement' => 460,
            'Transport' => 100,
            'Nourriture' => 150,
            'Voiture' => 100,
            'Plaisirs' => 100,
            'Voyage' => 50,
            'Sport' => 35,
            'Vêtement' => 50,
            'Santé' => 50,
        ];

        foreach ($budgets as $name => $amount) {
            $budget = new Budget();
            $budget->setName($name);
            $budget->setAmount($amount);
            $budget->setUser($this->getReference('user_0'));
            $this->setReference($name, $budget);
            $manager->persist($budget);
        }
    }

    public function createTransactionsFixtures(ObjectManager $manager): void
    {
        $budgets = [
            'Logement',
            'Transport',
            'Nourriture',
            'Voiture',
            'Plaisirs',
            'Voyage',
            'Sport',
            'Vêtement',
            'Santé'
        ];
        $months = ['October', 'November', 'December'];
        $dates = ['10/01/2023', '11/01/2023', '12/01/2023'];
        $transactions = [
            'Loyer' => 460,
            'Salaire' => 1500,
            'Remboursement' => 100,
            'Courses' => 50,
            'Essence' => 50,
            'Restaurant' => 50,
            'Vêtements' => 50,
            'Cinéma' => 50,
            'Voyage' => 50,
            'Médecin' => 50,
            'Salle de sport' => 35,
            'Cadeau' => 35,
            'Café' => 35,
            'Impôts' => 35,
            'Internet' => 35,
            'Téléphone' => 35,
            'Assurance' => 35,
            'Cigarettes' => 35,
            'Alcool' => 35,
            'Cadeau' => 35,
        ];

        $index = 0;
        foreach ($months as $month) {
            foreach ($transactions as $name => $amount) {
                $budget = $this->getReference($budgets[$index]);
                $month = $this->getReference($dates[$index]);
                $transactionType = $this->getReference('Spent');
                $transaction = new Transaction();
                $transaction->setName($name);
                $transaction->setAmount($amount);
                $transaction->setBudgetCategory($budget);
                $transaction->setDate(new \DateTime($dates[$index]));
                $transaction->setMonth($month);
                $transaction->setType($transactionType);
                $transaction->setUser($this->getReference('user_0'));
                $manager->persist($transaction);
            }
            $index++;
        }
    }
}
