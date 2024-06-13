<?php

namespace App\Repository;

use App\Entity\Month;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Month>
 *
 * @method Month|null find($id, $lockMode = null, $lockVersion = null)
 * @method Month|null findOneBy(array $criteria, array $orderBy = null)
 * @method Month[]    findAll()
 * @method Month[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Month::class);
    }

    public function findMonthsSortedByUserAndCurrentDate($user): array
    {
        $currentMonth = new \DateTime();
        $months = $this->createQueryBuilder('m')
            ->where('m.user = :user')
            ->setParameter('user', $user)
            ->orderBy('m.date', 'DESC')
            ->getQuery()
            ->getResult();

        // Si le 1er mois de la liste est plus grand que le mois actuel
        // (ex: 2021-01 > 2020-12), alors on retourne la liste des mois avec le mois actuel en 1er choix
        if ($months[0]->getDate()->format('Y-m') > $currentMonth->format('Y-m')) {
            foreach($months as $key => $month) {
                if ($month->getDate()->format('Y-m') === $currentMonth->format('Y-m')) {
                    unset($months[$key]);
                    array_unshift($months, $month);
                }
            }
        }
        return $months;
    }

//    /**
//     * @return Month[] Returns an array of Month objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Month
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
