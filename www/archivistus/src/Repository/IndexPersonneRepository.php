<?php

namespace App\Repository;

use App\Entity\IndexPersonne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IndexPersonne|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndexPersonne|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndexPersonne[]    findAll()
 * @method IndexPersonne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexPersonneRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IndexPersonne::class);
    }

    // /**
    //  * @return IndexPersonne[] Returns an array of IndexPersonne objects
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
    public function findOneBySomeField($value): ?IndexPersonne
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
