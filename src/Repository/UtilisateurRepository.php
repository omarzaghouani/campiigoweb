<?php
// src/Repository/UtilisateurRepository.php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface; // Corrected namespace
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
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

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Utilisateur) {
            throw new \InvalidArgumentException('Expected an instance of Utilisateur, got "'.get_class($user).'"');
        }
        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }


    // Ajoutez ici des méthodes personnalisées pour les opérations de base de données liées aux utilisateurs si nécessaire
}
