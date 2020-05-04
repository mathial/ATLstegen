<?php

namespace App\Controller;

use \Symfony\Component\Form\Extension\Core\Type\FormType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use \Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Matchs;
use App\Entity\Player;
use App\Entity\EloRatingSystem;
use App\Entity\EloCompetitor;

class MatchsController extends Controller
{
    /**
     * @Route("/matchs", name="matchs")
     */
    public function index()
    {
        return $this->render('matchs/index.html.twig', [
            'controller_name' => 'MatchsController',
        ]);
    }

  /**
   * @Route(
   * "/matchs/list/{maxpage}/{page}", 
   * name="matchs_list", 
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
      if ($request->query->get('filter')) {
        $filter=$request->query->get('filter');
        $where = " WHERE (m.idplayer1 = ".$filter." OR m.idplayer2 = ".$filter.")";
      }
    }

    $listTotMatchs = $em->getRepository('App:Matchs')->getMatchsPerPage($page, $maxpage, $filter);

    $dql   = "SELECT m FROM App:Matchs m ".$where." ORDER BY m.date DESC, m.id DESC";
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
    $sql_m   = 'SELECT m.id, m.date, m.tie, p1.id AS p1id, p2.id AS p2id, p1.initialRating AS p1IR, p2.initialRating AS p2IR 
                  FROM Matchs m, Player p1, Player p2
                  '.($where!="" ? $where : " WHERE 1 ")." 
                  AND p1.id=m.idplayer1
                  AND p2.id=m.idplayer2
                  ORDER BY m.date DESC, m.id DESC";
    $stmt = $em->getConnection()->prepare($sql_m);
    $stmt->execute();
    $matches = $stmt->fetchAll();

    $arrMEvol=array();

    foreach ($matches as $mat) {

      $rankId="";

      // get the closest ranking
      $sql_rank = 'SELECT id FROM Ranking WHERE date<="'.$mat["date"].'" ORDER BY date DESC LIMIT 0,1';
      $stmt = $em->getConnection()->prepare($sql_rank);
      $stmt->execute();
      $rank = $stmt->fetchAll();
      if (isset($rank[0])) $rankId=$rank[0]["id"];
      
      $rating_player1=$mat["p1IR"];
      $rating_player2=$mat["p2IR"];
      $arrMEvol[$mat["id"]]=0;

      if ($rankId!="") {
        $sql_rank = 'SELECT score FROM RankingPos WHERE idRanking="'.$rankId.'" AND idPlayer='.$mat["p1id"];
        $stmt = $em->getConnection()->prepare($sql_rank);
        $stmt->execute();
        $rank = $stmt->fetchAll();
        if (isset($rank[0])) $rating_player1=$rank[0]["score"];

        $sql_rank = 'SELECT score FROM RankingPos WHERE idRanking="'.$rankId.'" AND idPlayer='.$mat["p2id"];
        $stmt = $em->getConnection()->prepare($sql_rank);
        $stmt->execute();
        $rank = $stmt->fetchAll();
        if (isset($rank[0])) $rating_player2=$rank[0]["score"];

      }

      if ($mat["tie"]==0) $result=1;
      else $result=0;

     /* if ($id==$mat["p1id"]) $idPFin=1;
      else $idPFin=2;*/

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
          }
          
          $arrMEvol[$mat["id"]]=$arrRt[1];
        }
      
      }

    }

    /* END POINTS EVOL PER MATCH */


    return $this->render('site/matchs_list.html.twig', array("listMatchs" => $listMatchs,
      'maxpage' => $maxpage,
      'nbPages' => $nbPages,
      'page' => $page,
      'filter' => $filter,
      'arrMEvol' => $arrMEvol
    ));
    
  }

  /**
   * @Route(
   * "/matchs/new", 
   * name="matchs_new")
   */
  public function new(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    
    $match = new Matchs();
    $formBuilder = $this->createFormBuilder($match);

    $formBuilder
    ->add('date', DateType::class, array(
      'required'   => true,
      'label'   => 'Date',
      'format' => 'yyyy-MM-dd',
      'widget' => 'single_text',
    ))
    ->add('idplayer1', EntityType::class, array(
      'label'   => 'Winner',
      'class' => Player::class,
      'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('p')
            ->where('p.active=1')
            ->orderBy('p.nameshort', 'ASC');
      },
      'choice_label' => 'nameshort',
    ))
    ->add('idplayer2', EntityType::class, array(
      'label'   => '2nd player',
      'class' => Player::class,
      'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('p')
            ->where('p.active=1')
            ->orderBy('p.nameshort', 'ASC');
      },
      'choice_label' => 'nameshort',
    ))
    ->add('score', TextType::class, array(
      'label'    => 'Score',
      'required' => true,
    ))
    ->add('conditions', ChoiceType::class, array(
      'label'    => 'Conditions',
      'choices' => array("hard indoor" => "hard indoor", "clay outdoor" => "clay outdoor"),
      'required'   => true,
    ))
    ->add('context', ChoiceType::class, array(
      'label'    => 'Conditions',
      'choices' => array("Stege (söndag 21-22)" => "Stege (söndag 21-22)", "Stege" => "Stege", "A-serien" => "A-serien", "Sprinttennis tournament" => "Sprinttennis tournament", "ATL Klubbmästerskap" => "ATL Klubbmästerskap"),
      'required'   => true,
    ))
    ->add('tie', ChoiceType::class, array(
      'label'    => 'Tie',
      'choices' => array("no" => 0, "yes" => 1),
      'required'   => true,
    ))
    ->add("Create", SubmitType::class);

    $form = $formBuilder->getForm();

    if ($request->isMethod('POST')) {

      $form->handleRequest($request);

      if ($form->isValid()) {

        $em->persist($match);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Match created.');

      }
    }

    return $this->render('site/matchs_new.html.twig', array(
      'form' => $form->createView()
    ));
    
  }

  /**
   * @Route(
   * "/matchs/sunday-contest/", 
   * name="sunday_contest")
   */
  public function sundayContest(Request $request)
  {
    $em = $this->getDoctrine()->getManager();


    $sql   = 'SELECT DISTINCT idPlayer1 AS idP FROM Matchs m WHERE context="Stege (söndag 21-22)" 
    UNION SELECT DISTINCT idPlayer2 AS idP FROM Matchs m WHERE context="Stege (söndag 21-22)" 
    ORDER BY idP';
    $stmt = $em->getConnection()->prepare($sql);
    $stmt->execute();
    $players = $stmt->fetchAll();

    $arrContest=array();
    $arrContestData=array();

    foreach ($players as $player) {
      $dataPlayer = $em->getRepository('App:Player')->findOneBy(array('id'=>$player["idP"]));

      $sql_nb   = 'SELECT COUNT(DISTINCT date) AS tot FROM Matchs m WHERE context="Stege (söndag 21-22)" AND (idPlayer1 = '.$player["idP"].' OR idPlayer2 = '.$player["idP"].') ';
      $stmt = $em->getConnection()->prepare($sql_nb);
      $stmt->execute();
      $nbM = $stmt->fetchAll();

      $arrContest[$player["idP"]]=$nbM[0]["tot"];

      $arrContestData[$player["idP"]]["name"]=$dataPlayer->getNameShort();


    }

    arsort($arrContest);


    return $this->render('site/sunday_contest.html.twig', array("arrContest" => $arrContest, "arrContestData" => $arrContestData
    ));
    
  }

  /**
   * @Route(
   * "/matchs/race-slutspel/", 
   * name="race_slutspel")
   */
  public function raceTournament(Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $arrRace=array();

    $spe_date="2020-04-25";
    // total of matches played since the date
    $sql   = 'SELECT SUM(nbM) AS totNbM, idP FROM
              (
              SELECT COUNT(*) AS nbM, idPlayer1 AS idP FROM Matchs WHERE date>"'.$spe_date.'" GROUP BY idPlayer1 UNION ALL SELECT COUNT(*) AS nbM, idPlayer2 AS idP FROM Matchs WHERE date>"'.$spe_date.'" GROUP BY idPlayer2
              ) AS totTab
              GROUP BY idP
              ORDER By totNbM DESC';
    $stmt = $em->getConnection()->prepare($sql);
    $stmt->execute();
    $arrRace = $stmt->fetchAll();


    $arrRaceTot=array();

    foreach ($arrRace as $rt) {
      $dataPlayer = $em->getRepository('App:Player')->findOneBy(array('id'=>$rt["idP"]));

      $recap=array();
      $recap["idP"]=$rt["idP"];
      $recap["name"]=$dataPlayer->getNameShort();
      $recap["lastyear"]=$rt["totNbM"];

      // total of matches played since the biginning
      $qYear='SELECT SUM(nbM) AS totNbM FROM 
              ( SELECT COUNT(*) AS nbM, idPlayer1 AS idP FROM Matchs WHERE  idPlayer1='.$rt["idP"].' 
              UNION ALL SELECT COUNT(*) AS nbM, idPlayer2 AS idP FROM Matchs WHERE idPlayer2='.$rt["idP"].' ) AS totTab';
      $stmt = $em->getConnection()->prepare($qYear);
      $stmt->execute();
      $rtTot = $stmt->fetchAll();
      $recap["tot"]=$rtTot[0]["totNbM"];


      $arrRaceTot[]=$recap;
    }

//    return $this->render('site/race_slutspel.html.twig', array("arrRace" => $arrRace, "arrRaceData" => $arrRaceData
    return $this->render('site/race_slutspel.html.twig', array("arrRaceTot" => $arrRaceTot
    ));
    
  }





  /**
   * @Route(
   * "/matchs/listMatchsSlutSpel/{year}/{maxpage}/{page}", 
   * name="matchs_list_slutspel", 
   * requirements={
   *   "year"="\d+", 
   *   "maxpage"="\d+", 
   *   "page"="\d+" 
   * })
   */
  public function listMatchsSlutSpel($year, $maxpage, $page, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    /*
    $filter="";
    $where="";
    if ($request->isMethod('GET')) {
      if ($request->query->get('filter')) {
        $filter=$request->query->get('filter');
        $where = " WHERE (m.idplayer1 = ".$filter." OR m.idplayer2 = ".$filter.")";
      }
    }
    */


    $dql   = 'SELECT m FROM App:Matchs m WHERE m.context = :context ORDER BY m.date DESC';
    $query = $em->createQuery($dql)
            ->setParameter('context', "Stegen Slutspel ".$year);;

    $paginator  = $this->get('knp_paginator');

    $listMatchs = $paginator->paginate(
      $query, 
      $request->query->getInt('page', 1),
      50
    );

    return $this->render('site/matchs_list_slutspel.html.twig', array("listMatchs" => $listMatchs,
      'year' => $year
    ));
    
  }

}
