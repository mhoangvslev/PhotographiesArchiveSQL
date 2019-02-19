<?php

namespace App\Repository;

use App\Entity\IndexIconographique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IndexIconographique|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndexIconographique|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndexIconographique[]    findAll()
 * @method IndexIconographique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexIconographiqueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IndexIconographique::class);
    }

    // /**
    //  * @return IndexIconographique[] Returns an array of IndexIconographique objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IndexIconographique
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
