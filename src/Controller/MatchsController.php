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
      if ($request->query->get('filter') && strlen($request->query->get('filter'))>2 ) {
        $filter=$request->query->get('filter');
        $where = " WHERE c.nameshort LIKE '%".$filter."%' OR p.namelong LIKE '%".$filter."%' OR p.email LIKE '%".$filter."%' ";
      }
    }

    $listTotMatchs = $em->getRepository('App:Matchs')->getMatchsPerPage($page, $maxpage, $filter);

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

    return $this->render('site/matchs_list.html.twig', array("listMatchs" => $listMatchs,
      'maxpage' => $maxpage,
      'nbPages' => $nbPages,
      'page' => $page,
      'filter' => $filter
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
      'class' => Player::class,
      'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('p')
            ->where('p.active=1')
            ->orderBy('p.nameshort', 'ASC');
      },
      'choice_label' => 'nameshort',
    ))
    ->add('idplayer2', EntityType::class, array(
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
      'choices' => array("Stege (söndag 21-22)" => "Stege (söndag 21-22)", "Stege" => "Stege", "A-serien" => "A-serien"),
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
}
