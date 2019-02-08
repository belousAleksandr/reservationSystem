<?php

namespace App\Repository;

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

    // /**
    //  * @return Seans[] Returns an array of Seans objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

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
