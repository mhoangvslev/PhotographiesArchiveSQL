<?php

namespace App\Repository;

use App\Entity\TableForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TableForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableForm[]    findAll()
 * @method TableForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableFormRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TableForm::class);
    }

    // /**
    //  * @return TableForm[] Returns an array of TableForm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TableForm
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
