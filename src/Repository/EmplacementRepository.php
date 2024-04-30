<?php

namespace App\Repository;

use App\Entity\Emplacement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Emplacement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emplacement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emplacement[]    findAll()
 * @method Emplacement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmplacementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emplacement::class);
    }

    public function findBySearchTermAndSort($searchTerm, $sortBy)
    {
        $query = $this->createQueryBuilder('e')
            ->leftJoin('e.centre', 'c');

        if ($searchTerm) {
            $query->andWhere('c.nom_centre LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        if ($sortBy === 'disponibilite') {
            $query->orderBy('e.disponibilite', 'DESC'); // Sort by disponibilite in descending order
        }

        return $query->getQuery()->getResult();
    }
}