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

  function getContexts() {
    $contexts=array(
      "all" => "all", 
      "stege" => "Stege", 
      "stegesondag2122" => "Stege (söndag 21-22)", 
      "divisionleague" => "Division League %", 
      "aserien" => "A-serien", 
      "lbtkmandagstennis" => "LBTK-Måndagstennis", 
      "summertournaments" => "Summer tournament %",
      "atlklubbmasterskap" => "ATL Klubbmästerskap",
      "generationMatchup" => "Generation Matchup",
      "svenskaTennisligan" => "Svenska Tennisligan",
      "sprinttennistournament" => "Sprinttennis tournament" 
    );
    return $contexts;
  }

  function getConditions() {
    $conditions=array(
      "all" => "all", 
      "hardindoor" => "hard indoor", 
      "clayoutdoor" => "clay outdoor"
    );
    return $conditions;
  }

  function getFormStats($stat, $scdparam) {

    $formBuilder = $this->createFormBuilder();

    $list2ndParam=array();
    if ($stat=="nbmatchscontext") {
      $label = "Context";
      $list2ndParam=$this->getContexts();
    }
    elseif ($stat=="nbmatchscondition") {
      $label = "Context";
      $list2ndParam=$this->getConditions();
    }

    $formBuilder
    ->add('stat', ChoiceType::class, array(
      'placeholder' => 'Pick an item in the list!',
      'label'    => '',
      'choices' => array(
        "Number of matches played per players (context)" => "nbmatchscontext", 
        "Number of matches played per players (conditions)" => "nbmatchscondition", 
      ),
      'required'   => true,
      'data' => $stat
    ))
    ->add('scdparam', ChoiceType::class, [
      'label'    => $label,
      'choices' => array_combine(array_values($list2ndParam), array_keys($list2ndParam)),
      'data' => $scdparam
    ])
    ->add('go', SubmitType::class, array('label' => 'Go!'));

    $form = $formBuilder->getForm();

    return $form;

  }

  /**
   * @Route(
   * "/stats/{stat}/{scdparam}", 
   * name="stats_tennis", 
   * requirements={
   *   "stat"="[A-Za-z0-9\-]+", 
   *   "scdparam"="[A-Za-z0-9\-]+"
   * })
   */
  public function calculateStats($stat, $scdparam, Request $request)
  {
    if (is_null($stat)) 
      $stat="nbmatchscontext";
    if (is_null($scdparam)) 
      $scdparam="all";

    $maxpage=100;
    $desc="";

    if ($stat=="nbmatchscontext")
      $list2ndParam=$this->getContexts();
    elseif ($stat=="nbmatchscondition")
      $list2ndParam=$this->getConditions();

    $form = $this->getFormStats($stat, $scdparam);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $data = $form->getData();

      $stat=$data['stat'];
      $scdparam=$data['scdparam'];

      $url = $this->generateUrl('stats_tennis', array('stat' => $stat, 'scdparam' => $scdparam));
      return $this->redirect($url);
    } 

    $desc="";
    $where="";

    if ($stat=="nbmatchscontext")  {
      $desc="Players who played the most single matches through history. You can pick the context.";
      if ($scdparam!="all")
        $where=" WHERE context LIKE '".$list2ndParam[$scdparam]."'";
    }
    elseif ($stat=="nbmatchscondition" && $scdparam!="all") {
      $desc="Players who played the most single matches through history. You can pick the conditions.";
      if ($scdparam!="all")
        $where=" WHERE conditions LIKE '".$list2ndParam[$scdparam]."'";
    }

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
    //echo $sql;
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


    return $this->render('site/stats_tennis.html.twig', 
        array(
          'form' => $form->createView(),
          "stat" => $stat, 
          "desc" => $desc, 
          "scdparam" => $scdparam, 
          "years" => $years, 
          "playersStat" => $playersRecapNbMatch,
          "playersStatSort" => $playersRecapNbMatchTotal
        )
      );
    
  }

}
