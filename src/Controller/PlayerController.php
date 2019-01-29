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

    $arrStatsOpponents=array();
    $arrStatsOpponentsDetails=array();

    if ($player) {
      $sql = '
        SELECT idplayer1, idplayer2 FROM Matchs, Player P1, Player P2
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

        if (isset($arrStatsOpponents[$oppId])) {
          $arrStatsOpponents[$oppId]++;
        }
        else {
          $arrStatsOpponents[$oppId]=1;
        }
      }

      // sorting array
      if (count($arrStatsOpponents)>0) {
        arsort($arrStatsOpponents);
      }

      foreach ($arrStatsOpponents as $idP => $nbM) {
        $dataPlayer = $em->getRepository('App:Player')->findOneBy(array('id'=>$idP));
        $arrStatsOpponentsDetails[$idP]["name"]=$dataPlayer->getNameLong();
        $arrStatsOpponentsDetails[$idP]["id"]=$idP;
        $arrStatsOpponentsDetails[$idP]["nbM"]=$nbM;

      }
    }
    else {

      $request->getSession()->getFlashBag()->add('error', 'Error selecting rankings ('.$id.')');
    }
    
    return $this->render('site/player_view.html.twig', [
      'controller_name' => 'PlayerController',
      'player' => $player,
      'arrStatsOpponents' => $arrStatsOpponentsDetails,
      'nbMTot' => $nbMTot,
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
      'required'   => false,
    ))
    ->add('username', TextType::class, array(
      'required'   => false,
    ))
    ->add('password', TextType::class, array(
      'required'   => false,
    ))
    ->add('roles', ChoiceType::class, array(
      'multiple' => true,
      'choices' => array(
        'ROLE_USER' => 'ROLE_USER',
        'ROLE_ADMIN' => 'ROLE_ADMIN',
      ),
      'required'   => false,
    ))
    ;

    // $form = $formBuilder->getForm();

    if ($mode=="edit") {
      $formBuilder
      ->add('update', SubmitType::class, array('label' => 'Update'))
      ;
    }
    else {
      $formBuilder
      ->add('password', TextType::class, array(
        'required'   => true,
      ))
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

        $rolesString=implode(",", $form->get('roles')->getData());
        $player->setRoles($rolesString);
        $player->setPassword(password_hash($form->get('password')->getData(), PASSWORD_DEFAULT));

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
          $rolesString=implode(",", $form->get('roles')->getData());
          $player->setRoles($rolesString);

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
