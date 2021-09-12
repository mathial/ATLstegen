<?php

namespace App\Repository;

use App\Entity\Rankingdouble;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Persistence\ManagerRegistry;

class RankingdoubleRepository extends ServiceEntityRepository {

  public function __construct(ManagerRegistry $registry)
  {
      parent::__construct($registry, Rankingdouble::class);
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