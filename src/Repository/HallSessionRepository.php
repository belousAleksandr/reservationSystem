<?php

namespace App\Repository;

use App\Entity\Cinema;
use App\Entity\HallSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HallSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method HallSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method HallSession[]    findAll()
 * @method HallSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HallSessionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HallSession::class);
    }

     /**
      * @param Cinema $cinema
      * @param \DateTime $dateTime
      * @return HallSession[] Returns an array of HallSessions objects
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
    public function findOneBySomeField($value): ?HallSession
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
