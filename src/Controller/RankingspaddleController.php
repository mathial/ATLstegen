<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use \Symfony\Component\Form\Extension\Core\Type\FormType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use \Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use \Symfony\Component\Form\Extension\Core\Type\DateType;

use App\Entity\EloRatingSystem;
use App\Entity\EloCompetitor;
use App\Entity\Player;
use App\Entity\Rankingpaddle;
use App\Entity\Rankingpospaddle;

class RankingspaddleController extends AbstractController
{
  /**
   * @Route("/rankingspadel", name="rankings_padel")
   */
  public function index()
  {
      return $this->render('rankings/index.html.twig', [
          'controller_name' => 'RankingspaddleController',
      ]);
  }

  public function calculateRankings ($em, $date_from, $generate_ranking, $based_ranking) {


  	$arrResults=array();
		$arrResults["playersDisplay"]=array();
		$arrResults["matchs"]=array();
		$arrResults["messages"]=array();


    if ($based_ranking!="init") {
    	$rankingpaddle = $em->getRepository('App\Entity\Rankingpaddle')->findOneBy(array("id" => $based_ranking));

    	if($generate_ranking==1) {

				// check if a ranking exist at that date
				$sql = '
			    SELECT * FROM RankingPaddle WHERE date = :date
			    ';
			  $stmt = $em->getConnection()->prepare($sql);
				$exec = $stmt->execute(['date' => $date_from->format("Y-m-d")]);
				if ($rankingExist = $exec->fetchAll()) {

					// delete all the pos 
					$sql = '
			    DELETE FROM RankingPosPaddle WHERE idRankingPaddle = :idR
			    ';
					$stmt = $em->getConnection()->prepare($sql);
					$nbDeletes = $stmt->execute(['idR' => $rankingExist[0]["id"]]);
					if ($nbDeletes->rowCount()>0) {
						//$request->getSession()->getFlashBag()->add('info',  $stmt->rowCount().' RankingPosPadel deleted ('.$date_from->format("Y-m-d").' // id#'.$rankingExist[0]["id"].').');
						$arrResults["messages"][]=[
							'type' => 'info',
							'msg' => $nbDeletes->rowCount().' RankingPosPadel deleted ('.$date_from->format("Y-m-d").' // id#'.$rankingExist[0]["id"].').'
						];
					}

					// and then delete the rankings
					$sql = '
			    DELETE FROM RankingPaddle WHERE id = :idR
			    ';
					$stmt = $em->getConnection()->prepare($sql);
					$nbDeletes = $stmt->execute(['idR' => $rankingExist[0]["id"]]);
					if ($nbDeletes->rowCount()>0) {
						//$request->getSession()->getFlashBag()->add('info', 'RankingPadel deleted ('.$date_from->format("Y-m-d").' // id#'.$rankingExist[0]["id"].').');
						$arrResults["messages"][]=[
							'type' => 'info',
							'msg' => 'RankingPadel deleted ('.$date_from->format("Y-m-d").' // id#'.$rankingExist[0]["id"].').'
						];
					}

				}
				
      }


    }

    $sql = '
	    SELECT DISTINCT idPlayer1 FROM MatchsPaddle WHERE date <= :date
	    ';
		$stmt = $em->getConnection()->prepare($sql);
		$exec = $stmt->execute(['date' => $date_from->format("Y-m-d")]);
		$players1 = $exec->fetchAll();
		
		$sql = '
	    SELECT DISTINCT idPlayer2 FROM MatchsPaddle WHERE date <= :date
	    ';
		$stmt = $em->getConnection()->prepare($sql);
		$exec = $stmt->execute(['date' => $date_from->format("Y-m-d")]);
		$players2 = $exec->fetchAll();

		$sql = '
	    SELECT DISTINCT idPlayer3 FROM MatchsPaddle WHERE date <= :date
	    ';
		$stmt = $em->getConnection()->prepare($sql);
		$exec = $stmt->execute(['date' => $date_from->format("Y-m-d")]);
		$players3 = $exec->fetchAll();

		$sql = '
	    SELECT DISTINCT idPlayer4 FROM MatchsPaddle WHERE date <= :date
	    ';
		$stmt = $em->getConnection()->prepare($sql);
		$exec = $stmt->execute(['date' => $date_from->format("Y-m-d")]);
		$players4 = $exec->fetchAll();
		
		
	  if ($based_ranking!="init") {
	  	$sql = ' SELECT id, idPlayer1, idPlayer2, idPlayer3, idPlayer4, tie FROM MatchsPaddle WHERE date < :date AND date>= :date_based ';
	  	$stmt = $em->getConnection()->prepare($sql);
			$exec = $stmt->execute(['date' => $date_from->format("Y-m-d"), 'date_based' => $rankingpaddle->getDate()->format("Y-m-d")]);
	  }
	  else {
	  	$sql = 'SELECT id, idPlayer1, idPlayer2, idPlayer3, idPlayer4, tie FROM MatchsPaddle WHERE date < :date';
	  	$stmt = $em->getConnection()->prepare($sql);
			$exec = $stmt->execute(['date' => $date_from->format("Y-m-d")]);
	  }
		
		$matchs = $exec->fetchAll();

		$arrPlayers=array();
		//$arrPlayersDisplay=array();
		$arrResults["playersDisplay"]=array();

		foreach ($players1 as $row) {
			$arrPlayers[]=$row["idPlayer1"];
		}
		foreach ($players2 as $row) {
			$arrPlayers[]=$row["idPlayer2"];
		}
		foreach ($players3 as $row) {
			$arrPlayers[]=$row["idPlayer3"];
		}
		foreach ($players4 as $row) {
			$arrPlayers[]=$row["idPlayer4"];
		}

		$elo = new EloRatingSystem(100, 50);

		foreach($arrPlayers as $pId) {
			$player = $em->getRepository('App\Entity\Player')->findOneBy(array("id" => $pId));
			$arrResults["playersDisplay"][$pId]=$player->getNameshort();

			// get the ranking expected
			if ($based_ranking=="init") {
				$basedRate[$pId]=$player->getInitialRatingPaddle();
			}
			else {
				$rankPos = $em->getRepository('App\Entity\Rankingpospaddle')->findOneBy(array("idrankingpaddle" => $based_ranking, "idplayer" => $player->getId()));
				if ($rankPos) {
					$basedRate[$pId] = $rankPos->getScore();
				}
				else {
					$basedRate[$pId] = $player->getInitialRatingPaddle();
					//echo "RIEN".$based_ranking."/".$player->getId()." - ".$basedRate[$pId]."<br>";
				}
			}

			$elo->addCompetitor(new EloCompetitor($player->getId(), $player->getNameshort(), $basedRate[$pId]));
		}
		
		foreach($matchs as $m) {
			if ($m["tie"]==1) $tie=true;
			else $tie=false;

			//$elo->addResultDouble($m["idPlayer1"], $m["idPlayer2"], $m["idPlayer3"], $m["idPlayer4"], $tie);

			$elo->addResultDouble(
    		array("id" => $m["idPlayer1"], "rating" => $basedRate[$m["idPlayer1"]]), 
    		array("id" => $m["idPlayer2"], "rating" => $basedRate[$m["idPlayer2"]]), 
    		array("id" => $m["idPlayer3"], "rating" => $basedRate[$m["idPlayer3"]]), 
    		array("id" => $m["idPlayer4"], "rating" => $basedRate[$m["idPlayer4"]]), 
    		$tie
    	);

		}

		$elo->updateRatings();

	  $tabRank = $elo->getRankings();

	  if ($generate_ranking==1) {

			$rankingpaddle=new Rankingpaddle();
			$rankingpaddle->setDate($date_from);
			$rankingpaddle->setDategeneration(new \DateTime(date("Y-m-d H:i:s")));

			$em->persist($rankingpaddle);
      $em->flush();
      //$request->getSession()->getFlashBag()->add('success', 'Rankingpaddle created');
      $arrResults["messages"][]=[
				'type' => 'success',
				'msg' => 'Rankingpaddle created'
			];
	  }

	  $iR = 0;
	  $oldR=0;
	  $pos=0;
	  foreach ($tabRank as $idName => $val) {
	  	$iR++;
	    $row=array();
	    $expl_rank=explode("#", $idName);

	    if ($oldR!=$val) {
	    	$pos=$iR;
	    }

	    $row["id"]=$expl_rank[0];
	    $row["rank"]=$pos;
	    $row["name"]=$expl_rank[1];
	    $row["rating"]=$val;

	    //$arrRankFinal[]=$row;
	    $arrResults["rankFinal"][]=$row;

	    if ($generate_ranking==1) {
	    	$rankingpaddle_pos = new Rankingpospaddle();
	    	$player = $em->getRepository('App\Entity\Player')->findOneBy(array("id" => $expl_rank[0]));
	    	
	    	$rankingpaddle_pos->setIdrankingpaddle($rankingpaddle);
	    	$rankingpaddle_pos->setIdplayer($player);
	    	$rankingpaddle_pos->setPosition($pos);
	    	$rankingpaddle_pos->setScore($val);

				$em->persist($rankingpaddle_pos);
	    }

	    $oldR=$val;
	  }

		if ($generate_ranking==1) {

			foreach($matchs as $m) {
				$match = $em->getRepository('App\Entity\Matchspaddle')->findOneBy(array("id" => $m["id"]));
				$match->setIdrankingpaddle($rankingpaddle->getId());
				$em->persist($match);
			}

     	$em->flush();
		}


		$arrResults["matchs"]=$matchs;

		return $arrResults;

	}



  /**
   * @Route("/rankingspadel/generate", name="rankings_generate_padel")
   */
  public function generate(Request $request)
  {
  	
	  $arrRankFinal = array();
	  $matchs = array();
		$date_from="";
		
		$em = $this->getDoctrine()->getManager();

		$arrRank=array();
		$rankings = $em->getRepository('App\Entity\Rankingpaddle')->findBy(array(), array('date' => 'DESC'));
		foreach ($rankings as $rank) {
			$arrRank[$rank->getDate()->format("Y-m-d")] = $rank->getId();
		}
		$arrRank["Initial Rankings"]='init';

		$defaultData = array('message' => 'Type your message here');
		$formBuilder = $this->createFormBuilder($defaultData);

		$arrResults=array();
		$arrResults["playersDisplay"]=array();
		$arrResults["matchs"]=array();
		$arrResults["rankFinal"]=array();
		$matchesWithoutRankingsFinal=array();
	  
	  $formBuilder
	  ->add('date_ranking', DateType::class, array(
	    'required'   => true,
	    'label'   => 'Date',
	    'format' => 'yyyy-MM-dd',
	    'widget' => 'single_text',
	  ))
	  ->add('based_ranking', ChoiceType::class, array(
      'choices' => $arrRank,
      'required'   => true,
    ))
	  ->add('generate_ranking', CheckboxType::class, array(
	    'label'    => 'Generate rankings',
	    'required' => false,
		))
	  ->add("Create", SubmitType::class);

	  $form = $formBuilder->getForm();

		$form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // data is an array with "name", "email", and "message" keys
      $data = $form->getData();

      $date_from=$data['date_ranking'];
      $generate_ranking=$data['generate_ranking'];
      $based_ranking=$data['based_ranking'];

      if ($based_ranking!="init") {
	      $rankingBase = $em->getRepository('App\Entity\Rankingpaddle')->findOneBy(array('id' => $based_ranking));
	      $based_ranking_date = $rankingBase->getDate()->format("Y-m-d");
      }


			$arrResults = $this->calculateRankings ($em, $date_from, $generate_ranking, $based_ranking);
      if (isset($arrResults["messages"])) {
      	foreach($arrResults["messages"] as $elt ) {
      		$request->getSession()->getFlashBag()->add($elt["type"], $elt["msg"]);
      	}
      }

			// check if there are matches without any ranking linked

      $matchesWithoutRankings = $em->getRepository('App\Entity\Matchspaddle')->getMatchesWithoutRankings($based_ranking_date);

      foreach($matchesWithoutRankings as $matW) {
      	$matchesWithoutRankingsFinal[]=array(
      		"id" => $matW->getId(),
      		"idPlayer1" => $matW->getIdplayer1()->getNameshort(),
      		"idPlayer2" => $matW->getIdplayer2()->getNameshort(),
      		"idPlayer3" => $matW->getIdplayer3()->getNameshort(),
      		"idPlayer4" => $matW->getIdplayer4()->getNameshort(),
      		"date" => $matW->getDate()->format("Y-m-d")
      	);
      }
    }

	  return $this->render('site/generate_rankings_paddle.html.twig', [
	    'controller_name' => 'RankingspaddleController',
	    'form' => $form->createView(),
	    'arrRankFinal' => $arrResults["rankFinal"],
	    'dateFrom' => $date_from,
	    'arrMatchs' => $arrResults["matchs"],
	    'arrPlayersDisplay' => $arrResults["playersDisplay"],
	    'arrMatchesWithoutRankings' => $matchesWithoutRankingsFinal
	  ]);
	}


	/**
   * @Route("/rankingspadel/view", name="rankings_padel_view", defaults={"id" = null, "AO" = 1})
   * @Route("/rankingspadel/view/{id}", name="rankings_padel_view_id", defaults={"AO" = 1})
   * @Route("/rankingspadel/view/{id}/{AO}", name="rankings_padel_view_id_ao")
   */
  public function viewRanking($id=null, $AO=1, Request $request)
  {	
  	$em = $this->getDoctrine()->getManager();

  	if ($id<>null) {
			$ranking = $em->getRepository('App\Entity\Rankingpaddle')->findOneBy(array('id' => $id));
		}
  	else {
  		$ranking = $em->getRepository('App\Entity\Rankingpaddle')->getLastRanking();
  	}

  	if ($id<>null) $defaultId=$id;
  	else $defaultId=$ranking->getId();

  	$activeOnly=$AO;
		$arrRank=array();
		$rankings = $em->getRepository('App\Entity\Rankingpaddle')->findBy(array(), array('date' => 'DESC'));
		foreach ($rankings as $rank) {
			$arrRank[$rank->getDate()->format("Y-m-d")] = $rank->getId();
		}

		$defaultData = array('message' => 'Type your message here');
		$formBuilder = $this->createFormBuilder($defaultData);

		$formBuilder
		->add('id_ranking', ChoiceType::class, array(
			'choices' => $arrRank,
		  'required' => true,
		  'data' => $defaultId,
		))
		->add('active_only', CheckboxType::class, array(
			'label' => "Only active players.",
		   'required' => false,
		   'data' => ($activeOnly==1 ? true : false)
		))
		->add("Select", SubmitType::class);

		$form = $formBuilder->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
		  $data = $form->getData();

      $idRanking=$data['id_ranking'];
      $activeOnly=(isset($data['active_only']) && $data['active_only']!="" ? $data['active_only'] : 0);

      $url = $this->generateUrl('rankings_padel_view_id_ao', array('id' => $idRanking, 'AO' => $activeOnly));
      return $this->redirect($url);
		}	
		/*elseif ($id<>null) {
			$ranking = $em->getRepository('App\Entity\Rankingpaddle')->findOneBy(array('id' => $id));
		}
  	else {
  		$ranking = $em->getRepository('App\Entity\Rankingpaddle')->getLastRanking();
  	}*/

		$ranking_1 =  $em->getRepository('App\Entity\Rankingpaddle')->getRankingBefore($ranking->getDate()->format("Y-m-d"));

  	$detailsRankings=$em->getRepository('App\Entity\Rankingpospaddle')->findBy(array('idrankingpaddle' => $ranking));

  	$detailsPlayer=array();
		$arrTotal=array();
  	$arrTotal["score"]=0;
  	$arrTotal["evolscore"]=0;
  	$arrTotal["evolpos"]=0;
  	$arrTotal["wins"]=0;
  	$arrTotal["defeats"]=0;
  	$arrTotal["ties"]=0;
  	$arrTotal["total"]=0;

  	if ($ranking && $detailsRankings) {

  		// SCORE EVOL
  		foreach ($detailsRankings as $det) {

  			$detailsRankings_1=$em->getRepository('App\Entity\Rankingpospaddle')->findOneBy(array('idrankingpaddle' => $ranking_1, "idplayer" => $det->getIdplayer()));
  			if ($detailsRankings_1) {
  				$evol = $det->getScore() - $detailsRankings_1->getScore();
  			}
  			else {
  				$evol=$det->getScore() - $det->getIdplayer()->getInitialRatingPaddle();
  			}

				if ($evol>0) $detailsPlayer[$det->getIdplayer()->getId()]["evol"]="+".number_format($evol, 0);
				else $detailsPlayer[$det->getIdplayer()->getId()]["evol"]=number_format($evol, 0);

				$arrTotal["evolscore"]+=$evol;
				$arrTotal["score"]+=$det->getScore();


				$sql_best = 'SELECT MAX(score) AS score FROM RankingPosPaddle RP, RankingPaddle R WHERE 
							R.id=RP.idRankingPaddle
							AND date<="'.$ranking->getDate()->format("Y-m-d").'" 
							AND idPlayer='.$det->getIdplayer()->getId();
			    $stmt = $em->getConnection()->prepare($sql_best);
			    $exec = $stmt->execute();
			    $best = $exec->fetchAll();
			    if (isset($best[0])) $best=$best[0]["score"];
			    else $best=0;

				// best rankings
				$detailsPlayer[$det->getIdplayer()->getId()]["best"]=$best;
				//getBestRating($det->getIdplayer()->getId(), $ranking->getDate()->format("Y-m-d"));
	  			
  		}	

	  	for ($iP=1;$iP<=2;$iP++) {
	  		// WINS P1 + P2
				$sql = '
		    	SELECT COUNT(*) AS tot, idPlayer'.$iP.' 
		    	FROM MatchsPaddle
		    	WHERE tie=0 AND date<:date
		    	GROUP BY idPlayer'.$iP.' 
			    ';
				$stmt = $em->getConnection()->prepare($sql);
				$exec = $stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
				$wins = $exec->fetchAll();

				foreach ($wins as $win) {
					if (!isset($detailsPlayer[$win["idPlayer".$iP]]["wins"]))
						$detailsPlayer[$win["idPlayer".$iP]]["wins"]=0;
					$detailsPlayer[$win["idPlayer".$iP]]["wins"]+=$win["tot"];

					// has maybe already some ties for previous loop
					if (!isset($detailsPlayer[$win["idPlayer".$iP]]["ties"])) 
						$detailsPlayer[$win["idPlayer".$iP]]["ties"]=0;

					$detailsPlayer[$win["idPlayer".$iP]]["defeats"]=0;

					$arrTotal["wins"]+=$win["tot"];

				}

				// TIES P1 + P2
				$sql = '
		    	SELECT COUNT(*) AS tot, idPlayer'.$iP.' 
		    	FROM MatchsPaddle 
		    	WHERE tie=1 AND date<:date
		    	GROUP BY idPlayer'.$iP.' 
			    ';
				$stmt = $em->getConnection()->prepare($sql);
				$exec = $stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
				$ties1 = $exec->fetchAll();

				foreach ($ties1 as $tie) {
					
					if (!isset($detailsPlayer[$tie["idPlayer".$iP]]["ties"]))
						$detailsPlayer[$tie["idPlayer".$iP]]["ties"]=0;

					$detailsPlayer[$tie["idPlayer".$iP]]["ties"]+=$tie["tot"];			

					if (!isset($detailsPlayer[$tie["idPlayer".$iP]]["wins"])) {
						$detailsPlayer[$tie["idPlayer".$iP]]["wins"]=0;
					}
					if (!isset($detailsPlayer[$tie["idPlayer".$iP]]["defeats"])) {
						$detailsPlayer[$tie["idPlayer".$iP]]["defeats"]=0;
					}

					$arrTotal["ties"]+=$tie["tot"];

				}

	  	}

	  	for ($iP=3;$iP<=4;$iP++) {
				// TIES P3+p4
				$sql = '
		    	SELECT COUNT(*) AS tot, idPlayer'.$iP.'
		    	FROM MatchsPaddle 
		    	WHERE tie=1 AND date<:date
		    	GROUP BY idPlayer'.$iP.' 
			    ';
				$stmt = $em->getConnection()->prepare($sql);
				$exec = $stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
				$ties1 = $exec->fetchAll();

				foreach ($ties1 as $tie) {
					
					if (!isset($detailsPlayer[$tie["idPlayer".$iP]]["ties"])) {
						$detailsPlayer[$tie["idPlayer".$iP]]["ties"]=$tie["tot"];			
					}
					else $detailsPlayer[$tie["idPlayer".$iP]]["ties"]+=$tie["tot"];

					if (!isset($detailsPlayer[$tie["idPlayer".$iP]]["wins"])) {
						$detailsPlayer[$tie["idPlayer".$iP]]["wins"]=0;
					}
					if (!isset($detailsPlayer[$tie["idPlayer".$iP]]["defeats"])) {
						$detailsPlayer[$tie["idPlayer".$iP]]["defeats"]=0;
					}

					$arrTotal["ties"]+=$tie["tot"];

				}

				// DEFEATS
				$sql = '
		    	SELECT COUNT(*) AS tot, idPlayer'.$iP.' 
		    	FROM MatchsPaddle 
		    	WHERE tie=0 AND date<:date
		    	GROUP BY idPlayer'.$iP.'
			    ';
				$stmt = $em->getConnection()->prepare($sql);
				$exec = $stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
				$defeats = $exec->fetchAll();

				foreach ($defeats as $defeat) {

					if (!isset($detailsPlayer[$defeat["idPlayer".$iP]]["defeats"]))
						$detailsPlayer[$defeat["idPlayer".$iP]]["defeats"]=0;

					$detailsPlayer[$defeat["idPlayer".$iP]]["defeats"]+=$defeat["tot"];
					
					if (!isset($detailsPlayer[$defeat["idPlayer".$iP]]["wins"])) {
						$detailsPlayer[$defeat["idPlayer".$iP]]["wins"]=0;
					}
					if (!isset($detailsPlayer[$defeat["idPlayer".$iP]]["ties"])) {
						$detailsPlayer[$defeat["idPlayer".$iP]]["ties"]=0;
					}

					$arrTotal["defeats"]+=$defeat["tot"];

				}
			}

			// WIN RATIO
			foreach($detailsPlayer as $idP => $dataDeatils) {
				if ($detailsPlayer[$idP]["wins"]+$detailsPlayer[$idP]["defeats"] != 0)
					$detailsPlayer[$idP]["loseratio"]=number_format(100*($detailsPlayer[$idP]["defeats"]/($detailsPlayer[$idP]["wins"]+$detailsPlayer[$idP]["ties"]+$detailsPlayer[$idP]["defeats"])), 1)."%";
				else $detailsPlayer[$idP]["loseratio"]="-";
			}
			$arrTotal["loseratio"]="";


			$arrTotal["total"]=$arrTotal["wins"]+$arrTotal["ties"]+$arrTotal["defeats"];

	  }
	  else {
	  	$request->getSession()->getFlashBag()->add('error', 'Error selecting rankings ('.$id.')');
	  }

		return $this->render('site/rankings_paddle_view.html.twig', [
	    'controller_name' => 'RankingspaddleController',
	    'form' => $form->createView(),
	    'ranking' => $ranking,
	    'arrRankings' => $arrRank,
	    'detailsRankings' => $detailsRankings,
	    'detailsPlayer' => $detailsPlayer,
	    'activeOnly' => $activeOnly,
	    'arrTotal' => $arrTotal,
	  ]);
	}




  /**
   * @Route("/simulatordouble", name="simulator_double")
   */
  public function simulatordouble2(Request $request)
  {
  	$arrRt=array();
  	$avg_teamA=null;
  	$avg_teamB=null;

  	$defaultData = array('message' => 'Type your message here');
		$formBuilder = $this->createFormBuilder($defaultData);

	  $formBuilder
	  ->add('rating_player1', TextType::class, array(
	    'label'    => 'TEAM A: Rating player 1',
	    'required'   => true,
	  ))
	  ->add('rating_player2', TextType::class, array(
	    'label'    => 'TEAM A: Rating player 2',
	    'required'   => true,
	  ))
	  ->add('rating_player3', TextType::class, array(
	    'label'    => 'TEAM B: Rating player 3',
	    'required'   => true,
	  ))
	  ->add('rating_player4', TextType::class, array(
	    'label'    => 'TEAM B: Rating player 4',
	    'required'   => true,
	  ))
	  ->add('result', ChoiceType::class, array(
      'choices' => array("Team A wins" => 1, "Team B wins" => 2, "tie" => 0),
      'required'   => true,
    ))
	  ->add("Calculate", SubmitType::class);

	  $form = $formBuilder->getForm();

		$form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // data is an array with "name", "email", and "message" keys
      $data = $form->getData();

      $rating_player1=$data['rating_player1'];
      $rating_player2=$data['rating_player2'];
      $rating_player3=$data['rating_player3'];
      $rating_player4=$data['rating_player4'];
      $result=$data['result'];

      if (isset($rating_player1) && is_numeric($rating_player1) && isset($rating_player2) && is_numeric($rating_player2) && isset($rating_player3) && is_numeric($rating_player3) && isset($rating_player4) && is_numeric($rating_player4)) {


			  $competitors = array(
			    array('id' => 1, 'name' => "Player 1", 'skill' => 100, 'rating' => $rating_player1, 'active' => 1),
			    array('id' => 2, 'name' => "Player 2", 'skill' => 100, 'rating' => $rating_player2, 'active' => 1),
			    array('id' => 3, 'name' => "Player 3", 'skill' => 100, 'rating' => $rating_player3, 'active' => 1),
			    array('id' => 4, 'name' => "Player 4", 'skill' => 100, 'rating' => $rating_player4, 'active' => 1),
			  );
			  //  initialize the ranking system and add the competitors
			  $elo = new EloRatingSystem(100, 50);
			  foreach ($competitors as $competitor) {
			    $elo->addCompetitor(new EloCompetitor($competitor['id'], $competitor['name'], $competitor['rating']));
			  }

			  if ($result==1) {
			    $elo->addResultDouble(
			    		$competitors[0], 
			    		$competitors[1], 
			    		$competitors[2], 
			    		$competitors[3]
			    	);
			    $match = "Team A defeats Team B";
			    //$result="player1";
			  }
			  elseif ($result==2) {
			    $elo->addResultDouble(
			    		$competitors[2], 
			    		$competitors[3], 
			    		$competitors[0], 
			    		$competitors[1] 
			    	);
			    $match = "Team B defeats Team A";
			    //$result="player2";
			  }
			  else {
			    $elo->addResultDouble(
			    		$competitors[0], 
			    		$competitors[1], 
			    		$competitors[2], 
			    		$competitors[3],
			    		true 
			    	);
			    $match = "TIE Team A - Team B";
			    //$result="draw";
			  }

			  $avg_teamA=($rating_player1+$rating_player2) / 2;
			  $avg_teamB=($rating_player3+$rating_player4) / 2;

			  $elo->updateRatings();

				$tabRank = $elo->getRankings();

				foreach ($tabRank as $idP => $val) {
					$exp=explode("#", $idP);
					if ($exp[0]==1) {
						$evol=$val-$rating_player1;
						if ($evol>0) $arrRt[1]="+".number_format($evol, 1);
						else $arrRt[1]=number_format($evol, 1);
					}
					elseif ($exp[0]==2) {
						$evol=$val-$rating_player2;
						if ($evol>0) $arrRt[2]="+".number_format($evol, 1);
						else $arrRt[2]=number_format($evol, 1);

					}
					elseif ($exp[0]==3) {
						$evol=$val-$rating_player3;
						if ($evol>0) $arrRt[3]="+".number_format($evol, 1);
						else $arrRt[3]=number_format($evol, 1);
					}
					elseif ($exp[0]==4) {
						$evol=$val-$rating_player4;
						if ($evol>0) $arrRt[4]="+".number_format($evol, 1);
						else $arrRt[4]=number_format($evol, 1);
					}

				}

			}

    }

	  return $this->render('site/simulator_double.html.twig', [
	    'controller_name' => 'RankingsController',
	    'form' => $form->createView(),
	    'avg_teamA' => $avg_teamA,
	    'avg_teamB' => $avg_teamB,
	    'arrRt' => $arrRt,
	  ]);
  }

}
