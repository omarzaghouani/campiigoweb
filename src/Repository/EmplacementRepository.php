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

    public function searchAndSort($searchQuery, $sortBy)
    {
        $qb = $this->createQueryBuilder('e');

        // Apply search filter if search query is provided
        if ($searchQuery) {
            $qb->andWhere('e.id_centre = :searchQuery')
               ->setParameter('searchQuery', $searchQuery);
        }

        // Apply sorting
        if ($sortBy === 'name') {
            $qb->orderBy('e.name', 'ASC');
        }
        // Add more sorting options if needed

        return $qb->getQuery()->getResult();
    }}
