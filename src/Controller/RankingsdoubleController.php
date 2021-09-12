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
use App\Entity\Rankingdouble;
use App\Entity\Rankingposdouble;

class RankingsdoubleController extends AbstractController
{
  /**
   * @Route("/rankingsdouble", name="rankings_double")
   */
  public function index()
  {
      return $this->render('rankings/index.html.twig', [
          'controller_name' => 'RankingsdoubleController',
      ]);
  }

  /**
   * @Route("/rankingsdouble/generate", name="rankings_generate_double")
   */
  public function generate(Request $request)
  {
  	
	  $arrRankFinal = array();
	  $matchs = array();
		$date_from="";
		
		$em = $this->getDoctrine()->getManager();

		$arrRank=array();
		$rankings = $em->getRepository('App:Rankingdouble')->findBy(array(), array('date' => 'DESC'));
		foreach ($rankings as $rank) {
			$arrRank[$rank->getDate()->format("Y-m-d")] = $rank->getId();
		}
		$arrRank["Initial Rankings"]='init';

		$defaultData = array('message' => 'Type your message here');
		$formBuilder = $this->createFormBuilder($defaultData);

		$arrPlayersDisplay=array();
	  
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
      	$rankingdouble = $em->getRepository('App:Rankingdouble')->findOneBy(array("id" => $based_ranking));
      }

      $sql = '
		    SELECT DISTINCT idPlayer1 FROM MatchsDouble WHERE date <= :date
		    ';
			$stmt = $em->getConnection()->prepare($sql);
			$stmt->execute(['date' => $date_from->format("Y-m-d")]);
			$players1 = $stmt->fetchAll();
			
			$sql = '
		    SELECT DISTINCT idPlayer2 FROM MatchsDouble WHERE date <= :date
		    ';
			$stmt = $em->getConnection()->prepare($sql);
			$stmt->execute(['date' => $date_from->format("Y-m-d")]);
			$players2 = $stmt->fetchAll();

			$sql = '
		    SELECT DISTINCT idPlayer3 FROM MatchsDouble WHERE date <= :date
		    ';
			$stmt = $em->getConnection()->prepare($sql);
			$stmt->execute(['date' => $date_from->format("Y-m-d")]);
			$players3 = $stmt->fetchAll();

			$sql = '
		    SELECT DISTINCT idPlayer4 FROM MatchsDouble WHERE date <= :date
		    ';
			$stmt = $em->getConnection()->prepare($sql);
			$stmt->execute(['date' => $date_from->format("Y-m-d")]);
			$players4 = $stmt->fetchAll();
			
			
		  if ($based_ranking!="init") {
		  	$sql = ' SELECT id, idPlayer1, idPlayer2, idPlayer3, idPlayer4, tie FROM MatchsDouble WHERE date < :date AND date>= :date_based ';
		  	$stmt = $em->getConnection()->prepare($sql);
				$stmt->execute(['date' => $date_from->format("Y-m-d"), 'date_based' => $rankingdouble->getDate()->format("Y-m-d")]);
		  }
		  else {
		  	$sql = 'SELECT id, idPlayer1, idPlayer2, idPlayer3, idPlayer4, tie FROM MatchsDouble WHERE date < :date';
		  	$stmt = $em->getConnection()->prepare($sql);
				$stmt->execute(['date' => $date_from->format("Y-m-d")]);
		  }
			
			$matchs = $stmt->fetchAll();

			$arrPlayers=array();
			$arrPlayersDisplay=array();

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
				$player = $em->getRepository('App:Player')->findOneBy(array("id" => $pId));
				$arrPlayersDisplay[$pId]=$player->getNameshort();

				// get the ranking expected
				if ($based_ranking=="init") {
					$basedRate[$pId]=$player->getInitialRatingDouble();

				}
				else {
					$rankPos = $em->getRepository('App:Rankingposdouble')->findOneBy(array("idrankingdouble" => $based_ranking, "idplayer" => $player->getId()));
					if ($rankPos) {
						$basedRate[$pId] = $rankPos->getScore();
					}
					else {
						$basedRate[$pId] = $player->getInitialRatingDouble();
						//echo "RIEN".$based_ranking."/".$player->getId()." - ".$basedRate[$pId]."<br>";
					}
				}

				// specific double
				// if initial_rating=0 => get the last single rating
				if ($basedRate[$pId]==0) {
					$ranking = $em->getRepository('App:Player')->getLastRanking($pId);
					$basedRate[$pId]=number_format($ranking["score"], 0);
					$player->setInitialRatingDouble($basedRate[$pId]);
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
				$sql = '
			    DELETE FROM RankingDouble WHERE date = :date
			    ';
				$stmt = $em->getConnection()->prepare($sql);
				$nbDeletes = $stmt->execute(['date' => $date_from->format("Y-m-d")]);
				if ($nbDeletes>0) {
					$request->getSession()->getFlashBag()->add('info', 'Rankingdouble deleted ('.$date_from->format("Y-m-d").').');
				}

				$rankingdouble=new Rankingdouble();
				$rankingdouble->setDate($date_from);
				$rankingdouble->setDategeneration(new \DateTime(date("Y-m-d H:i:s")));

				$em->persist($rankingdouble);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Rankingdouble created');
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

		    $arrRankFinal[]=$row;

		    if ($generate_ranking==1) {
		    	$rankingdouble_pos = new Rankingposdouble();
		    	$player = $em->getRepository('App:Player')->findOneBy(array("id" => $expl_rank[0]));
		    	
		    	$rankingdouble_pos->setIdrankingdouble($rankingdouble);
		    	$rankingdouble_pos->setIdplayer($player);
		    	$rankingdouble_pos->setPosition($pos);
		    	$rankingdouble_pos->setScore($val);

					$em->persist($rankingdouble_pos);
		    }

		    $oldR=$val;
		  }

			if ($generate_ranking==1) {

				foreach($matchs as $m) {
					$match = $em->getRepository('App:Matchsdouble')->findOneBy(array("id" => $m["id"]));
					$match->setIdrankingdouble($rankingdouble->getId());
					$em->persist($match);
				}

       	$em->flush();
			}

    }

	  return $this->render('site/generate_rankings_double.html.twig', [
	    'controller_name' => 'RankingsdoubleController',
	    'form' => $form->createView(),
	    'arrRankFinal' => $arrRankFinal,
	    'dateFrom' => $date_from,
	    'arrMatchs' => $matchs,
	    'arrPlayersDisplay' => $arrPlayersDisplay,
	  ]);
	}


	/**
   * @Route("/rankingsdouble/view", name="rankings_double_view")
   * @Route("/rankingsdouble/view/{id}", name="rankings_double_view_id")
   * @Route("/rankingsdouble/view/{id}/{AO}", name="rankings_double_view_id_ao")
   */
  public function viewRanking($id=null, $AO=1, Request $request)
  {
  	if ($id<>null) $defaultId=$id;
  	else $defaultId=57;

  	$em = $this->getDoctrine()->getManager();

  	$activeOnly=$AO;
		$arrRank=array();
		$rankings = $em->getRepository('App:Rankingdouble')->findBy(array(), array('date' => 'DESC'));
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

      $url = $this->generateUrl('rankings_double_view_id_ao', array('id' => $idRanking, 'AO' => $activeOnly));
      return $this->redirect($url);
		}	
		elseif ($id<>null) {
			$ranking = $em->getRepository('App:Rankingdouble')->findOneBy(array('id' => $id));
		}
  	else {
  		$ranking = $em->getRepository('App:Rankingdouble')->getLastRanking();
  	}

		$ranking_1 =  $em->getRepository('App:Rankingdouble')->getRankingBefore($ranking->getDate()->format("Y-m-d"));

  	$detailsRankings=$em->getRepository('App:Rankingposdouble')->findBy(array('idrankingdouble' => $ranking));

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

  			$detailsRankings_1=$em->getRepository('App:Rankingposdouble')->findOneBy(array('idrankingdouble' => $ranking_1, "idplayer" => $det->getIdplayer()));
  			if ($detailsRankings_1) {
  				$evol = $det->getScore() - $detailsRankings_1->getScore();
  			}
  			else {
  				$evol=$det->getScore() - $det->getIdplayer()->getInitialRatingDouble();
  			}

				if ($evol>0) $detailsPlayer[$det->getIdplayer()->getId()]["evol"]="+".number_format($evol, 0);
				else $detailsPlayer[$det->getIdplayer()->getId()]["evol"]=number_format($evol, 0);

				$arrTotal["evolscore"]+=$evol;
				$arrTotal["score"]+=$det->getScore();


				$sql_best = 'SELECT MAX(score) AS score FROM RankingPosDouble RP, RankingDouble R WHERE 
							R.id=RP.idRankingDouble
							AND date<="'.$ranking->getDate()->format("Y-m-d").'" 
							AND idPlayer='.$det->getIdplayer()->getId();
			    $stmt = $em->getConnection()->prepare($sql_best);
			    $stmt->execute();
			    $best = $stmt->fetchAll();
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
		    	FROM MatchsDouble
		    	WHERE tie=0 AND date<:date
		    	GROUP BY idPlayer'.$iP.' 
			    ';
				$stmt = $em->getConnection()->prepare($sql);
				$stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
				$wins = $stmt->fetchAll();

				foreach ($wins as $win) {
					if (!isset($detailsPlayer[$win["idPlayer".$iP]]["wins"]))
						$detailsPlayer[$win["idPlayer".$iP]]["wins"]=0;
					$detailsPlayer[$win["idPlayer".$iP]]["wins"]+=$win["tot"];
					$detailsPlayer[$win["idPlayer".$iP]]["ties"]=0;
					$detailsPlayer[$win["idPlayer".$iP]]["defeats"]=0;

					$arrTotal["wins"]+=$win["tot"];

				}

				// TIES P1 + P2
				$sql = '
		    	SELECT COUNT(*) AS tot, idPlayer'.$iP.' 
		    	FROM MatchsDouble 
		    	WHERE tie=1 AND date<:date
		    	GROUP BY idPlayer'.$iP.' 
			    ';
				$stmt = $em->getConnection()->prepare($sql);
				$stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
				$ties1 = $stmt->fetchAll();

				foreach ($ties1 as $tie) {
					
					if (!isset($detailsPlayer[$win["idPlayer".$iP]]["ties"]))
						$detailsPlayer[$win["idPlayer".$iP]]["wins"]=0;

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
				// TIES P2
				$sql = '
		    	SELECT COUNT(*) AS tot, idPlayer'.$iP.'
		    	FROM MatchsDouble 
		    	WHERE tie=1 AND date<:date
		    	GROUP BY idPlayer'.$iP.' 
			    ';
				$stmt = $em->getConnection()->prepare($sql);
				$stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
				$ties1 = $stmt->fetchAll();

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
		    	FROM MatchsDouble 
		    	WHERE tie=0 AND date<:date
		    	GROUP BY idPlayer'.$iP.'
			    ';
				$stmt = $em->getConnection()->prepare($sql);
				$stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
				$defeats = $stmt->fetchAll();

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

			$arrTotal["total"]=$arrTotal["wins"]+$arrTotal["ties"]+$arrTotal["defeats"];

	  }
	  else {
	  	$request->getSession()->getFlashBag()->add('error', 'Error selecting rankings ('.$id.')');
	  }

		return $this->render('site/rankings_double_view.html.twig', [
	    'controller_name' => 'RankingsdoubleController',
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
