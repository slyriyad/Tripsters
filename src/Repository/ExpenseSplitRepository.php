<?php

namespace App\Repository;

use App\Entity\ExpenseSplit;
use App\Entity\Trip;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExpenseSplit>
 */
class ExpenseSplitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpenseSplit::class);
    }

    public function findExpensesForTrip(Trip $trip): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.trip = :trip')
            ->setParameter('trip', $trip)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findExpensesForTripAndUser(Trip $trip, User $user): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.trip = :trip')
            ->andWhere('e.paidBy = :user OR :user MEMBER OF e.expenseSplits')
            ->setParameter('trip', $trip)
            ->setParameter('user', $user)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
//    /**
//     * @return ExpenseSplit[] Returns an array of ExpenseSplit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ExpenseSplit
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
