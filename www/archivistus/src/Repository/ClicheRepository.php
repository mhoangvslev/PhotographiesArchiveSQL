<?php

namespace App\Repository;

use App\Entity\Cliche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Cliche|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cliche|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cliche[]    findAll()
 * @method Cliche[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClicheRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cliche::class);
    }

    // /**
    //  * @return Cliche[] Returns an array of Cliche objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cliche
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
