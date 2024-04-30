<?php

namespace App\Repository;

use App\Entity\Equipement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Equipement>
 *
 * @method Equipement|null find($idEquipement, $lockMode = null, $lockVersion = null)
 * @method Equipement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipement[]    findAll()
 * @method Equipement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipement::class);
    }

//    /**
//     * @return Centre[] Returns an array of Centre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Centre
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
