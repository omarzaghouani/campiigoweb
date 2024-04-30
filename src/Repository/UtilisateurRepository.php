<?php
// src/Repository/UtilisateurRepository.php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }
    public function findBySearchTerm($searchTerm)
{
    return $this->createQueryBuilder('c')
        ->andWhere('c.nom LIKE :searchTerm')
        ->setParameter('searchTerm', '%'.$searchTerm.'%')
        ->getQuery()
        ->getResult();
}

public function getStatisticsByRole(): array
{
    $qb = $this->createQueryBuilder('c')
        ->select('c.role, COUNT(c) as roleCount')
        ->groupBy('c.role')
        ->getQuery();

    return $qb->getResult();
}


public function findAllSortedByAttribute($attribute, $sortOrder = 'ASC')
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.' . $attribute, $sortOrder)
            ->getQuery()
            ->getResult();
    }


    // Ajoutez ici des méthodes personnalisées pour les opérations de base de données liées aux utilisateurs si nécessaire
}
