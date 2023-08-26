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

use App\Entity\Matchspaddle;
use App\Entity\Player;
use App\Entity\EloRatingSystem;
use App\Entity\EloCompetitor;

//email
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class MatchspaddleController extends Controller
{
    /**
     * @Route("/matchspadel", name="matchspadel")
     */
    public function index()
    {
        return $this->render('matchspadel/index.html.twig', [
            'controller_name' => 'MatchspaddleController',
        ]);
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
        'label'   => 'Winning team - player1',
        'class' => Player::class,
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('p')
              ->orderBy('p.nameshort', 'ASC');
        },
        'choice_label' => 'nameshort',
        'data' => $playerDefault
      ))
      ->add('idplayer2', EntityType::class, array(
        'label'   => 'Winning team - player2',
        'class' => Player::class,
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('p')
              ->orderBy('p.nameshort', 'ASC');
        },
        'choice_label' => 'nameshort',
        'data' => $playerDefault
      ))
      ->add('idplayer3', EntityType::class, array(
        'label'   => '2nd team - player3',
        'class' => Player::class,
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('p')
              ->orderBy('p.nameshort', 'ASC');
        },
        'choice_label' => 'nameshort',
        'data' => $playerDefault
      ))
      ->add('idplayer4', EntityType::class, array(
        'label'   => '2nd team - player4',
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
        'label'   => 'Winning team - player1',
        'class' => Player::class,
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('p')
              ->orderBy('p.nameshort', 'ASC');
        },
        'choice_label' => 'nameshort',
      ))
      ->add('idplayer2', EntityType::class, array(
        'label'   => 'Winning team - player2',
        'class' => Player::class,
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('p')
              ->orderBy('p.nameshort', 'ASC');
        },
        'choice_label' => 'nameshort',
      ))
      ->add('idplayer3', EntityType::class, array(
        'label'   => '2nd team - player3',
        'class' => Player::class,
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('p')
              ->orderBy('p.nameshort', 'ASC');
        },
        'choice_label' => 'nameshort',
      ))
      ->add('idplayer4', EntityType::class, array(
        'label'   => '2nd team - player4',
        'class' => Player::class,
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('p')
              ->orderBy('p.nameshort', 'ASC');
        },
        'choice_label' => 'nameshort',
      ));
    }

    $formBuilder
    ->add('context', ChoiceType::class, array(
      'label'    => 'Context',
      'choices' => array("Stege" => "Stege", "Vinnarbana" => "Vinnarbana", "ATLs Padelstege" => "ATLs Padelstege", "PDL serier" => "PDL serier"),
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
      'choices' => array("indoor" => "indoor", "outdoor" => "outdoor"),
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
   * "/matchspadel/new", 
   * name="matchspadel_new")
   */
  public function new(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    
    $match = new Matchspaddle();
    
    $form = $this->getFormMatch($match, "add");

    if ($request->isMethod('POST')) {

      $form->handleRequest($request);

      if ($form->isValid()) {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $match->setIduseradd($user);

        $em->persist($match);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Match created.');

        $emailSubject="[ATL-St.] padel - ".$user->getUsername();

        $emailContent=''.$match->getIdplayer1()->getNameShort().'-'.$match->getIdplayer2()->getNameShort().' VS '. $match->getIdplayer3()->getNameShort().'-'.$match->getIdplayer4()->getNameShort() .' : '.$match->getScore();
        $emailContent.=($match->getTie()==1 ? ' (TIE)' : "");
        $emailContent.='<br>'.$match->getConditions().' - '.$match->getContext();

        // Create a Transport object
        $transport = Transport::fromDsn($_SERVER['MAILER_DSN']);
        // Create a Mailer object
        $mailer = new Mailer($transport); 
        // Create an Email object
        $email = (new Email());
        // Set the "From address"
        $email->from($_SERVER['EMAIL_ADMIN']);
        // Set the "From address"
        $email->to('mathieu@isomail.org');
        $email->cc($_SERVER['EMAIL_ADMIN']);
        // Set a "subject"
        $email->subject($emailSubject);
        // Set the plain-text "Body"
        $email->html($emailContent);

        try {
          $mailer->send($email);
        } catch (Exception $e) {
            // some error prevented the email sending; display an
            // error message or try to resend the message
            $request->getSession()->getFlashBag()->add('error', 'Error sending email to '.$_SERVER['EMAIL_ADMIN']);

        }

      }
    }

    return $this->render('site/matchs_form.html.twig', array(
      'form' => $form->createView(),
      'form_title' => "New match",
      'type_match' => "Padel"
    ));
    
  }


  /**
   * @Route(
   * "/matchspadel/update/{id}", 
   * name="matchpadel_update", 
   * requirements={
   *   "id"="\d+" 
   * })
   */
  public function edit($id, Request $request) {

    $em = $this->getDoctrine()->getManager();
    $match = $em->getRepository('App\Entity\Matchspaddle')->findOneBy(['id' => $id]);

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
      'type_match' => "Padel"
    ));
    
  }


  /**
   * @Route(
   * "/matchspadel/delete/{id}", 
   * name="matchpadel_delete", 
   * requirements={
   *   "id"="\d+" 
   * })
   */
  public function delete($id, Request $request) {

    if ($this->getUser()!== NULL && in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {

        $em = $this->getDoctrine()->getManager();
        $match = $em->getRepository('App\Entity\Matchspaddle')->findOneBy(['id' => $id]);

        $em->remove($match);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'Match '.$id.' deleted.');
    }
    else {
        $request->getSession()->getFlashBag()->add('error', 'Only for ADMINS.');
    }

    return $this->redirectToRoute('matchspadel_list', array('maxpage' =>50, 'page'=>1));
    
  }

    /**
   * @Route(
   * "/matchspadel/list/{maxpage}/{page}", 
   * name="matchspadel_list", 
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

    $listTotMatchs = $em->getRepository('App\Entity\Matchspaddle')->getMatchsPerPage($page, $maxpage, $filter);

    $dql   = "SELECT m FROM App:Matchspaddle m ".$where." ORDER BY m.date DESC, m.id DESC";
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
    $sql_m   = 'SELECT m.id, m.date, m.tie, p1.id AS p1id, p2.id AS p2id, p1.initialRatingPaddle AS p1IR, p2.initialRatingPaddle AS p2IR, p3.id AS p3id, p4.id AS p4id, p3.initialRatingPaddle AS p3IR, p4.initialRatingPaddle AS p4IR 
                  FROM MatchsPaddle m, Player p1, Player p2, Player p3, Player p4
                  '.($where!="" ? $where : " WHERE 1 ")." 
                  AND p1.id=m.idplayer1
                  AND p2.id=m.idplayer2
                  AND p3.id=m.idplayer3
                  AND p4.id=m.idplayer4
                  ORDER BY m.date DESC, m.id DESC
                  LIMIT ".($page-1)*$limitpage.", ".$limitpage;              
    $stmt = $em->getConnection()->prepare($sql_m);
    $exec = $stmt->execute();
    $matches = $exec->fetchAll();

    $arrMEvol=array();

    foreach ($matches as $mat) {

      $rankId="";

      // get the closest ranking
      $sql_rank = 'SELECT id FROM RankingPaddle WHERE date<="'.$mat["date"].'" ORDER BY date DESC LIMIT 0,1';
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

     /* if ($id==$mat["p1id"]) $idPFin=1;
      else $idPFin=2;*/

      if (isset($rating_player1) && is_numeric($rating_player1) && isset($rating_player2) && is_numeric($rating_player2) && isset($rating_player3) && is_numeric($rating_player3) && isset($rating_player4) && is_numeric($rating_player4)) {

        $avg_teamA = ($rating_player1 + $rating_player2) / 2;
        $avg_teamB = ($rating_player3 + $rating_player4) / 2;

        $competitors = array(
          array('id' => 1, 'name' => "Team A", 'skill' => 100, 'rating' => $avg_teamA, 'active' => 1),
          array('id' => 2, 'name' => "Team B", 'skill' => 100, 'rating' => $avg_teamB, 'active' => 1),
        );
        //  initialize the ranking system and add the competitors
        $elo = new EloRatingSystem(100, 50);
        foreach ($competitors as $competitor) {
          $elo->addCompetitor(new EloCompetitor($competitor['id'], $competitor['name'], $competitor['rating']));
        }

        if ($result==1) {
          $elo->addResult(1,2);
          $match = "Team A defeats Team B";
        }
        else {
          $elo->addResult(1,2, true);
          $match = "TIE Team A - Team B";
        }

        $elo->updateRatings();

        $tabRank = $elo->getRankings();

        foreach ($tabRank as $idP => $val) {

          $exp=explode("#", $idP);
          if ($exp[0]==1) {
            $evol=$val-$avg_teamA;
            if ($evol>0) $arrRt[1]="+".number_format($evol, 1);
            else $arrRt[1]=number_format($evol, 1);
          }
          
          $arrMEvol[$mat["id"]]=$arrRt[1];
        }
      
      }

    }

    /* END POINTS EVOL PER MATCH */


    return $this->render('site/matchspaddle_list.html.twig', array("listMatchs" => $listMatchs,
      'maxpage' => $maxpage,
      'nbPages' => $nbPages,
      'page' => $page,
      'filter' => $filter,
      'arrMEvol' => $arrMEvol
    ));
    
  }
}
