<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class MatchspaddleRepository extends EntityRepository {

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
    ->orWhere('m.idplayer3 = :idplayer')
    ->orWhere('m.idplayer4 = :idplayer')
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

  // get all the matches without any linked Idranking before a specific date (date of the last ranking should be provided)
  public function getMatchesWithoutRankings($date) {
    $query = $this->createQueryBuilder('m')
    ->where(' m.idrankingpaddle is null')
    ->andWhere('m.date < :datePreviousRanking')
    ->orderBy('m.date', 'DESC')
    ->setParameter('datePreviousRanking', $date)
    ->getQuery()
    ->getResult()
    ;

    return $query;

  }



}