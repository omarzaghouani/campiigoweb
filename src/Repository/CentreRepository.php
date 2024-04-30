<?php

namespace App\Repository;

use App\Entity\Centre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Centre>
 *
 * @method Centre|null find($id_centre, $lockMode = null, $lockVersion = null)
 * @method Centre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Centre[]    findAll()
 * @method Centre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CentreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Centre::class);
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
public function rechercheParVille($ville)
{
    return $this->createQueryBuilder('e')
        ->andWhere('e.ville = :ville')
        ->setParameter('ville', $ville)
        ->getQuery()
        ->getResult();
}
public function getStatisticsByCity(): array
{
    $qb = $this->createQueryBuilder('c')
        ->select('c.ville, COUNT(c) as centreCount')
        ->groupBy('c.ville')
        ->getQuery();

    return $qb->getResult();
}

}
