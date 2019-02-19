<?php

namespace App\Repository;

use App\Entity\DatePhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DatePhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method DatePhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method DatePhoto[]    findAll()
 * @method DatePhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatePhotoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DatePhoto::class);
    }

    // /**
    //  * @return DatePhoto[] Returns an array of DatePhoto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DatePhoto
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
