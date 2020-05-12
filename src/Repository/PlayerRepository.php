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

  public function getLastRanking($id){
    $em = $this->getEntityManager();
    $sql = '
        SELECT position, score, date 
        FROM RankingPos RP, Ranking R 
        WHERE R.id=RP.idRanking AND RP.idPlayer=:idPlayer 
        ORDER BY date DESC 
        LIMIT 0,1
        ';
    $stmt = $em->getConnection()->prepare($sql);
    $stmt->execute(['idPlayer' => $id]);
    $lastR = $stmt->fetchAll();

    $rt=array();

    if (isset($lastR[0]["score"])) {
      $rt["score"]=$lastR[0]["score"];
      $rt["position"]=$lastR[0]["position"];
    }
    else {
      $rt["score"]="-";
      $rt["position"]="-";
    }

    return $rt;
  }
}