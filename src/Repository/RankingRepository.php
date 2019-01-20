<?php

namespace App\Repository;

use App\Entity\Ranking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RankingRepository extends ServiceEntityRepository {

  public function __construct(RegistryInterface $registry)
  {
      parent::__construct($registry, Ranking::class);
  }

  public function getLastRanking() { 
    $query = $this->createQueryBuilder('r')
    ->orderBy('r.date', 'DESC')
    ->getQuery()
    // ->getResult()
    ->setMaxResults(1)->getOneOrNullResult()
    ;

    return $query;
  }

  // get the ranking just before the given date
  public function getRankingBefore($date) { 
    $query = $this->createQueryBuilder('r')
    ->where('r.date < :date')
    ->orderBy('r.date', 'DESC')
    ->setParameter('date', $date)
    ->getQuery()
    // ->getResult()
    ->setMaxResults(1)->getOneOrNullResult()
    ;

    return $query;
  }
}