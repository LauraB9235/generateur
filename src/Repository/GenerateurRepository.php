<?php

namespace App\Repository;

use App\Entity\Generateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Generateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Generateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Generateur[]    findAll()
 * @method Generateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenerateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Generateur::class);

    }





    // /**
    //  * @return Generateur[] Returns an array of Generateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Generateur
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
