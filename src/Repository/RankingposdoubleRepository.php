<?php

namespace App\Repository;

use App\Entity\Rankingposdouble;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Persistence\ManagerRegistry;

class RankingposdoubleRepository extends ServiceEntityRepository {

  public function __construct(ManagerRegistry $registry)
  {
      parent::__construct($registry, Rankingposdouble::class);
  }

  public function getSelectedRankingpos($idRanking, $arrIdPlayers) { 

   
    $em = $this->getEntityManager();
    $sql = '
        SELECT idplayer, position, score, date 
        FROM RankingPosDouble RP, RankingDouble R 
        WHERE R.id=RP.idRanking
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