<?php

namespace App\Repository;

use App\Entity\Rankingpos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Persistence\ManagerRegistry;

class RankingposRepository extends ServiceEntityRepository {

  public function __construct(ManagerRegistry $registry)
  {
      parent::__construct($registry, Rankingpos::class);
  }

  public function getSelectedRankingpos($idRanking, $arrIdPlayers) { 

   
    $em = $this->getEntityManager();
    $sql = '
        SELECT idplayer, position, score, date 
        FROM RankingPos RP, Ranking R 
        WHERE R.id=RP.idRanking
        AND R.id=:idRanking 
        AND RP.idPlayer IN ('.implode(",", $arrIdPlayers).') 
        ORDER BY score DESC 
        ';


    $stmt = $em->getConnection()->prepare($sql);
    $exec = $stmt->execute(['idRanking' => $idRanking]);
    $RP = $exec->fetchAll();

    return $RP;


  }

}