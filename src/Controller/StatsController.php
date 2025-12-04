<?php

namespace App\Controller;

use \Symfony\Component\Form\Extension\Core\Type\FormType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use \Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use \Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Component\Form\Extension\Core\Type\NumberType;
use \Symfony\Component\Form\Extension\Core\Type\DateType;
use \Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use App\Entity\Matchs;
use App\Entity\Player;
use App\Entity\EloRatingSystem;
use App\Entity\EloCompetitor;
use App\Entity\Rabbit;


class StatsController extends Controller
{


  function getFormStats($stat, $year) {

    $formBuilder = $this->createFormBuilder();

    $years=array("all-time");
    for ($iY=2017;$iY<=date("Y");$iY++) {
      $years[]=$iY;
    }

    $formBuilder
    ->add('stat', ChoiceType::class, array(
      'placeholder' => 'Pick an item in the list!',
      'label'    => '',
      'choices' => array(
        "Number of matches played per players" => "nbmatchs", 
      ),
      'required'   => true,
      'data' => $stat
    ))
    ->add('year', ChoiceType::class, [
      'label'    => '',
      'choices' => array_combine($years, $years),
      'data' => $year
    ])
    ->add('go', SubmitType::class, array('label' => 'Go!'));

    $form = $formBuilder->getForm();

    return $form;

  }

  /**
   * @Route(
   * "/stats/{stat}/{year}", 
   * name="stats_tennis", 
   * requirements={
   *   "stat"="[A-Za-z0-9\-]+", 
   *   "year"="[A-Za-z0-9\-]+"
   * })
   */
  public function calculateStats($stat, $year, Request $request)
  {
    //if (is_null($stat)) 
      $stat="nbmatchs";
    if (is_null($year)) 
      $year="all-time";

    $maxpage=100;
    $desc="";
    
    $form = $this->getFormStats($stat, $year);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $data = $form->getData();

      $stat=$data['stat'];
      $year=$data['year'];

      $url = $this->generateUrl('stats_tennis', array('stat' => $stat, 'year' => $year));
      return $this->redirect($url);
    } 


    if ($stat=="nbmatchs"){
      $desc="Players who played the most matches through history. You can pick all-time (since 2017) or a specific year.";
      $where="";
      if ($year!="all-time" && is_numeric($year) && $year>=2010)
        $where=" WHERE YEAR(date)=".$year;

      $em = $this->getDoctrine()->getManager();

      // get all the matches played per year
      $sql   = 'SELECT SUM(nbM) as totM, idP, P.nameShort, yr from(
                  SELECT count(*) as nbM, idPlayer1 as idP, YEAR(date) as yr FROM `Matchs` '.$where.' GROUP BY idPlayer1, YEAR(date) 
                  union
                  SELECT count(*) as nbM, idPlayer2 as idP, YEAR(date) as yr FROM `Matchs` '.$where.' GROUP BY idPlayer2, YEAR(date) 
                ) as cm, Player P 
                WHERE cm.idP=P.id
                group by idP, yr
                ORDER BY idP  ';
                //LIMIT '.$maxpage;
      $stmt = $em->getConnection()->prepare($sql);

      $exec = $stmt->execute();
      $players = $exec->fetchAll();

      //print_r($players);exit();

      $years=array();
      for ($iY=2017;$iY<=date("Y");$iY++) {
        $years[]=$iY;
      }
      // last total column
      //$years[]="tot";

      $playersRecapNbMatch=array();
      $playersRecapNbMatchTotal=array();
      foreach($players as $pl) {
        if (!isset($playersRecapNbMatch[$pl["idP"]])) {
          $playersRecapNbMatch[$pl["idP"]]["tot"]=0;
          $playersRecapNbMatch[$pl["idP"]]["name"]=$pl["nameShort"];
          foreach ($years as $yr) 
            $playersRecapNbMatch[$pl["idP"]][$yr]=0;
        }

        $playersRecapNbMatch[$pl["idP"]][$pl["yr"]]=$pl["totM"];
        $playersRecapNbMatch[$pl["idP"]]["tot"]+=$pl["totM"];
        $playersRecapNbMatchTotal[$pl["idP"]]=$playersRecapNbMatch[$pl["idP"]]["tot"];
      }

      arsort($playersRecapNbMatchTotal);
      //print_r($playersRecapNbMatch);

    }



    return $this->render('site/stats_tennis.html.twig', 
        array(
          'form' => $form->createView(),
          "stat" => $stat, 
          "desc" => $desc, 
          "years" => $years, 
          "playersStat" => $playersRecapNbMatch,
          "playersStatSort" => $playersRecapNbMatchTotal
        )
      );
    
  }

}
