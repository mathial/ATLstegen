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

use App\Entity\User;

class UserController extends Controller
{
  
  /**
   * @Route(
   * "/users/list", 
   * name="user_list"
   * )
   */
  public function list(Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $listUsers = $em->getRepository('App:User')->findAll();

    return $this->render('site/user_list.html.twig', array("listUsers" => $listUsers
    ));
    
  }


  function getFormUser($user, $mode) {

    // $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);
    $formBuilder = $this->createFormBuilder($user);

    $formBuilder
      ->add('username', TextType::class, array(
        'required'   => true,
      ))
      ->add('password', TextType::class, array(
        'required'   => true,
      ))
      ->add('roles', ChoiceType::class, array(
        'multiple' => true,
        'choices' => array(
          'ROLE_USER' => 'ROLE_USER',
          'ROLE_ADMIN' => 'ROLE_ADMIN',
        ),
        'required'   => true,
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
   * "/users/new", 
   * name="user_new"
   * )
   */
  public function new(Request $request) {

    $em = $this->getDoctrine()->getManager();
    $user = new User();

    /*$formBuilder = $this->createFormBuilder($user);

    $formBuilder
      ->add('username', TextType::class, array(
        'required'   => true,
      ))
      ->add('password', TextType::class, array(
        'required'   => true,
      ))
      ->add('roles', ChoiceType::class, array(
        'multiple' => true,
        'choices' => array(
          'ROLE_USER' => 'ROLE_USER',
          'ROLE_ADMIN' => 'ROLE_ADMIN',
        ),
        'required'   => true,
      ))
      ->add("Create", SubmitType::class);
    ;

    $form = $formBuilder->getForm();
    */

    $form=$this->getFormUser($user, "add");

    if ($request->isMethod('POST')) {

      $form->handleRequest($request);

      if ($form->isValid()) {

        // $rolesString=implode(",", $form->get('roles')->getData());
        // $user->setRoles($rolesString);
        $user->setPassword(password_hash($form->get('password')->getData(), PASSWORD_DEFAULT));

        try {
          $em->persist($user);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success', 'User created.');

          return $this->redirectToRoute('user_list');
        }
        catch(UniqueConstraintViolationException $e) {
          $form->get('username')->addError(new  \Symfony\Component\Form\FormError(
            "This username already exists in the database"
          ));

          // return $this->render('site/player_view.html.twig', array(
          //   'form' => $form->createView(),
          // ));
        }

      }
    }

    return $this->render('site/user_form.html.twig', array(
      'form' => $form->createView(),
      'form_title' => 'Form new user'
    ));

  }

  /**
   * @Route(
   * "/users/update/{id}", 
   * name="user_update", 
   * requirements={
   *   "id"="\d+" 
   * })
   */
  public function updateUserAction($id, Request $request) {

    $em = $this->getDoctrine()->getManager();
    $user = $em->getRepository('App:User')->findOneBy(['id' => $id]);

    $form=$this->getFormUser($user, "edit");

    if ($request->isMethod('POST')) {

      $form->handleRequest($request);

      if ($form->isValid()) {

        $user->setPassword(password_hash($form->get('password')->getData(), PASSWORD_DEFAULT));

        try {
          // $rolesString=implode(",", $form->get('roles')->getData());
          // $user->setRoles($rolesString);

          $em->persist($user);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success', 'User updated.');

        } 
        catch(UniqueConstraintViolationException $e) {
          $form->get('username')->addError(new  \Symfony\Component\Form\FormError(
            "This username already exists in the database"
          ));

        }
      }
    }

    return $this->render('site/user_form.html.twig', array(
      'form' => $form->createView(),
      'form_title' => 'Update user'
    ));
  }

  /**
   * @Route(
   * "/users/changepassword", 
   * name="change_password")
   */

  public function changePasswordAction(Request $request) {

    $em = $this->getDoctrine()->getManager();
    //$user = $em->getRepository('App:User')->findOneBy(['id' => $id]);
    $user = $this->get('security.token_storage')->getToken()->getUser();

    $formBuilder = $this->createFormBuilder($user);

    $formBuilder
      ->add('password', PasswordType::class, array(
        'required'   => true,
      ))
      ->add('update', SubmitType::class, array('label' => 'Change'))
    ;

    $form = $formBuilder->getForm();

    if ($request->isMethod('POST')) {

      $form->handleRequest($request);

      if ($form->isValid()) {

        $user->setPassword(password_hash($form->get('password')->getData(), PASSWORD_DEFAULT));

        try {
          // $rolesString=implode(",", $form->get('roles')->getData());
          // $user->setRoles($rolesString);

          $em->persist($user);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success', 'Password updated.');

        } 
        catch(UniqueConstraintViolationException $e) {
          $form->get('username')->addError(new  \Symfony\Component\Form\FormError(
            "This username already exists in the database"
          ));

        }
      }
    }

    return $this->render('site/user_form.html.twig', array(
      'form' => $form->createView(),
      'form_title' => 'Change my password'
    ));
  }

}
