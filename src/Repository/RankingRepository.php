<?php

namespace App\Repository;

use App\Entity\Ranking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Persistence\ManagerRegistry;

class RankingRepository extends ServiceEntityRepository {

  public function __construct(ManagerRegistry $registry)
  {
      parent::__construct($registry, Ranking::class);
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

  // get a sorted list of players with their rating index calculated this way ;
  // average of the unique last rating in the rankings over the past X days
  public function getRatingIndex($nbDays) {


    $em = $this->getEntityManager();
    $sql = '
      SELECT P.id, P.nameShort, AVG(DISTINCT RP.score) as avg_score 
      FROM `Ranking` R, RankingPos RP, Player P 
      WHERE R.id=RP.idRanking AND RP.idPlayer=P.id 
      and R.date>=DATE_SUB(now(), INTERVAL '.$nbDays.' DAY)
      GROUP BY P.id
      ORDER BY avg_score DESC
        ';


    $stmt = $em->getConnection()->prepare($sql);
    $stmt->execute();
    $rt = $stmt->fetchAll();
   echo count($rt)." ratingIndex;";

    return $rt;



  }
}