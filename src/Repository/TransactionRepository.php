<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function findWithFilters($filters)
    {
        $queryBuilder = $this->createQueryBuilder('t');

        foreach($filters as $name => $value) {
            switch ($name) {
                case 'name':
                    $queryBuilder->andWhere("t.$name LIKE :$name");
                    $queryBuilder->setParameter($name, '%'.$value.'%');
                    break;
                case 'limit':
                    $queryBuilder->setMaxResults($value);
                    break;
                default:
                    $queryBuilder->andWhere("t.$name = :$name");
                    $queryBuilder->setParameter($name, $value);
                    break;
            }
        }
        $queryBuilder->orderBy('t.date', 'DESC');
        $queryBuilder->addOrderBy('t.id', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function findByUserSortedByDateAndIdDesc($user, $limit = null)
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->andWhere('t.from_upload = :from_upload')
            ->setParameter('from_upload', false)
            ->orderBy('t.date', 'DESC')
            ->addOrderBy('t.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Transaction[] Returns an array of Transaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Transaction
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
