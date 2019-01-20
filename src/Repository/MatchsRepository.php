<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class MatchsRepository extends EntityRepository {

  public function getMatchsPerPage($page, $nbPerPage, $filter="") { 
    $query = $this->createQueryBuilder('m');

    if ($filter!="") {
      $query
      ->where(' m.nameshort LIKE :filter')
      ->orWhere('m.namelong LIKE :filter')
      ->orWhere('m.email LIKE :filter')
      ->setParameter('filter', "%".$filter."%");
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
}