<?php

namespace App\Repository;

use App\Entity\Seat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Seat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seat[]    findAll()
 * @method Seat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Seat::class);
    }

    /**
     * Returns an array of Seat objects
     *
     * @param array $seats
     * @return array
     */
    public function findReservedSeats(array $seats): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id IN (:seats)')
            ->andWhere('s.reservation IS NOT NULL')
            ->setParameter('seats', $seats)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Seat
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
