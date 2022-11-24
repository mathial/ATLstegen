<?php

namespace App\Repository;

use App\Entity\Rankingpospaddle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Persistence\ManagerRegistry;

class RankingpospaddleRepository extends ServiceEntityRepository {

  public function __construct(ManagerRegistry $registry)
  {
      parent::__construct($registry, Rankingpospaddle::class);
  }

  public function getSelectedRankingpos($idRanking, $arrIdPlayers) { 

   
    $em = $this->getEntityManager();
    $sql = '
        SELECT idplayer, position, score, date 
        FROM RankingPosPaddle RP, RankingPaddle R 
        WHERE R.id=RP.idRankingPaddle
        AND R.id=:idRanking 
        AND RP.idPlayer IN ('.implode(",", $arrIdPlayers).') 
        ORDER BY score DESC 
        ';


    $stmt = $em->getConnection()->prepare($sql);
    $stmt->execute(['idRanking' => $idRanking]);
    $RP = $stmt->fetchAll();

    return $RP;


  }

}