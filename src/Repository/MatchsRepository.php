<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class MatchsRepository extends EntityRepository {

  public function getMatchsPerPage($page, $nbPerPage, $filter="") { 
    $query = $this->createQueryBuilder('m');

    if ($filter!="") {
      $query
      ->where(' m.conditions = :filter')
      ->orWhere('m.context = :filter')
      ->setParameter('filter', $filter);
    }
    $query
    ->orderBy('m.id', 'DESC')
    ->getQuery()
    ;

    if (is_numeric($nbPerPage)) {
      $query
      ->setFirstResult(($page-1) * $nbPerPage)
      ->setMaxResults($nbPerPage);
    }

    return new Paginator($query, true);
  }

  public function getMatchsPerPageByPlayer($page, $nbPerPage, $idplayer) { 
    $query = $this->createQueryBuilder('m');

    $query
    ->where(' m.idplayer1 = :idplayer')
    ->orWhere('m.idplayer2 = :idplayer')
    ->setParameter('idplayer', $idplayer)
    ->orderBy('m.id', 'DESC')
    ->getQuery()
    ;

    if (is_numeric($nbPerPage)) {
      $query
      ->setFirstResult(($page-1) * $nbPerPage)
      ->setMaxResults($nbPerPage);
    }

    return new Paginator($query, true);
  }

  public function findLastMatchPerPlayer($idplayer) { 
    $query = $this->createQueryBuilder('m')
    ->where(' m.idplayer1 = :idplayer')
    ->orWhere('m.idplayer2 = :idplayer')
    ->setParameter('idplayer', $idplayer)
    ->orderBy('m.date', 'DESC')
    ->getQuery()
    ->setMaxResults(1)->getOneOrNullResult()
    ;

    return $query;
  }
}