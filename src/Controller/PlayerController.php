<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use \Symfony\Component\Form\Extension\Core\Type\FormType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use \Symfony\Component\Form\Extension\Core\Type\EmailType;
use \Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Component\Form\Extension\Core\Type\DateType;
use \Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

use App\Entity\EloRatingSystem;
use App\Entity\EloCompetitor;
use App\Entity\Player;
use App\Entity\Country;

class PlayerController extends Controller
{
  /**
   * @Route("/players/view/{id}", 
   * name="player_view", 
   * requirements={
   *   "id"="\d+", 
   * })
   */
  public function view($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $player = $em->getRepository('App\Entity\Player')->findOneBy(array('id'=>$id));
    $lastRTS = $em->getRepository('App\Entity\Player')->getLastRanking($id);
    $lastRTD = $em->getRepository('App\Entity\Player')->getLastRanking($id, "Double");
    $lastRP = $em->getRepository('App\Entity\Player')->getLastRanking($id, "Paddle");


    $arrHistoryM=array();
    $arrConditions=["hard indoor", "clay outdoor"];

    for ($iY=2017; $iY<=date("Y"); $iY++) {
      foreach ($arrConditions as $cond) {
        $arrHistoryM[$iY][$cond]["nbM"]=0;
        $arrHistoryM[$iY][$cond]["nbW"]=0;
        $arrHistoryM[$iY][$cond]["nbD"]=0;
        $arrHistoryM[$iY][$cond]["nbT"]=0;
      }
    }
    foreach ($arrConditions as $cond) {
      $arrHistoryM["tot"][$cond]["nbM"]=0;
      $arrHistoryM["tot"][$cond]["nbW"]=0;
      $arrHistoryM["tot"][$cond]["nbD"]=0;
      $arrHistoryM["tot"][$cond]["nbT"]=0;
    }

    $arrStatsOpponents=array();
    $arrStatsMatchs=array();
    $arrStatsOpponentsDetails=array();

    $arrStatsOpponentsPaddle=array();
    $arrStatsMatchsPaddle=array();
    $arrStatsOpponentsDetailsPaddle=array();

    if ($player) {
      $sql = '
        SELECT idplayer1, idplayer2, tie , conditions, YEAR(date) AS annee
        FROM Matchs, Player P1, Player P2
        WHERE idplayer1=P1.id
        AND idplayer2=P2.id
        AND (idplayer1 = :idPlayer OR idplayer2 = :idPlayer)
        ORDER BY idplayer1, idplayer2
        ';
      $stmt = $em->getConnection()->prepare($sql);
      $exec = $stmt->execute(['idPlayer' => $id]);
      $opponents = $exec->fetchAll();

      $nbMTot=count($opponents);

      foreach ($opponents as $opp) {
        if ($opp["idplayer1"]==$id) {
          $oppId=$opp["idplayer2"];
        }
        else $oppId=$opp["idplayer1"];

        if (!isset($arrStatsMatchs[$oppId])) {
          $arrStatsMatchs[$oppId]["nbM"]=0;
          $arrStatsMatchs[$oppId]["nbW"]=0;
          $arrStatsMatchs[$oppId]["nbD"]=0;
          $arrStatsMatchs[$oppId]["nbT"]=0;
          $arrStatsMatchs[$oppId]["sold"]=0;
        }

        if ($opp["conditions"]=="hard outdoor") $opp["conditions"]="hard indoor";
        $arrStatsMatchs[$oppId]["nbM"]++;
        $arrHistoryM[$opp["annee"]][$opp["conditions"]]["nbM"]++;
        $arrHistoryM["tot"][$opp["conditions"]]["nbM"]++;

        if ($opp["tie"]==1) {
          $arrStatsMatchs[$oppId]["nbT"]++;
          $arrHistoryM[$opp["annee"]][$opp["conditions"]]["nbT"]++;
          $arrHistoryM["tot"][$opp["conditions"]]["nbT"]++;
        }
        else {
          if ($opp["idplayer1"]==$id) {
            $arrStatsMatchs[$oppId]["nbW"]++;
            $arrStatsMatchs[$oppId]["sold"]++;

            $arrHistoryM[$opp["annee"]][$opp["conditions"]]["nbW"]++;
            $arrHistoryM["tot"][$opp["conditions"]]["nbW"]++;
          }
          else {
            $arrStatsMatchs[$oppId]["nbD"]++;
            $arrStatsMatchs[$oppId]["sold"]--;

            $arrHistoryM[$opp["annee"]][$opp["conditions"]]["nbD"]++;
            $arrHistoryM["tot"][$opp["conditions"]]["nbD"]++;
          }
        }

      }
      
      // sorting array
      // if (count($arrStatsOpponents)>0) {
      //   arsort($arrStatsOpponents);
      // }

      foreach ($arrStatsMatchs as $idP => $data) {
        $dataPlayer = $em->getRepository('App\Entity\Player')->findOneBy(array('id'=>$idP));

        $arrStatsOpponentsDetails[$idP]["name"]=$dataPlayer->getNameShort();
        $arrStatsOpponentsDetails[$idP]["id"]=$idP;
        $arrStatsOpponentsDetails[$idP]["nbM"]=$data["nbM"];
        $arrStatsOpponentsDetails[$idP]["nbW"]=$data["nbW"];
        $arrStatsOpponentsDetails[$idP]["nbD"]=$data["nbD"];
        $arrStatsOpponentsDetails[$idP]["nbT"]=$data["nbT"];
        $arrStatsOpponentsDetails[$idP]["sold"]=$data["sold"];

      }

      /*
      // PADDLE
      $sql = '
        SELECT idplayer1, idplayer2, tie FROM MatchsPaddle, Player P1, Player P2, Player P3, Player P4
        WHERE idplayer1=P1.id
        AND idplayer2=P2.id
        AND idplayer3=P3.id
        AND idplayer4=P4.id
        AND (idplayer1 = :idPlayer OR idplayer2 = :idPlayer OR idplayer3 = :idPlayer OR idplayer4 = :idPlayer)
        ORDER BY idplayer1, idplayer2, idPlayer3, idPlayer4
        ';
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute(['idPlayer' => $id]);
      $opponents = $exec->fetchAll();

      $nbMTotPaddle=count($opponents);

      foreach ($opponents as $opp) {
        if ($opp["idplayer1"]==$id) {
          $oppId=$opp["idplayer2"];
        }
        else $oppId=$opp["idplayer1"];

        if (!isset($arrStatsMatchsPaddle[$oppId])) {
          $arrStatsMatchsPaddle[$oppId]["nbM"]=0;
          $arrStatsMatchsPaddle[$oppId]["nbW"]=0;
          $arrStatsMatchsPaddle[$oppId]["nbD"]=0;
          $arrStatsMatchsPaddle[$oppId]["nbT"]=0;
          $arrStatsMatchsPaddle[$oppId]["sold"]=0;
        }

        $arrStatsMatchsPaddle[$oppId]["nbM"]++;

        if ($opp["tie"]==1) $arrStatsMatchsPaddle[$oppId]["nbT"]++;
        else {
          if ($opp["idplayer1"]==$id) {
            $arrStatsMatchsPaddle[$oppId]["nbW"]++;
            $arrStatsMatchsPaddle[$oppId]["sold"]++;
          }
          else {
            $arrStatsMatchsPaddle[$oppId]["nbD"]++;
            $arrStatsMatchsPaddle[$oppId]["sold"]--;
          }
        }

      }

      foreach ($arrStatsMatchs as $idP => $data) {
        $dataPlayer = $em->getRepository('App\Entity\Player')->findOneBy(array('id'=>$idP));

        $arrStatsOpponentsDetailsPaddle[$idP]["name"]=$dataPlayer->getNameLong();
        $arrStatsOpponentsDetailsPaddle[$idP]["id"]=$idP;
        $arrStatsOpponentsDetailsPaddle[$idP]["nbM"]=$data["nbM"];
        $arrStatsOpponentsDetailsPaddle[$idP]["nbW"]=$data["nbW"];
        $arrStatsOpponentsDetailsPaddle[$idP]["nbD"]=$data["nbD"];
        $arrStatsOpponentsDetailsPaddle[$idP]["nbT"]=$data["nbT"];
        $arrStatsOpponentsDetailsPaddle[$idP]["sold"]=$data["sold"];

      }

      */
    }
    else {

      $request->getSession()->getFlashBag()->add('error', 'Error selecting rankings ('.$id.')');
    }
    
    return $this->render('site/player_view.html.twig', [
      'controller_name' => 'PlayerController',
      'player' => $player,
      'lastRTS' => $lastRTS,
      'lastRTD' => $lastRTD,
      'lastRP' => $lastRP,
      'arrStatsOpponents' => $arrStatsOpponentsDetails,
      'nbMTot' => $nbMTot,
      'arrConditions' => $arrConditions,
      'arrHistoryM' => $arrHistoryM
      /*'arrStatsOpponentsPaddle' => $arrStatsOpponentsDetailsPaddle,
      'nbMTotPaddle' => $nbMTotPaddle,*/
    ]);
  }

  /**
   * @Route("/players/matches/{id}/{page}", 
   * name="player_view_matches", 
   * requirements={
   *   "id"="\d+", 
   *   "page"="\d+", 
   * })
   */
  public function viewMatches($id, $page, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $player = $em->getRepository('App\Entity\Player')->findOneBy(array('id'=>$id));
    $lastR = $em->getRepository('App\Entity\Player')->getLastRanking($id);

    $where="WHERE (m.idplayer1= ".$id." OR m.idplayer2= ".$id.")";
    $maxpage=5000;
    $listTotMatchs = $em->getRepository('App\Entity\Matchs')->getMatchsPerPageByPlayer($page, $maxpage, $id);

    $dql   = "SELECT m FROM App:Matchs m ".$where." ORDER BY m.date DESC";
    $query = $em->createQuery($dql);

    $paginator  = $this->get('knp_paginator');

    if (is_numeric($maxpage)) {
      $limitpage=$maxpage;
      $nbPages = ceil(count($listTotMatchs)/$maxpage);
    }
    else {
      $limitpage=count($listTotMatchs);
      $nbPages=1;
    }

    if ($page < 1 || $page > $nbPages) {
      $page = 1;
      // throw $this->createNotFoundException("Page ".$page." doesn't exist.");
    }

    $listMatchs = $paginator->paginate(
      $query, 
      $request->query->getInt('page', $page),
      $limitpage
    );


    /* POINTS EVOL PER MATCH */

    // for each match, we calculate the points evolution
    $sql_m   = 'SELECT m.id, m.date, m.tie, p1.id AS p1id, p2.id AS p2id, p1.initialRatingTennis AS p1IR, p2.initialRatingTennis AS p2IR 
                  FROM Matchs m, Player p1, Player p2
                  '.$where." 
                  AND p1.id=m.idplayer1
                  AND p2.id=m.idplayer2
                  ORDER BY m.date DESC";
    $stmt = $em->getConnection()->prepare($sql_m);
    $exec = $stmt->execute();
    $matches = $exec->fetchAll();

    $arrMEvol=array();

    foreach ($matches as $mat) {

      $rankId="";

      // get the closest ranking
      $sql_rank = 'SELECT id FROM Ranking WHERE date<="'.$mat["date"].'" ORDER BY date DESC LIMIT 0,1';
      $stmt = $em->getConnection()->prepare($sql_rank);
      $exec = $stmt->execute();
      $rank = $exec->fetchAll();
      if (isset($rank[0])) $rankId=$rank[0]["id"];
      
      $rating_player1=$mat["p1IR"];
      $rating_player2=$mat["p2IR"];
      $arrMEvol[$mat["id"]]=0;

      if ($rankId!="") {
        $sql_rank = 'SELECT score FROM RankingPos WHERE idRanking="'.$rankId.'" AND idPlayer='.$mat["p1id"];
        $stmt = $em->getConnection()->prepare($sql_rank);
        $exec = $stmt->execute();
        $rank = $exec->fetchAll();
        if (isset($rank[0])) $rating_player1=$rank[0]["score"];

        $sql_rank = 'SELECT score FROM RankingPos WHERE idRanking="'.$rankId.'" AND idPlayer='.$mat["p2id"];
        $stmt = $em->getConnection()->prepare($sql_rank);
        $exec = $stmt->execute();
        $rank = $exec->fetchAll();
        if (isset($rank[0])) $rating_player2=$rank[0]["score"];

      }

      if ($mat["tie"]==0) $result=1;
      else $result=0;

      if ($id==$mat["p1id"]) $idPFin=1;
      else $idPFin=2;

      if (isset($rating_player1) && is_numeric($rating_player1) && isset($rating_player2) && is_numeric($rating_player2)) {

        $competitors = array(
          array('id' => 1, 'name' => "Player 1", 'skill' => 100, 'rating' => $rating_player1, 'active' => 1),
          array('id' => 2, 'name' => "Player 2", 'skill' => 100, 'rating' => $rating_player2, 'active' => 1),
        );
        //  initialize the ranking system and add the competitors
        $elo = new EloRatingSystem(100, 50);
        foreach ($competitors as $competitor) {
          $elo->addCompetitor(new EloCompetitor($competitor['id'], $competitor['name'], $competitor['rating']));
        }

        if ($result==1) {
          $elo->addResult(1,2);
          $match = "Player 1 defeats Player 2";
          $result="player1";
        }
        /*elseif ($result==2) {
          $elo->addResult(2,1);
          $match = "Player 2 defeats Player 1";
          $result="player2";
        }*/
        else {
          $elo->addResult(1,2, true);
          $match = "TIE Player 1 - Player 2";
          $result="draw";
        }

        $elo->updateRatings();

        $tabRank = $elo->getRankings();

        foreach ($tabRank as $idP => $val) {

          $exp=explode("#", $idP);
          if ($exp[0]==1) {
            $evol=$val-$rating_player1;
            if ($evol>0) $arrRt[1]="+".number_format($evol, 1);
            else $arrRt[1]=number_format($evol, 1);
            if ($exp[0]==$idPFin) {$arrMEvol[$mat["id"]]=$arrRt[1]; }
          }
          elseif ($exp[0]==2) {
            $evol=$val-$rating_player2;
            if ($evol>0) $arrRt[2]="+".number_format($evol, 1);
            else $arrRt[2]=number_format($evol, 1);
            if ($exp[0]==$idPFin) {$arrMEvol[$mat["id"]]=$arrRt[2]; }
          }
         

             
        }
      
      }

    }
    /* END POINTS EVOL PER MATCH */

    return $this->render('site/player_view_matches.html.twig', array("listMatchs" => $listMatchs,
      'player' => $player,
      'lastR' => $lastR,
      'maxpage' => $maxpage,
      'nbPages' => $nbPages,
      'page' => $page,
      'arrMEvol' => $arrMEvol
    ));
    
  }



  /**
   * @Route("/players/matchespaddle/{id}/{page}", 
   * name="player_view_matches_padel", 
   * requirements={
   *   "id"="\d+", 
   *   "page"="\d+", 
   * })
   */
  public function viewMatchesPaddle($id, $page, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $player = $em->getRepository('App\Entity\Player')->findOneBy(array('id'=>$id));
    $lastR = $em->getRepository('App\Entity\Player')->getLastRanking($id, "Paddle");

    $where="WHERE (m.idplayer1= ".$id." OR m.idplayer2= ".$id." OR m.idplayer3= ".$id." OR m.idplayer4= ".$id.")";
    $maxpage=5000;
    $listTotMatchs = $em->getRepository('App\Entity\Matchspaddle')->getMatchsPerPageByPlayer($page, $maxpage, $id);

    $dql   = "SELECT m FROM App:Matchspaddle m ".$where." ORDER BY m.date DESC";
    $query = $em->createQuery($dql);

    $paginator  = $this->get('knp_paginator');

    if (is_numeric($maxpage)) {
      $limitpage=$maxpage;
      $nbPages = ceil(count($listTotMatchs)/$maxpage);
    }
    else {
      $limitpage=count($listTotMatchs);
      $nbPages=1;
    }

    if ($page < 1 || $page > $nbPages) {
      $page = 1;
      // throw $this->createNotFoundException("Page ".$page." doesn't exist.");
    }

    $listMatchs = $paginator->paginate(
      $query, 
      $request->query->getInt('page', $page),
      $limitpage
    );


    /* POINTS EVOL PER MATCH */

    // for each match, we calculate the points evolution
    $sql_m   = 'SELECT m.id, m.date, m.tie, p1.id AS p1id, p2.id AS p2id, p1.initialRatingPaddle AS p1IR, p2.initialRatingPaddle AS p2IR, p3.id AS p3id, p4.id AS p4id, p3.initialRatingPaddle AS p3IR, p4.initialRatingPaddle AS p4IR 
                  FROM MatchsPaddle m, Player p1, Player p2, Player p3, Player p4
                  '.$where." 
                  AND p1.id=m.idplayer1
                  AND p2.id=m.idplayer2
                  AND p3.id=m.idplayer3
                  AND p4.id=m.idplayer4
                  ORDER BY m.date DESC";
    $stmt = $em->getConnection()->prepare($sql_m);
    $exec = $stmt->execute();
    $matches = $exec->fetchAll();

    $arrMEvol=array();

    foreach ($matches as $mat) {

      $rankId="";

      // get the closest ranking
      $sql_rank = 'SELECT id FROM RankingPaddle WHERE date<"'.$mat["date"].'" ORDER BY date DESC LIMIT 0,1';
      $stmt = $em->getConnection()->prepare($sql_rank);
      $exec = $stmt->execute();
      $rank = $exec->fetchAll();
      if (isset($rank[0])) $rankId=$rank[0]["id"];
      
      $rating_player1=$mat["p1IR"];
      $rating_player2=$mat["p2IR"];
      $rating_player3=$mat["p3IR"];
      $rating_player4=$mat["p4IR"];

      $arrMEvol[$mat["id"]]=0;

      if ($rankId!="") {
        $sql_rank = 'SELECT score FROM RankingPosPaddle WHERE idRankingPaddle="'.$rankId.'" AND idPlayer='.$mat["p1id"];
        $stmt = $em->getConnection()->prepare($sql_rank);
        $exec = $stmt->execute();
        $rank = $exec->fetchAll();
        if (isset($rank[0])) $rating_player1=$rank[0]["score"];

        $sql_rank = 'SELECT score FROM RankingPosPaddle WHERE idRankingPaddle="'.$rankId.'" AND idPlayer='.$mat["p2id"];
        $stmt = $em->getConnection()->prepare($sql_rank);
        $exec = $stmt->execute();
        $rank = $exec->fetchAll();
        if (isset($rank[0])) $rating_player2=$rank[0]["score"];

        $sql_rank = 'SELECT score FROM RankingPosPaddle WHERE idRankingPaddle="'.$rankId.'" AND idPlayer='.$mat["p3id"];
        $stmt = $em->getConnection()->prepare($sql_rank);
        $exec = $stmt->execute();
        $rank = $exec->fetchAll();
        if (isset($rank[0])) $rating_player3=$rank[0]["score"];

        $sql_rank = 'SELECT score FROM RankingPosPaddle WHERE idRankingPaddle="'.$rankId.'" AND idPlayer='.$mat["p4id"];
        $stmt = $em->getConnection()->prepare($sql_rank);
        $exec = $stmt->execute();
        $rank = $exec->fetchAll();
        if (isset($rank[0])) $rating_player4=$rank[0]["score"];

      }

      if ($mat["tie"]==0) $result=1;
      else $result=0;

      if ($id==$mat["p1id"]) $idPFin=1; // team A
      elseif ($id==$mat["p2id"]) $idPFin=2; // team A 
      elseif ($id==$mat["p3id"]) $idPFin=3; // team B
      elseif ($id==$mat["p4id"]) $idPFin=4; // team B

      if (isset($rating_player1) && is_numeric($rating_player1) && isset($rating_player2) && is_numeric($rating_player2) && isset($rating_player3) && is_numeric($rating_player3) && isset($rating_player4) && is_numeric($rating_player4)) {

        $avg_teamA = ($rating_player1 + $rating_player2) / 2;
        $avg_teamB = ($rating_player3 + $rating_player4) / 2;

        $competitors = array(
          array('id' => 1, 'name' => "Player 1", 'skill' => 100, 'rating' => $rating_player1, 'active' => 1),
          array('id' => 2, 'name' => "Player 2", 'skill' => 100, 'rating' => $rating_player2, 'active' => 1),
          array('id' => 3, 'name' => "Player 3", 'skill' => 100, 'rating' => $rating_player3, 'active' => 1),
          array('id' => 4, 'name' => "Player 4", 'skill' => 100, 'rating' => $rating_player4, 'active' => 1),
        );

        //  initialize the ranking system and add the competitors
        $elo = new EloRatingSystem(100, 50);
        foreach ($competitors as $competitor) {
          $elo->addCompetitor(new EloCompetitor($competitor['id'], $competitor['name'], $competitor['rating']));
        }

        if ($result==1) {
          $elo->addResultDouble(
              $competitors[0], 
              $competitors[1], 
              $competitors[2], 
              $competitors[3]
            );
          $match = "Team A defeats Team B";
          //$result="player1";
        }
        else {
          $elo->addResultDouble(
              $competitors[0], 
              $competitors[1], 
              $competitors[2], 
              $competitors[3],
              true 
            );
          $match = "TIE Team A - Team B";
          //$result="draw";
        }

        $elo->updateRatings();

        $tabRank = $elo->getRankings();


        foreach ($tabRank as $idP => $val) {
          $exp=explode("#", $idP);
          if ($exp[0]==1) {
            $evol=$val-$rating_player1;
            if ($evol>0) $arrRt[1]="+".number_format($evol, 1);
            else $arrRt[1]=number_format($evol, 1);
            if ($exp[0]==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[1];
          }
          elseif ($exp[0]==2) {
            $evol=$val-$rating_player2;
            if ($evol>0) $arrRt[2]="+".number_format($evol, 1);
            else $arrRt[2]=number_format($evol, 1);
            if ($exp[0]==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[2];
          }
          elseif ($exp[0]==3) {
            $evol=$val-$rating_player3;
            if ($evol>0) $arrRt[3]="+".number_format($evol, 1);
            else $arrRt[3]=number_format($evol, 1);
            if ($exp[0]==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[3];
          }
          elseif ($exp[0]==4) {
            $evol=$val-$rating_player4;
            if ($evol>0) $arrRt[4]="+".number_format($evol, 1);
            else $arrRt[4]=number_format($evol, 1);
            if ($exp[0]==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[4];
          }


          /*if ($exp[0]==1) {
            $evol=$val-$avg_teamA;
            if ($evol>0) $arrRt[1]="+".number_format($evol, 1);
            else $arrRt[1]=number_format($evol, 1);

            $arrRt[2]=$arrRt[1];

            if ($exp[0]==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[1];

          }
          elseif ($exp[0]==2) {
            $evol=$val-$avg_teamB;
            if ($evol>0) $arrRt[3]="+".number_format($evol, 1);
            else $arrRt[3]=number_format($evol, 1);

            $arrRt[4]=$arrRt[3];

            if ($exp[0]==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[3];
          }*/

        }
      
      }

    }
    /* END POINTS EVOL PER MATCH */


    return $this->render('site/player_view_matches_paddle.html.twig', array("listMatchs" => $listMatchs,
      'player' => $player,
      'lastR' => $lastR,
      'maxpage' => $maxpage,
      'nbPages' => $nbPages,
      'page' => $page,
      'arrMEvol' => $arrMEvol
    ));
    
  }

  /**
   * @Route("/players/matchesdouble/{id}/{page}", 
   * name="player_view_matches_double", 
   * requirements={
   *   "id"="\d+", 
   *   "page"="\d+", 
   * })
   */
  public function viewMatchesDouble($id, $page, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $player = $em->getRepository('App\Entity\Player')->findOneBy(array('id'=>$id));
    $lastR = $em->getRepository('App\Entity\Player')->getLastRanking($id, "Double");

    $where="WHERE (m.idplayer1= ".$id." OR m.idplayer2= ".$id." OR m.idplayer3= ".$id." OR m.idplayer4= ".$id.")";
    $maxpage=5000;
    $listTotMatchs = $em->getRepository('App\Entity\Matchsdouble')->getMatchsPerPageByPlayer($page, $maxpage, $id);

    $dql   = "SELECT m FROM App:Matchsdouble m ".$where." ORDER BY m.date DESC";
    $query = $em->createQuery($dql);

    $paginator  = $this->get('knp_paginator');

    if (is_numeric($maxpage)) {
      $limitpage=$maxpage;
      $nbPages = ceil(count($listTotMatchs)/$maxpage);
    }
    else {
      $limitpage=count($listTotMatchs);
      $nbPages=1;
    }

    if ($page < 1 || $page > $nbPages) {
      $page = 1;
      // throw $this->createNotFoundException("Page ".$page." doesn't exist.");
    }

    $listMatchs = $paginator->paginate(
      $query, 
      $request->query->getInt('page', $page),
      $limitpage
    );


    /* POINTS EVOL PER MATCH */

    // for each match, we calculate the points evolution
    $sql_m   = 'SELECT m.id, m.date, m.tie, p1.id AS p1id, p2.id AS p2id, p1.initialRatingDouble AS p1IR, p2.initialRatingDouble AS p2IR, p3.id AS p3id, p4.id AS p4id, p3.initialRatingDouble AS p3IR, p4.initialRatingDouble AS p4IR 
                  FROM MatchsDouble m, Player p1, Player p2, Player p3, Player p4
                  '.$where." 
                  AND p1.id=m.idplayer1
                  AND p2.id=m.idplayer2
                  AND p3.id=m.idplayer3
                  AND p4.id=m.idplayer4
                  ORDER BY m.date DESC";
    $stmt = $em->getConnection()->prepare($sql_m);
    $exec = $stmt->execute();
    $matches = $exec->fetchAll();

    $arrMEvol=array();

    foreach ($matches as $mat) {

      $rankId="";

      // get the closest ranking
      $sql_rank = 'SELECT id FROM RankingDouble WHERE date<"'.$mat["date"].'" ORDER BY date DESC LIMIT 0,1';
      $stmt = $em->getConnection()->prepare($sql_rank);
      $exec = $stmt->execute();
      $rank = $exec->fetchAll();
      if (isset($rank[0])) $rankId=$rank[0]["id"];
      
      $rating_player1=$mat["p1IR"];
      $rating_player2=$mat["p2IR"];
      $rating_player3=$mat["p3IR"];
      $rating_player4=$mat["p4IR"];

      $arrMEvol[$mat["id"]]=0;

      if ($rankId!="") {
        $sql_rank = 'SELECT score FROM RankingPosDouble WHERE idRankingDouble="'.$rankId.'" AND idPlayer='.$mat["p1id"];
        $stmt = $em->getConnection()->prepare($sql_rank);
        $exec = $stmt->execute();
        $rank = $exec->fetchAll();
        if (isset($rank[0])) $rating_player1=$rank[0]["score"];

        $sql_rank = 'SELECT score FROM RankingPosDouble WHERE idRankingDouble="'.$rankId.'" AND idPlayer='.$mat["p2id"];
        $stmt = $em->getConnection()->prepare($sql_rank);
        $exec = $stmt->execute();
        $rank = $exec->fetchAll();
        if (isset($rank[0])) $rating_player2=$rank[0]["score"];

        $sql_rank = 'SELECT score FROM RankingPosDouble WHERE idRankingDouble="'.$rankId.'" AND idPlayer='.$mat["p3id"];
        $stmt = $em->getConnection()->prepare($sql_rank);
        $exec = $stmt->execute();
        $rank = $exec->fetchAll();
        if (isset($rank[0])) $rating_player3=$rank[0]["score"];

        $sql_rank = 'SELECT score FROM RankingPosDouble WHERE idRankingDouble="'.$rankId.'" AND idPlayer='.$mat["p4id"];
        $stmt = $em->getConnection()->prepare($sql_rank);
        $exec = $stmt->execute();
        $rank = $exec->fetchAll();
        if (isset($rank[0])) $rating_player4=$rank[0]["score"];

      }

      if ($mat["tie"]==0) $result=1;
      else $result=0;

      if ($id==$mat["p1id"]) $idPFin=1; // team A
      elseif ($id==$mat["p2id"]) $idPFin=2; // team A 
      elseif ($id==$mat["p3id"]) $idPFin=3; // team B
      elseif ($id==$mat["p4id"]) $idPFin=4; // team B

      if (isset($rating_player1) && is_numeric($rating_player1) && isset($rating_player2) && is_numeric($rating_player2) && isset($rating_player3) && is_numeric($rating_player3) && isset($rating_player4) && is_numeric($rating_player4)) {

        $avg_teamA = ($rating_player1 + $rating_player2) / 2;
        $avg_teamB = ($rating_player3 + $rating_player4) / 2;

        $competitors = array(
          array('id' => 1, 'name' => "Player 1", 'skill' => 100, 'rating' => $rating_player1, 'active' => 1),
          array('id' => 2, 'name' => "Player 2", 'skill' => 100, 'rating' => $rating_player2, 'active' => 1),
          array('id' => 3, 'name' => "Player 3", 'skill' => 100, 'rating' => $rating_player3, 'active' => 1),
          array('id' => 4, 'name' => "Player 4", 'skill' => 100, 'rating' => $rating_player4, 'active' => 1),
        );

        //  initialize the ranking system and add the competitors
        $elo = new EloRatingSystem(100, 50);
        foreach ($competitors as $competitor) {
          $elo->addCompetitor(new EloCompetitor($competitor['id'], $competitor['name'], $competitor['rating']));
        }

        if ($result==1) {
          $elo->addResultDouble(
              $competitors[0], 
              $competitors[1], 
              $competitors[2], 
              $competitors[3]
            );
          $match = "Team A defeats Team B";
          //$result="player1";
        }
        else {
          $elo->addResultDouble(
              $competitors[0], 
              $competitors[1], 
              $competitors[2], 
              $competitors[3],
              true 
            );
          $match = "TIE Team A - Team B";
          //$result="draw";
        }

        $elo->updateRatings();

        $tabRank = $elo->getRankings();


        foreach ($tabRank as $idP => $val) {
          $exp=explode("#", $idP);
          if ($exp[0]==1) {
            $evol=$val-$rating_player1;
            if ($evol>0) $arrRt[1]="+".number_format($evol, 1);
            else $arrRt[1]=number_format($evol, 1);
            if ($exp[0]==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[1];
          }
          elseif ($exp[0]==2) {
            $evol=$val-$rating_player2;
            if ($evol>0) $arrRt[2]="+".number_format($evol, 1);
            else $arrRt[2]=number_format($evol, 1);
            if ($exp[0]==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[2];
          }
          elseif ($exp[0]==3) {
            $evol=$val-$rating_player3;
            if ($evol>0) $arrRt[3]="+".number_format($evol, 1);
            else $arrRt[3]=number_format($evol, 1);
            if ($exp[0]==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[3];
          }
          elseif ($exp[0]==4) {
            $evol=$val-$rating_player4;
            if ($evol>0) $arrRt[4]="+".number_format($evol, 1);
            else $arrRt[4]=number_format($evol, 1);
            if ($exp[0]==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[4];
          }


          /*if ($exp[0]==1) {
            $evol=$val-$avg_teamA;
            if ($evol>0) $arrRt[1]="+".number_format($evol, 1);
            else $arrRt[1]=number_format($evol, 1);

            $arrRt[2]=$arrRt[1];

            if ($exp[0]==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[1];

          }
          elseif ($exp[0]==2) {
            $evol=$val-$avg_teamB;
            if ($evol>0) $arrRt[3]="+".number_format($evol, 1);
            else $arrRt[3]=number_format($evol, 1);

            $arrRt[4]=$arrRt[3];

            if ($exp[0]==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[3];
          }*/

        }
      
      }
    }
    /* END POINTS EVOL PER MATCH */

    return $this->render('site/player_view_matches_double.html.twig', array("listMatchs" => $listMatchs,
      'player' => $player,
      'lastR' => $lastR,
      'maxpage' => $maxpage,
      'nbPages' => $nbPages,
      'page' => $page,
      'arrMEvol' => $arrMEvol
    ));
    
  }

  /**
   * @Route("/players/evolution/{id}", 
   * name="player_view_evolution", 
   * requirements={
   *   "id"="\d+", 
   * })
   */
  public function viewEvolution($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $arrDate=array();

    $player = $em->getRepository('App\Entity\Player')->findOneBy(array('id'=>$id));
    $lastR = $em->getRepository('App\Entity\Player')->getLastRanking($id);

    $maxVal=0;
    $minVal=2000;

    $rankingScores = $em->getRepository('App\Entity\Rankingpos')->findBy(array('idplayer'=>$id), array('idranking' => 'ASC'));

    $arrRS=array();


    foreach ($rankingScores as $key=>$rs) {
      // add the initial rankings on the first loop
      if ($key==0) {
        $date_1week = new \DateTime($rs->getIdRanking()->getDate()->format("Y-m-d"));
        // initial rankings
        $date_1_week_format=$date_1week->modify('-7 day')->format("Y-m-d");

        $arrRS[$date_1_week_format] = $player->getinitialRatingTennis();

        $arrDate[]=$date_1_week_format;

      }
      $arrRS[$rs->getIdRanking()->getDate()->format("Y-m-d")]=$rs->getScore();

      if ($rs->getScore()<$minVal) $minVal=$rs->getScore();
      if ($rs->getScore()>$maxVal) $maxVal=$rs->getScore();

      $arrDate[]=$rs->getIdRanking()->getDate()->format("Y-m-d");

    }
    // sorting per index
    ksort($arrRS);

    // EVOL RANKING DOUBLE 
    
    $lastRDouble = $em->getRepository('App\Entity\Player')->getLastRanking($id, "Double");

    $rankingScoresDouble = $em->getRepository('App\Entity\Rankingposdouble')->findBy(array('idplayer'=>$id), array('idrankingdouble' => 'ASC'));

    $arrRSDouble=array();


    foreach ($rankingScoresDouble as $key=>$rs) {
      // add the initial rankings on the first loop
      if ($key==0) {
        $date_1week = new \DateTime($rs->getIdRankingdouble()->getDate()->format("Y-m-d"));
        // initial rankings
        $date_1_week_format=$date_1week->modify('-7 day')->format("Y-m-d");
        $arrRSDouble[$date_1_week_format] = $player->getinitialRatingDouble();

        if (!in_array($date_1_week_format, $arrDate)) $arrDate[]=$date_1_week_format;

      }
      $df=$rs->getIdRankingdouble()->getDate()->format("Y-m-d");
      $arrRSDouble[$df]=$rs->getScore();

      if ($rs->getScore()<$minVal) $minVal=$rs->getScore();
      if ($rs->getScore()>$maxVal) $maxVal=$rs->getScore();

      if (!in_array($df, $arrDate)) $arrDate[]=$df;
    }
    // sorting per index
    ksort($arrRSDouble);



    // EVOL RANKING PADEL 

    $lastRPaddle = $em->getRepository('App\Entity\Player')->getLastRanking($id, "Paddle");

    $rankingScoresPadel = $em->getRepository('App\Entity\Rankingpospaddle')->findBy(array('idplayer'=>$id), array('idrankingpaddle' => 'ASC'));

    $arrRSPadel=array();


    foreach ($rankingScoresPadel as $key=>$rs) {
      // add the initial rankings on the first loop
      if ($key==0) {
        $date_1week = new \DateTime($rs->getIdRankingpaddle()->getDate()->format("Y-m-d"));
        // initial rankings
        $date_1_week_format=$date_1week->modify('-7 day')->format("Y-m-d");
        $arrRSPadel[$date_1_week_format] = $player->getinitialRatingPaddle();

        if (!in_array($date_1_week_format, $arrDate)) $arrDate[]=$date_1_week_format;

      }
      $df=$rs->getIdRankingpaddle()->getDate()->format("Y-m-d");
      $arrRSPadel[$df]=$rs->getScore();

      if ($rs->getScore()<$minVal) $minVal=$rs->getScore();
      if ($rs->getScore()>$maxVal) $maxVal=$rs->getScore();

      if (!in_array($df, $arrDate)) $arrDate[]=$df;
    }
    // sorting per index
    ksort($arrRSPadel);



    // sorting per index
    asort($arrDate);

    $arrRS_tennis_final=array();
    $arrRS_padel_final=array();

    foreach ($arrDate as $date) {

      if (isset($arrRSPadel[$date])) $arrRS_padel_final[$date]=$arrRSPadel[$date];
      else $arrRS_padel_final[$date]=null;

      if (isset($arrRSDouble[$date])) $arrRS_double_final[$date]=$arrRSDouble[$date];
      else $arrRS_double_final[$date]=null;

      if (isset($arrRS[$date])) $arrRS_tennis_final[$date]=$arrRS[$date];
      else $arrRS_tennis_final[$date]=null;

    }

    return $this->render('site/player_view_evolution.html.twig', [
      'controller_name' => 'PlayerController',
      'player' => $player,
      'arrDate' => $arrDate,
      'lastR' => $lastR,
      'arrRS' => $arrRS_tennis_final,
      'lastRPaddle' => $lastRPaddle,
      'lastRDouble' => $lastRDouble,
      'arrRSPaddle' => $arrRS_padel_final,
      'arrRSDouble' => $arrRS_double_final,
      'minVal' => ($minVal-50),
      'maxVal' => ($maxVal+50),
    ]);
  }

  /**
   * @Route(
   * "/players/list/{maxpage}/{page}", 
   * name="players_list", 
   * requirements={
   *   "page"="\d+" 
   * })
   */
  public function list($maxpage, $page, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $filter="";
    $where="";
    if ($request->isMethod('GET')) {
      if ($request->query->get('filter') && strlen($request->query->get('filter'))>2 ) {
        $filter=$request->query->get('filter');
        $where = " WHERE p.nameshort LIKE '%".$filter."%' OR p.namelong LIKE '%".$filter."%' OR p.email LIKE '%".$filter."%' ";
      }
    }

    $listTotPlayers = $em->getRepository('App\Entity\Player')->getPlayersPerPage($page, $maxpage, $filter);

    $dql   = "SELECT p FROM App:Player p ".$where." ORDER BY p.namelong";
    $query = $em->createQuery($dql);

    $paginator  = $this->get('knp_paginator');

    if (is_numeric($maxpage)) {
      $limitpage=$maxpage;
      $nbPages = ceil(count($listTotPlayers)/$maxpage);
    }
    else {
      $limitpage=count($listTotPlayers);
      $nbPages=1;
    }

    if ($page < 1 || $page > $nbPages) {
      $page = 1;
      // throw $this->createNotFoundException("Page ".$page." doesn't exist.");
    }

    $listPlayers = $paginator->paginate(
      $query, 
      $request->query->getInt('page', $page),
      $limitpage
    );

    return $this->render('site/player_list.html.twig', array("listPlayers" => $listPlayers,
      'maxpage' => $maxpage,
      'nbPages' => $nbPages,
      'page' => $page,
      'filter' => $filter
    ));
    
  }


  function getFormPlayer($player, $mode) {

    // $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $player);
    $formBuilder = $this->createFormBuilder($player);

    $formBuilder
    ->add('nameshort', TextType::class, array(
      'required'   => false,
    ))
    ->add('namelong', TextType::class, array(
      'required'   => true,
    ))
    ->add('email', EmailType::class, array(
      'required'   => false,
    ))
    ->add('phone', TextType::class, array(
      'required'   => false,
    ))
    ->add('birthdate', DateType::class, array(
      'required'   => false,
      'years'      => range(date('Y'), date('Y') - 60, -1)
    ))
    ->add('country', EntityType::class, array(
      'class' => Country::class,
      'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC');
      },
      'choice_label' => 'name',
    ))
    ->add('initialRatingTennis', TextType::class, array(
      'required'   => true,
    ))
    ->add('initialRatingPaddle', TextType::class, array(
      'label'    => 'Initial Rating Padel',
      'required'   => true,
    ))
    ->add('initialRatingDouble', TextType::class, array(
      'required'   => true,
    ))
    // ->add('username', TextType::class, array(
    //   'required'   => false,
    // ))
    // ->add('password', TextType::class, array(
    //   'required'   => false,
    // ))
    // ->add('roles', ChoiceType::class, array(
    //   'multiple' => true,
    //   'choices' => array(
    //     'ROLE_USER' => 'ROLE_USER',
    //     'ROLE_ADMIN' => 'ROLE_ADMIN',
    //   ),
    //   'required'   => false,
    // ))
    ;

    // $form = $formBuilder->getForm();

    if ($mode=="edit") {
      $formBuilder
      ->add('activetennis', ChoiceType::class, array(
        'label'    => 'Active Tennis',
        'choices' => array("no" => 0, "yes" => 1),
        'required'   => true,
      ))
      ->add('activepaddle', ChoiceType::class, array(
        'label'    => 'Active Padel',
        'choices' => array("no" => 0, "yes" => 1),
        'required'   => true,
      ))
      ->add('update', SubmitType::class, array('label' => 'Update'))
      ;
    }
    else {
      $formBuilder
      // ->add('password', TextType::class, array(
      //   'required'   => true,
      // ))
      ->add("Create", SubmitType::class);
    }

    $form = $formBuilder->getForm();

    return $form;

  }

  /**
   * @Route(
   * "/players/new", 
   * name="player_new"
   * )
   */
  public function new(Request $request) {

    $em = $this->getDoctrine()->getManager();
    $player = new Player();

    $form=$this->getFormPlayer($player, "add");

    if ($request->isMethod('POST')) {

      $form->handleRequest($request);

      if ($form->isValid()) {

        // $rolesString=implode(",", $form->get('roles')->getData());
        // $player->setRoles($rolesString);
        // $player->setPassword(password_hash($form->get('password')->getData(), PASSWORD_DEFAULT));

        try {

          if ($form->get('initialRatingTennis')->getData()!=0 && is_numeric($form->get('initialRatingTennis')->getData())) {
            $player->setActivetennis(1);
          }
          else $player->setActivetennis(0);

          if ($form->get('initialRatingPaddle')->getData()!=0 && is_numeric($form->get('initialRatingPaddle')->getData())) {
            $player->setActivepaddle(1);
          }
          else $player->setActivepaddle(0);

          if ($form->get('initialRatingDouble')->getData()!=0 && is_numeric($form->get('initialRatingDouble')->getData())) {
            $player->setActivetennis(1);
          }

          $em->persist($player);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success', 'Player created.');

          return $this->redirectToRoute('player_view', array('id' => $player->getId()));
        }
        catch(UniqueConstraintViolationException $e) {
          $form->get('username')->addError(new  \Symfony\Component\Form\FormError(
            "This username already exists in the database"
          ));

          return $this->render('site/player_view.html.twig', array(
            'form' => $form->createView(),
          ));
        }

      }
    }

    return $this->render('site/player_new.html.twig', array(
      'form' => $form->createView(),
    ));

  }

  /**
   * @Route(
   * "/players/update/{id}", 
   * name="player_update", 
   * requirements={
   *   "id"="\d+" 
   * })
   */
  public function updatePlayerAction($id, Request $request) {

    $em = $this->getDoctrine()->getManager();
    $player = $em->getRepository('App\Entity\Player')->findOneBy(['id' => $id]);

    $form=$this->getFormPlayer($player, "edit");

    if ($request->isMethod('POST')) {

      $form->handleRequest($request);

      if ($form->isValid()) {

        try {
          // $rolesString=implode(",", $form->get('roles')->getData());
          // $player->setRoles($rolesString);

          $em->persist($player);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success', 'Player updated.');

        } 
        catch(UniqueConstraintViolationException $e) {
          $form->get('username')->addError(new  \Symfony\Component\Form\FormError(
            "This username already exists in the database"
          ));

        }
      }
    }

    return $this->render('site/player_update.html.twig', array(
      'form' => $form->createView(),
    ));
  }


  /*public function getBestRating ($id, $date) {

    $em = $this->getDoctrine()->getManager();
    $player = $em->getRepository('App\Entity\Player')->findOneBy(['id' => $id]);

     // get the closest ranking
    $sql_best = 'SELECT MAX(score) FROM RankingPos WHERE date<="'.$date.'" AND idPlayer='.$id;
    $stmt = $em->getConnection()->prepare($sql_best);
    $stmt->execute();
    $best = $exec->fetchAll();
    if (isset($best[0])) $best=$best[0]["score"];
    else $best=0;

    return $best;

  }*/


  /**
   * @Route(
   * "/player/myprofile", 
   * name="player_profile_edit")
   */
  public function editMyProfile(Request $request) {

    $user = $this->get('security.token_storage')->getToken()->getUser();

    if (!isset($user)) {
      $request->getSession()->getFlashBag()->add('error', 'User not logged in');
      return 0;
    }
    else {
      $em = $this->getDoctrine()->getManager();

      $player=$user->getIdplayer();

      $formBuilder = $this->createFormBuilder($player);

      $formBuilder
      ->add('email', EmailType::class, array(
        'label'    => 'Email',
        'required' => false,
      ));
      $formBuilder
      ->add('update', SubmitType::class, array('label' => 'Update'))
      ;

      $form = $formBuilder->getForm();

      if ($request->isMethod('POST')) {

        $form->handleRequest($request);

        if ($form->isValid()) {

          $em->persist($user);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success', 'Profile updated.');

        }
      }

      
      return $this->render('site/player_profile_form.html.twig', array(
        'form' => $form->createView(),
        'form_title' => "Update my profile",
        'email_admin' => $_SERVER['EMAIL_ADMIN']
      ));

    }

    
  }

}
