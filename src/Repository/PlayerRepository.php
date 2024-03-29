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

  public function getLastRanking($id, $sport="Tennis", $dateMax=""){

    if ($sport=="Tennis") $cplmt="";
    else $cplmt=$sport;
    if ($dateMax=="") $cplmtDate="";
    else $cplmtDate='AND date<"'.$dateMax.'"';

    $em = $this->getEntityManager();
    $sql = '
        SELECT position, score, date 
        FROM RankingPos'.$cplmt.' RP, Ranking'.$cplmt.' R 
        WHERE R.id=RP.idRanking'.$cplmt.' AND RP.idPlayer=:idPlayer 
        '.$cplmtDate.'
        ORDER BY date DESC 
        LIMIT 0,1
        ';
    $stmt = $em->getConnection()->prepare($sql);
    $exec = $stmt->execute(['idPlayer' => $id]);
    $lastR = $exec->fetchAll();

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