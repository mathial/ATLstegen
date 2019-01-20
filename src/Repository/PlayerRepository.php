<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PlayerRepository extends EntityRepository {

  public function getPlayersPerPage($page, $nbPerPage, $filter="") { 
    $query = $this->createQueryBuilder('p');

    if ($filter!="") {
      $query
      ->where(' p.nameshort LIKE :filter')
      ->orWhere('p.namelong LIKE :filter')
      ->orWhere('p.email LIKE :filter')
      ->setParameter('filter', "%".$filter."%");
    }
    $query
    ->orderBy('p.id', 'DESC')
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