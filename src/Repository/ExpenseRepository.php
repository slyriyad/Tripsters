<?php

namespace App\Repository;

use App\Entity\Expense;
use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    public function getSumByCategory(Trip $trip)
{
    $qb = $this->getEntityManager()->createQueryBuilder();
    return $qb->select('ce.name as categoryName, ce.backgroundColor, ce.icon, COALESCE(SUM(e.amount), 0) as total')
        ->from('App\Entity\CategoryExpense', 'ce')
        ->leftJoin('App\Entity\Expense', 'e', 'WITH', 'e.categoryExpense = ce AND e.trip = :trip')
        ->setParameter('trip', $trip)
        ->groupBy('ce.id')
        ->getQuery()
        ->getResult();
}
}