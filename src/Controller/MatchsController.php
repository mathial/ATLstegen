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
    // only for the matches displayed (LIMIT !!)
    $sql_m   = 'SELECT m.id, m.date, m.tie, p1.id AS p1id, p2.id AS p2id, p1.initialRatingTennis AS p1IR, p2.initialRatingTennis AS p2IR 
                  FROM Matchs m, Player p1, Player p2
                  '.($where!="" ? $where : " WHERE 1 ")." 
                  AND p1.id=m.idplayer1
                  AND p2.id=m.idplayer2
                  ORDER BY m.date DESC, m.id DESC
                  LIMIT ".($page-1)*$limitpage.", ".$limitpage;


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


  function getFormMatch($match, $mode) {

    $formBuilder = $this->createFormBuilder($match);

    $formBuilder
    ->add('date', DateType::class, array(
      'required'   => true,
      'label'   => 'Date',
      'format' => 'yyyy-MM-dd',
      'widget' => 'single_text',
    ));

    // for the new matches, set the user idPlayer as default
    if ($mode=="add") {
      $user = $this->get('security.token_storage')->getToken()->getUser();
      $playerDefault=$user->getIdplayer();


      $formBuilder
      ->add('idplayer1', EntityType::class, array(
        'label'   => 'Winner',
        'class' => Player::class,
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('p')
              ->orderBy('p.nameshort', 'ASC');
        },
        'choice_label' => 'nameshort',
        'data' => $playerDefault
      ))
      ->add('idplayer2', EntityType::class, array(
        'label'   => '2nd player',
        'class' => Player::class,
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('p')
              ->orderBy('p.nameshort', 'ASC');
        },
        'choice_label' => 'nameshort',
        'data' => $playerDefault
      ));
    }

    else {
      $formBuilder
      ->add('idplayer1', EntityType::class, array(
        'label'   => 'Winner',
        'class' => Player::class,
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('p')
              ->orderBy('p.nameshort', 'ASC');
        },
        'choice_label' => 'nameshort',
      ))
      ->add('idplayer2', EntityType::class, array(
        'label'   => '2nd player',
        'class' => Player::class,
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('p')
              ->orderBy('p.nameshort', 'ASC');
        },
        'choice_label' => 'nameshort',
        'empty_data' => (isset($playerDefault) ? $playerDefault : null)
      ));
    }

    $formBuilder
    ->add('context', ChoiceType::class, array(
      'label'    => 'CONTEXT (League ? A-series ? Sunday 21-22 ? etc)',
      'choices' => array("Stege" => "Stege", "Stege (söndag 21-22)" => "Stege (söndag 21-22)", "Division League - Round#8" => "Division League - Round#8", "A-serien" => "A-serien", "Sprinttennis tournament" => "Sprinttennis tournament", "Summer tournament 2023 (june)" => "Summer tournament 2023 (june)", "ATL Klubbmästerskap" => "ATL Klubbmästerskap"),
      'required'   => true,
    ))
    ->add('tie', ChoiceType::class, array(
      'label'    => 'TIE ?',
      'choices' => array("no" => 0, "yes" => 1),
      'required'   => true,
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
    ;

    if ($mode=="edit") {
      $formBuilder
      ->add('update', SubmitType::class, array('label' => 'Update'))
      ;
    }
    else {
      $formBuilder
      ->add("Create", SubmitType::class);
    }

    
    

    $form = $formBuilder->getForm();

    return $form;
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
    
    $form = $this->getFormMatch($match, "add");

    if ($request->isMethod('POST')) {

      $form->handleRequest($request);

      if ($form->isValid()) {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $match->setIduseradd($user);

        $em->persist($match);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Match created.');

        $headers ='From: contact@luckylosertennis.com'."\r\n";
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        // $headers .='Content-Type: text/html; charset="iso-8859-1"'."\r\n";
        $headers .='Content-Transfer-Encoding: 8bit'."\r\n";

        // $email_contenu = utf8_decode("et là tu les vois les accéééééénts ?");

        $contenu=''.$match->getIdplayer1()->getNameShort().' VS '.$match->getIdplayer2()->getNameShort().' : '.$match->getScore();
        $contenu.=($match->getTie()==1 ? ' (TIE)' : "");
        $contenu.='<br>'.$match->getConditions().' - '.$match->getContext();

        if (mail($_SERVER['EMAIL_ADMIN'], "[ATL-St.] tennis single - ".$user->getUsername()." (".$match->getDate()->format('Y-m-d').")", $contenu, $headers)) {}
        else {
          $request->getSession()->getFlashBag()->add('error', 'Error sending email to '.$_SERVER['EMAIL_ADMIN']);

        }
      }
    }

    return $this->render('site/matchs_form.html.twig', array(
      'form' => $form->createView(),
      'form_title' => "New match",
      'type_match' => "Tennis SINGLE"
    ));
    
  }


  /**
   * @Route(
   * "/matchs/update/{id}", 
   * name="match_update", 
   * requirements={
   *   "id"="\d+" 
   * })
   */
  public function edit($id, Request $request) {

    $em = $this->getDoctrine()->getManager();
    $match = $em->getRepository('App:Matchs')->findOneBy(['id' => $id]);

    $form=$this->getFormMatch($match, "edit");

    if ($request->isMethod('POST')) {

      $form->handleRequest($request);

      if ($form->isValid()) {

        $em->persist($match);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Match updated.');

      }
    }

    return $this->render('site/matchs_form.html.twig', array(
      'form' => $form->createView(),
      'form_title' => "Update match",
      'type_match' => "Tennis SINGLE"
    ));
    
  }

  /**
   * @Route(
   * "/matchs/delete/{id}", 
   * name="match_delete", 
   * requirements={
   *   "id"="\d+" 
   * })
   */
  public function delete($id, Request $request) {

    if ($this->getUser()!== NULL && in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {

        $em = $this->getDoctrine()->getManager();
        $match = $em->getRepository('App:Matchs')->findOneBy(['id' => $id]);

        $em->remove($match);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'Match '.$id.' deleted.');
    }
    else {
        $request->getSession()->getFlashBag()->add('error', 'Only for ADMINS.');
    }

    return $this->redirectToRoute('matchs_list', array('maxpage' =>50, 'page'=>1));
    
  }

  /**
   * @Route(
   * "/matchs/sunday-contest/", 
   * name="sunday_contest")
   */
  public function sundayContest(Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $contextName="Stege (söndag 21-22)";

    // count distinct sessions
    $sql_nb   = 'SELECT COUNT(DISTINCT date) AS tot, YEAR(date) AS yearDate FROM Matchs m WHERE context="'.$contextName.'" GROUP BY yearDate';
    $stmt = $em->getConnection()->prepare($sql_nb);
    $stmt->execute();
    $nbM = $stmt->fetchAll();

    $arrSessions=array();
    $arrSessions["tot"]=0;
    $arrTotPerYear=array();

    // in case no match yet in pending year
    $arrSessions[date("Y")]=0;
    $arrTotPerYear[date("Y")]=0;
    foreach ($nbM as $year) {
      $arrSessions[$year["yearDate"]]=$year["tot"];
      $arrSessions["tot"]+=$year["tot"];
      $arrTotPerYear[$year["yearDate"]]=0;
    }

    $sql   = 'SELECT DISTINCT idPlayer1 AS idP FROM Matchs m WHERE context="'.$contextName.'" 
    UNION SELECT DISTINCT idPlayer2 AS idP FROM Matchs m WHERE context="'.$contextName.'" 
    ORDER BY idP';
    $stmt = $em->getConnection()->prepare($sql);
    $stmt->execute();
    $players = $stmt->fetchAll();

    $arrTot=array();
    $arrContest=array();
    $arrContestData=array();
    $arrIdPlayer=array();

    foreach ($players as $player) {
      $dataPlayer = $em->getRepository('App:Player')->findOneBy(array('id'=>$player["idP"]));

      $arrIdPlayer[]=$player["idP"];

      $sql_nb   = 'SELECT COUNT(DISTINCT date) AS tot, YEAR(date) AS yearDate FROM Matchs m WHERE context="'.$contextName.'" AND (idPlayer1 = '.$player["idP"].' OR idPlayer2 = '.$player["idP"].') 
        GROUP BY yearDate';
      $stmt = $em->getConnection()->prepare($sql_nb);
      $stmt->execute();
      $nbM = $stmt->fetchAll();

      foreach ($nbM as $year) {

        $arrTotPerYear[$year["yearDate"]]++;

        $arrContest[$player["idP"]][$year["yearDate"]]=$year["tot"];
        if (!isset($arrTot[$player["idP"]])) {
          $arrTot[$player["idP"]]=0;
        }
        $arrTot[$player["idP"]]+=$year["tot"];

        $arrContest[$player["idP"]][$year["yearDate"]."%"]=number_format($arrContest[$player["idP"]][$year["yearDate"]]*100/$arrSessions[$year["yearDate"]],0);
      }
      $arrContestData[$player["idP"]]["name"]=$dataPlayer->getNameShort();


    }

    arsort($arrTot);
    $arrTotPerYear["total"]=count($arrTot);


    // OBTAINING THE RATING AVERAGE OF PLAYERS JOINING THE SESSION
    // 1- get an array of PlayerRating[idPlayer][Year] to store the annual average rating per player (distinct ratings)
    // 2- for every single sunday session, calculate the rating average of all the players joining
    // 3- display with a graph

    //1- 
    $sqlRatingAvg   = 'SELECT P.id, AVG(DISTINCT RP.score) as avg_score, YEAR(R.date) as annee
    FROM `Ranking` R, RankingPos RP, Player P 
    WHERE R.id=RP.idRanking AND RP.idPlayer=P.id 
    and RP.idPlayer in ('.implode(",", $arrIdPlayer).')
    GROUP BY P.id, YEAR(R.date)
    ORDER BY P.id, annee DESC
    ';
    $stmt = $em->getConnection()->prepare($sqlRatingAvg);
    $stmt->execute();
    $rRatingAvg = $stmt->fetchAll();

    $arrRatingAvg=array();
    foreach ($rRatingAvg as $ravg) {
      $arrRatingAvg[$ravg["id"]][$ravg["annee"]]=$ravg["avg_score"];
    }

    //2-
    $sqlPlayerSession='SELECT DISTINCT(idPlayer1) as idP, date, YEAR(date) as annee FROM Matchs WHERE context="'.$contextName.'"
    UNION
    SELECT DISTINCT(idPlayer2) as idP, date, YEAR(date) as annee FROM Matchs WHERE context="'.$contextName.'"
    ORDER BY date';
    $stmt = $em->getConnection()->prepare($sqlPlayerSession);
    $stmt->execute();
    $rPlayerSession = $stmt->fetchAll();

    $arrSessionAvgRating=array();
    foreach ($rPlayerSession as $rps) {
      if (!isset($arrSessionAvgRating[$rps["date"]])) {
        $arrSessionAvgRating[$rps["date"]]["nbP"]=0;
        $arrSessionAvgRating[$rps["date"]]["totRat"]=0;
        $arrSessionAvgRating[$rps["date"]]["avgRat"]=0;
      }

      // si pas de rating : player who just appeared and has not been ranked yet, element not included

      if (isset($arrRatingAvg[$rps["idP"]][$rps["annee"]])) {
        $arrSessionAvgRating[$rps["date"]]["nbP"]++;
        $arrSessionAvgRating[$rps["date"]]["totRat"]+=$arrRatingAvg[$rps["idP"]][$rps["annee"]];
        $arrSessionAvgRating[$rps["date"]]["avgRat"]=$arrSessionAvgRating[$rps["date"]]["totRat"] / $arrSessionAvgRating[$rps["date"]]["nbP"];
      }
      else {
        echo "no averageRating for player ".$rps["idP"]." on date ".$rps["date"]."<br>";
      }

    }

    return $this->render('site/events_sunday_contest.html.twig', array(
        "arrSessions" => $arrSessions, 
        "arrContest" => $arrContest, 
        "arrTot" => $arrTot, 
        "arrContestData" => $arrContestData,
        "arrTotPerYear" => $arrTotPerYear,
        "arrSessionAvgRating" => $arrSessionAvgRating
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
    $bigTot=0;

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

      $bigTot+=$recap["lastyear"];

      $arrRaceTot[]=$recap;
    }

//    return $this->render('site/race_slutspel.html.twig', array("arrRace" => $arrRace, "arrRaceData" => $arrRaceData
    return $this->render('site/events_race_slutspel.html.twig', 
      array("arrRaceTot" => $arrRaceTot, "bigTot" => $bigTot)
    );
    
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




  /**
   * @Route(
   * "/matchs/h2h/{idplayer1}/{idplayer2}", 
   * name="matchs_h2h", 
   * requirements={
   *   "idplayer1"="\d+", 
   *   "idplayer2"="\d+" 
   * })
   */
  public function h2h($idplayer1, $idplayer2, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $player1 = $em->getRepository('App:Player')->findOneBy(array('id'=>$idplayer1));
    $player2 = $em->getRepository('App:Player')->findOneBy(array('id'=>$idplayer2));

    $dql   = 'SELECT m FROM App:Matchs m 
              WHERE (m.idplayer1 = :idplayer1 AND m.idplayer2 = :idplayer2)
              OR (m.idplayer1 = :idplayer2 AND m.idplayer2 = :idplayer1)  
              ORDER BY m.date DESC, m.id DESC';
    $query = $em->createQuery($dql)
            ->setParameter('idplayer1', $idplayer1)
            ->setParameter('idplayer2', $idplayer2)
            ;

    //$result = $query->execute();
    $result = $query->getResult();
    $recapRt=array();
    $recapRt["W"]=0;
    $recapRt["D"]=0;
    $recapRt["T"]=0;
    $arrMEvol=array();
    
    foreach ($result as $m) {

      $rankId="";

      // get the closest ranking
      $sql_rank = 'SELECT id FROM Ranking WHERE date<="'.$m->getDate()->format('Y-m-d').'" ORDER BY date DESC LIMIT 0,1';
      $stmt = $em->getConnection()->prepare($sql_rank);
      $stmt->execute();
      $rank = $stmt->fetchAll();
      if (isset($rank[0])) $rankId=$rank[0]["id"];
      
      $rating_player1=$m->getIdplayer1()->getInitialratingtennis();
      $rating_player2=$m->getIdplayer2()->getInitialratingtennis();
      $arrMEvol[$m->getId()]=0;

      if ($rankId!="") {
        $sql_rank = 'SELECT score FROM RankingPos WHERE idRanking="'.$rankId.'" AND idPlayer='.$m->getIdplayer1()->getId();
        $stmt = $em->getConnection()->prepare($sql_rank);
        $stmt->execute();
        $rank = $stmt->fetchAll();
        if (isset($rank[0])) $rating_player1=$rank[0]["score"];

        $sql_rank = 'SELECT score FROM RankingPos WHERE idRanking="'.$rankId.'" AND idPlayer='.$m->getIdplayer2()->getId();
        $stmt = $em->getConnection()->prepare($sql_rank);
        $stmt->execute();
        $rank = $stmt->fetchAll();
        if (isset($rank[0])) $rating_player2=$rank[0]["score"];

      }

      if ($m->getTie()==0) $result=1;
      else $result=0;

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
          
          $arrMEvol[$m->getId()]=$arrRt[1];
        }
      
      }



      if ($m->getTie()) $recapRt["T"]++;
      elseif ($m->getIdplayer1()->getId() == $idplayer1) $recapRt["W"]++;
      else $recapRt["D"]++;
    }

    $paginator  = $this->get('knp_paginator');

    $listMatchs = $paginator->paginate(
      $query, 
      $request->query->getInt('page', 1),
      50
    );

    return $this->render('site/matchs_h2h.html.twig', array("listMatchs" => $listMatchs,
      "player1" => $player1,
      "player2" => $player2,
      "recapRt" => $recapRt,
      "arrMEvol" => $arrMEvol,
    ));
    
  }

  /**
   * @Route("/matchs/matchup-generator", name="matchup_generator")
   */
  public function generateMatchups(Request $request)
  {
    
    $error="";
    $arrMatch=array();

    $em = $this->getDoctrine()->getManager();

    $arrPlayer=array();
    $players = $em->getRepository('App:Player')->findBy(array(), array('nameshort' => 'ASC'));
    foreach ($players as $pl) {
      $arrPlayer[$pl->getNameShort()] = $pl->getId();
    }

    $defaultData = array('message' => 'Type your message here');
    $formBuilder = $this->createFormBuilder($defaultData);

    $formBuilder
    ->add('sport', ChoiceType::class, array(
      'choices' => array("Tennis-single" => 0, "Tennis-double (1-4 Vs 2-3)" => 1, "Padel (1-4 Vs 2-3)" => 2),
      'required'   => true,
    ))
    ->add('method_generating', ChoiceType::class, array(
      'label' => 'Algorithm',
      'choices' => array("Standard (sorted by ratings)" => 0),
      'required'   => true,
    ))
    ->add('players', ChoiceType::class, array(
      'label' => 'Players (multiple selection with CTRL key)',
      'choices' => $arrPlayer,
      'multiple' => true,
      'required'   => true,
      'attr' => ['class' => 'generator-players']
    ))
    ->add("Generate", SubmitType::class);

    $form = $formBuilder->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $data = $form->getData();

      $sportForm=$data['sport'];
      $methodForm=$data['method_generating'];
      $playersForm=$data['players'];

      if ($sportForm==0 && $methodForm==0) {

        if (count($playersForm)%2!=0) {
          $request->getSession()->getFlashBag()->add('error', count($playersForm)." players selected, needs an even number to work");
          $error=count($playersForm)." players selected, needs an even number to work";
        }
        else {
          $arrPlayerFlip = array_flip($arrPlayer);

          $arrRank=array();
          $ranking = $em->getRepository('App:Ranking')->findOneBy(array(), array('date' => 'DESC'), 1);

          $detailsRankings=$em->getRepository('App:Rankingpos')->getSelectedRankingpos($ranking->getId(), $playersForm);

          foreach ($detailsRankings as $det) {
            $arrRank[$det["idplayer"]]=$det;
          }

          if (count($arrRank)!=count($playersForm)) {
              $request->getSession()->getFlashBag()->add('error', "Missing players in the rankings. ".count($playersForm)." expected, ".count($arrRank)." obtained");
              $error="Missing players in the rankings. ".count($playersForm)." expected, ".count($arrRank)." obtained";
          }
          else {
            $nbCourt=0;
            $player1="";
            $player2="";
            foreach($arrRank as $rank) {
              if ($player1=="")
                $player1 = $arrPlayerFlip[$rank["idplayer"]]." ".number_format($rank["score"], 0, ".", "")." pts ";
              else {
                $player2 = $arrPlayerFlip[$rank["idplayer"]]." ".number_format($rank["score"], 0, ".", "")." pts ";

                $nbCourt++;
                $arrMatch[]="Court ".$nbCourt." => ".$player1. " VS ".$player2;

                $player1="";
                $player2="";
              }


            }
          }
        }
        
      }
      elseif ($sportForm==1 && $methodForm==0) {

        if (count($playersForm)%4!=0) {
          $request->getSession()->getFlashBag()->add('error', "Only ".count($playersForm)." players selected, needs a multiple of 4.");
          $error="Only ".count($playersForm)." players selected, needs a multiple of 4.";
        }
        else {
          $arrPlayerFlip = array_flip($arrPlayer);

          $arrRank=array();
          $ranking = $em->getRepository('App:Rankingdouble')->findOneBy(array(), array('date' => 'DESC'), 1);

          $detailsRankings=$em->getRepository('App:Rankingposdouble')->getSelectedRankingpos($ranking->getId(), $playersForm);

          foreach ($detailsRankings as $det) {
            $arrRank[$det["idplayer"]]=$det;
          }

          if (count($arrRank)!=count($playersForm)) {
              $request->getSession()->getFlashBag()->add('error', "Missing players in the rankings. ".count($playersForm)." expected, ".count($arrRank)." obtained");
              $error="Missing players in the rankings. ".count($playersForm)." expected, ".count($arrRank)." obtained";
          }
          else {
            $nbCourt=0;
            $player1="";
            $player2="";
            $player3="";
            $player4="";
            foreach($arrRank as $rank) {
              if ($player1=="")
                $player1 = $arrPlayerFlip[$rank["idplayer"]]." ".number_format($rank["score"], 0, ".", "")." pts ";
              elseif ($player2=="")
                $player2 = $arrPlayerFlip[$rank["idplayer"]]." ".number_format($rank["score"], 0, ".", "")." pts ";
              elseif ($player3=="")
                $player3 = $arrPlayerFlip[$rank["idplayer"]]." ".number_format($rank["score"], 0, ".", "")." pts ";
              else {
                $player4 = $arrPlayerFlip[$rank["idplayer"]]." ".number_format($rank["score"], 0, ".", "")." pts ";

                $nbCourt++;
                $arrMatch[]="*Court ".$nbCourt."*:";
                $arrMatch[]=$player1. " - ".$player4;
                $arrMatch[]="VS";
                $arrMatch[]=$player2. " - ".$player3;
                $arrMatch[]="";

                $player1="";
                $player2="";
                $player3="";
                $player4="";
              }


            }
          }
        }
      }
      elseif ($sportForm==2 && $methodForm==0) {

        if (count($playersForm)%4!=0) {
          $request->getSession()->getFlashBag()->add('error', "Only ".count($playersForm)." players selected, needs a multiple of 4.");
          $error="Only ".count($playersForm)." players selected, needs a multiple of 4.";
        }
        else {
          $arrPlayerFlip = array_flip($arrPlayer);

          $arrRank=array();
          $ranking = $em->getRepository('App:Rankingpaddle')->findOneBy(array(), array('date' => 'DESC'), 1);

          $detailsRankings=$em->getRepository('App:Rankingpospaddle')->getSelectedRankingpos($ranking->getId(), $playersForm);

          foreach ($detailsRankings as $det) {
            $arrRank[$det["idplayer"]]=$det;
          }

          if (count($arrRank)!=count($playersForm)) {
              $request->getSession()->getFlashBag()->add('error', "Missing players in the rankings. ".count($playersForm)." expected, ".count($arrRank)." obtained");
              $error="Missing players in the rankings. ".count($playersForm)." expected, ".count($arrRank)." obtained";
          }
          else {
            $nbCourt=0;
            $player1="";
            $player2="";
            $player3="";
            $player4="";
            foreach($arrRank as $rank) {
              if ($player1=="")
                $player1 = $arrPlayerFlip[$rank["idplayer"]]." ".number_format($rank["score"], 0, ".", "")." pts ";
              elseif ($player2=="")
                $player2 = $arrPlayerFlip[$rank["idplayer"]]." ".number_format($rank["score"], 0, ".", "")." pts ";
              elseif ($player3=="")
                $player3 = $arrPlayerFlip[$rank["idplayer"]]." ".number_format($rank["score"], 0, ".", "")." pts ";
              else {
                $player4 = $arrPlayerFlip[$rank["idplayer"]]." ".number_format($rank["score"], 0, ".", "")." pts ";

                $nbCourt++;
                $arrMatch[]="*Court ".$nbCourt."*:";
                $arrMatch[]=$player1. " - ".$player4;
                $arrMatch[]="VS";
                $arrMatch[]=$player2. " - ".$player3;
                $arrMatch[]="";

                $player1="";
                $player2="";
                $player3="";
                $player4="";
              }


            }
          }
        }
      }

    }

    return $this->render('matchs/matchup_generator.html.twig', [
      'controller_name' => 'MatchsController',
      'form' => $form->createView(),
      'error' => $error,
      'arrMatch' => $arrMatch,
    ]);
  }


  /**
   * @Route(
   * "/matchs/listMatchsLongTournament/{year}/{maxpage}/{page}", 
   * name="matchs_list_longtournament", 
   * requirements={
   *   "year"="\d+", 
   *   "maxpage"="\d+", 
   *   "page"="\d+" 
   * })
   */
  public function listMatchsLongTournament($year, $maxpage, $page, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $context="";

    if ($year=="2021") $context="Longformat tournament ".$year;
    else {
      $context="Summer tournament ".$year;
    }
    $dql   = 'SELECT m FROM App:Matchs m WHERE m.context LIKE :context ORDER BY m.date DESC';
    $query = $em->createQuery($dql)
            ->setParameter('context', $context."%");;

    $paginator  = $this->get('knp_paginator');

    $listMatchs = $paginator->paginate(
      $query, 
      $request->query->getInt('page', 1),
      $maxpage
    );

    return $this->render('site/matchs_list_longtournament.html.twig', array("listMatchs" => $listMatchs,
      'year' => $year
    ));
    
  }

  /**
   * @Route(
   * "/matchs/listMatchsDivisionLeague/{round}/{maxpage}/{page}", 
   * name="matchs_list_divisionleague", 
   * requirements={
   *   "round"="\d+", 
   *   "maxpage"="\d+", 
   *   "page"="\d+" 
   * })
   */
  public function listMatchsDivisionLeague($round, $maxpage, $page, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $dql   = 'SELECT m FROM App:Matchs m WHERE m.context = :context ORDER BY m.date DESC';
    $query = $em->createQuery($dql)
            ->setParameter('context', "Division League - Round#".$round);;

    $paginator  = $this->get('knp_paginator');

    $listMatchs = $paginator->paginate(
      $query, 
      $request->query->getInt('page', 1),
      $maxpage
    );

    return $this->render('site/matchs_list_divisionleague.html.twig', array("listMatchs" => $listMatchs,
      'round' => $round
    ));
    
  }


}
