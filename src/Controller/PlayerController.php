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

    $player = $em->getRepository('App:Player')->findOneBy(array('id'=>$id));
    $lastR = $em->getRepository('App:Player')->getLastRanking($id);

    $arrStatsOpponents=array();
    $arrStatsMatchs=array();
    $arrStatsOpponentsDetails=array();

    if ($player) {
      $sql = '
        SELECT idplayer1, idplayer2, tie FROM Matchs, Player P1, Player P2
        WHERE idplayer1=P1.id
        AND idplayer2=P2.id
        AND (idplayer1 = :idPlayer OR idplayer2 = :idPlayer)
        ORDER BY idplayer1, idplayer2
        ';
      $stmt = $em->getConnection()->prepare($sql);
      $stmt->execute(['idPlayer' => $id]);
      $opponents = $stmt->fetchAll();

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

        $arrStatsMatchs[$oppId]["nbM"]++;

        if ($opp["tie"]==1) $arrStatsMatchs[$oppId]["nbT"]++;
        else {
          if ($opp["idplayer1"]==$id) {
            $arrStatsMatchs[$oppId]["nbW"]++;
            $arrStatsMatchs[$oppId]["sold"]++;
          }
          else {
            $arrStatsMatchs[$oppId]["nbD"]++;
            $arrStatsMatchs[$oppId]["sold"]--;
          }
        }

      }
      
      // sorting array
      // if (count($arrStatsOpponents)>0) {
      //   arsort($arrStatsOpponents);
      // }

      foreach ($arrStatsMatchs as $idP => $data) {
        $dataPlayer = $em->getRepository('App:Player')->findOneBy(array('id'=>$idP));

        $arrStatsOpponentsDetails[$idP]["name"]=$dataPlayer->getNameLong();
        $arrStatsOpponentsDetails[$idP]["id"]=$idP;
        $arrStatsOpponentsDetails[$idP]["nbM"]=$data["nbM"];
        $arrStatsOpponentsDetails[$idP]["nbW"]=$data["nbW"];
        $arrStatsOpponentsDetails[$idP]["nbD"]=$data["nbD"];
        $arrStatsOpponentsDetails[$idP]["nbT"]=$data["nbT"];
        $arrStatsOpponentsDetails[$idP]["sold"]=$data["sold"];

      }

      
    }
    else {

      $request->getSession()->getFlashBag()->add('error', 'Error selecting rankings ('.$id.')');
    }
    
    return $this->render('site/player_view.html.twig', [
      'controller_name' => 'PlayerController',
      'player' => $player,
      'lastR' => $lastR,
      'arrStatsOpponents' => $arrStatsOpponentsDetails,
      'nbMTot' => $nbMTot,
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

    $player = $em->getRepository('App:Player')->findOneBy(array('id'=>$id));
    $lastR = $em->getRepository('App:Player')->getLastRanking($id);

    $where="WHERE (m.idplayer1= ".$id." OR m.idplayer2= ".$id.")";
    $maxpage=50;
    $listTotMatchs = $em->getRepository('App:Matchs')->getMatchsPerPageByPlayer($page, $maxpage, $id);

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

    // for each match, we calculate the points evolution
    $sql_m   = 'SELECT m.id, m.date, m.tie, p1.id AS p1id, p2.id AS p2id, p1.initialRating AS p1IR, p2.initialRating AS p2IR 
                  FROM Matchs m, Player p1, Player p2
                  '.$where." 
                  AND p1.id=m.idplayer1
                  AND p2.id=m.idplayer2
                  ORDER BY m.date DESC";
    $stmt = $em->getConnection()->prepare($sql_m);
    $stmt->execute();
    $matches = $stmt->fetchAll();

    $arrMEvol=array();

    foreach ($matches as $mat) {

      $rankId="";

      // get the closest ranking
      $sql_rank = 'SELECT id FROM Ranking WHERE date<"'.$mat["date"].'" ORDER BY date DESC LIMIT 0,1';
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
        elseif ($result==2) {
          $elo->addResult(2,1);
          $match = "Player 2 defeats Player 1";
          $result="player2";
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

            if ($idP==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[1];
          }
          elseif ($exp[0]==2) {
            $evol=$val-$rating_player2;
            if ($evol>0) $arrRt[2]="+".number_format($evol, 1);
            else $arrRt[2]=number_format($evol, 1);

            if ($idP==$idPFin) $arrMEvol[$mat["id"]]=$arrRt[2];
          }
          
        }
      
      }

    }

    return $this->render('site/player_view_matches.html.twig', array("listMatchs" => $listMatchs,
      'player' => $player,
      'lastR' => $lastR,
      'maxpage' => $maxpage,
      'nbPages' => $nbPages,
      'page' => $page,
      'arrMEvol' => $arrMEvol
    ));
    
    // return $this->render('site/player_view_matches.html.twig', [
    //   'controller_name' => 'PlayerController',
    //   'player' => $player,
    //   'lastR' => $lastR,
    //   'arrStatsMatchs' => $arrStatsMatchs,
    // ]);
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

    $player = $em->getRepository('App:Player')->findOneBy(array('id'=>$id));
    $lastR = $em->getRepository('App:Player')->getLastRanking($id);

    $rankingScores = $em->getRepository('App:Rankingpos')->findBy(array('idplayer'=>$id), array('idranking' => 'ASC'));

    $arrRS=array();


    foreach ($rankingScores as $key=>$rs) {
      // add the initial rankings on the first loop
      if ($key==0) {
        $date_1week = new \DateTime($rs->getIdRanking()->getDate()->format("Y-m-d"));
        // initial rankings
        $arrRS[$date_1week->modify('-7 day')->format("Y-m-d")] = $player->getInitialRating();
      }
      $arrRS[$rs->getIdRanking()->getDate()->format("Y-m-d")]=$rs->getScore();
    }
    // sorting per index
    ksort($arrRS);


    return $this->render('site/player_view_evolution.html.twig', [
      'controller_name' => 'PlayerController',
      'player' => $player,
      'lastR' => $lastR,
      'arrRS' => $arrRS,
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

    $listTotPlayers = $em->getRepository('App:Player')->getPlayersPerPage($page, $maxpage, $filter);

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
    ->add('level', TextType::class, array(
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
    ->add('initialRating', TextType::class, array(
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
    $player = $em->getRepository('App:Player')->findOneBy(['id' => $id]);

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
}
