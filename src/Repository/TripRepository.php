<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Entity\TripUser;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Trip>
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

//    /**
//     * @return Trip[] Returns an array of Trip objects
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

//    public function findOneBySomeField($value): ?Trip
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

// Dans src/Repository/TripRepository.php
public function findTripsByUser(User $user)
{
    return $this->createQueryBuilder('t')
        ->innerJoin('t.tripUsers', 'tu')  // Supposons que 'tripUsers' est le nom de la relation dans l'entité Trip
        ->innerJoin('tu.user', 'u')       // Jointure avec l'entité User via TripUser
        ->andWhere('u = :user')           // Utilisation directe de l'objet User
        ->setParameter('user', $user)
        ->orderBy('t.startDate', 'DESC')  // J'ai décommenté cette ligne car c'est généralement utile
        ->getQuery()
        ->getResult();
}

public function findCreatorByTrip(Trip $trip): ?TripUser
{
    return $this->createQueryBuilder('t')
        ->select('tu')
        ->join('t.tripUsers', 'tu')
        ->where('tu.trip = :trip')
        ->andWhere('tu.role = :role') // Le rôle de créateur
        ->setParameter('trip', $trip)
        ->setParameter('role', 'créateur')
        ->getQuery()
        ->getOneOrNullResult();
}
}
