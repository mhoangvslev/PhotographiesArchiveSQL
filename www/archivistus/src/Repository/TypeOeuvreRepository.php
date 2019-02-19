<?php

namespace App\Repository;

use App\Entity\TypeOeuvre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeOeuvre|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeOeuvre|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeOeuvre[]    findAll()
 * @method TypeOeuvre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeOeuvreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeOeuvre::class);
    }

    // /**
    //  * @return TypeOeuvre[] Returns an array of TypeOeuvre objects
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
    public function findOneBySomeField($value): ?TypeOeuvre
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
