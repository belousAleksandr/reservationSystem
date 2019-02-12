<?php

namespace App\Repository;

use App\Entity\Cinema;
use App\Entity\Seans;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Seans|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seans|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seans[]    findAll()
 * @method Seans[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeansRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Seans::class);
    }

     /**
      * @param Cinema $cinema
      * @param \DateTime $dateTime
      * @return Seans[] Returns an array of Seans objects
      */
    public function findByCinemaAndDate(Cinema $cinema, \DateTime $dateTime): array
    {
        $endDate = clone $dateTime;
        $endDate->modify('+1 day');

        return $this->createQueryBuilder('s')
            ->leftJoin('s.hall', 'hall')
            ->andWhere('hall.cinema = :cinema')
            ->andWhere('s.datetime BETWEEN :startDate AND :endDate')
            ->setParameter('cinema', $cinema)
            ->setParameter('startDate', $dateTime)
            ->setParameter('endDate', $endDate)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Seans
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
