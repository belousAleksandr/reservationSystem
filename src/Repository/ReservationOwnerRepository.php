<?php

namespace App\Repository;

use App\Entity\ReservationOwner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReservationOwner|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationOwner|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationOwner[]    findAll()
 * @method ReservationOwner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationOwnerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReservationOwner::class);
    }

    // /**
    //  * @return ReservationOwner[] Returns an array of ReservationOwner objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReservationOwner
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
