<?php

namespace App\Controller;

use \Symfony\Component\Form\Extension\Core\Type\FormType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use \Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Component\Form\Extension\Core\Type\DateType;
use \Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use \Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use App\Entity\Rabbit;
use App\Entity\Player;


class RabbitController extends Controller
{

  function getFormRabbit($rabbit, $mode) {

    $formBuilder = $this->createFormBuilder($rabbit);

    $formBuilder
    ->add('namerabbit', TextType::class, array(
      'label'    => 'Name',
      'required' => false,
    ))
    ->add('nbpts', IntegerType::class, array(
      'label'    => 'Nb Pts',
      'required' => true,
    ))
    ->add('isover', CheckboxType::class, array(
      'label'    => 'Over?',
      'required' => false,
    ))
    ;

    $formBuilder
    ->add('idplayerfirst', EntityType::class, array(
      'label'   => 'First holder',
      'class' => Player::class,
      'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('p')
            ->orderBy('p.nameshort', 'ASC');
      },
      'choice_label' => 'nameshort',
    ))
    ->add('idplayerlast', EntityType::class, array(
      'label'   => 'Last holder',
      'class' => Player::class,
      'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('p')
            ->orderBy('p.nameshort', 'ASC');
      },
      'choice_label' => 'nameshort',
      'empty_data' => (isset($playerDefault) ? $playerDefault : null)
    ));


    if ($mode=="edit") {
      $formBuilder
      ->add('datecreated', DateTimeType::class, array(
        'label'    => 'Date created',
        'required' => true,
      ))
      ->add('dateupdated', DateTimeType::class, array(
        'label'    => 'Date updated',
        'data' => new \DateTime(),
        'required' => true,
      ))
      ->add('update', SubmitType::class, array('label' => 'Update'))
      ;
    }
    else {
      $formBuilder
      ->add('datecreated', DateTimeType::class, array(
        'label'    => 'Date created',
        'data' => new \DateTime(),
        'required' => true,
      ))
      ->add('dateupdated', DateTimeType::class, array(
        'label'    => 'Date updated',
        'data' => new \DateTime(),
        'required' => true,
      ))
      ->add("Create", SubmitType::class);
    }

    $form = $formBuilder->getForm();

    return $form;
  }

  /**
   * @Route(
   * "/rabbit/new", 
   * name="rabbit_new")
   */
  public function new(Request $request)
  {
    if ($this->getUser()!== NULL && in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {

      $em = $this->getDoctrine()->getManager();
      
      $rabbit = new Rabbit();
      
      $form = $this->getFormRabbit($rabbit, "add");

      if ($request->isMethod('POST')) {

        $form->handleRequest($request);

        if ($form->isValid()) {

          $em->persist($rabbit);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success', 'Rabbit created.');

        }
      }

      return $this->render('site/rabbit_form.html.twig', array(
        'form' => $form->createView(),
        'form_title' => "New rabbit",
      ));

    }

    else {
      $request->getSession()->getFlashBag()->add('error', 'Only for ADMINS.');

      return $this->redirectToRoute('homepage');
    }
    
  }


  /**
   * @Route(
   * "/rabbit/update/{id}", 
   * name="rabbit_update", 
   * requirements={
   *   "id"="\d+" 
   * })
   */
  public function edit($id, Request $request) {

    if ($this->getUser()!== NULL && in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {

      $em = $this->getDoctrine()->getManager();
      $rabbit = $em->getRepository('App\Entity\Rabbit')->findOneBy(['id' => $id]);

      $form=$this->getFormRabbit($rabbit, "edit");

      if ($request->isMethod('POST')) {

        $form->handleRequest($request);

        if ($form->isValid()) {

          $em->persist($rabbit);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success', 'Rabbit updated.');

        }
      }

      return $this->render('site/rabbit_form.html.twig', array(
        'form' => $form->createView(),
        'form_title' => "Update rabbit",
      ));
    }
    else {
      $request->getSession()->getFlashBag()->add('error', 'Only for ADMINS.');

      return $this->redirectToRoute('homepage');
    }
  }

  /**
   * @Route(
   * "/rabbit/list", 
   * name="rabbit_list", 
   * requirements={
   *   "page"="\d+" 
   * })
   */
  public function list(Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $listRabbits = $em->getRepository('App\Entity\Rabbit')->findBy(array(), array('id' => 'ASC'));

    return $this->render('site/rabbit_list.html.twig', array("listRabbits" => $listRabbits
    ));
    
  }


}
