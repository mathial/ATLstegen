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
use App\Entity\Matchs;
use App\Entity\Ranking;
use App\Entity\Rankingpos;

class RankingsController extends AbstractController
{
  /**
   * @Route("/rankings", name="rankings")
   */
  public function index()
  {
      return $this->render('rankings/index.html.twig', [
          'controller_name' => 'RankingsController',
      ]);
  }

  public function testMethodController($var) {
  	return "tarace".$var;
  }


  public function calculateRankings ($em, $date_from, $generate_ranking, $based_ranking) {


  	$arrResults=array();
		$arrResults["playersDisplay"]=array();
		$arrResults["playersDeactivate"]=array();
		$arrResults["playersActivate"]=array();
		$arrResults["matchs"]=array();
		$arrResults["messages"]=array();


    if ($based_ranking!="init") {
    	$ranking = $em->getRepository('App\Entity\Ranking')->findOneBy(array("id" => $based_ranking));

    	if($generate_ranking==1) {

				// check if a ranking exist at that date
				$sql = '
			    SELECT * FROM Ranking WHERE date = :date
			    ';
			  $stmt = $em->getConnection()->prepare($sql);
				$exec = $stmt->execute(['date' => $date_from->format("Y-m-d")]);
				if ($rankingExist = $exec->fetchAll()) {

					// delete all the pos 
					$sql = '
			    DELETE FROM RankingPos WHERE idRanking = :idR
			    ';
					$stmt = $em->getConnection()->prepare($sql);
					$nbDeletes = $stmt->execute(['idR' => $rankingExist[0]["id"]]);

					if ($nbDeletes->rowCount()>0) {
						//$request->getSession()->getFlashBag()->add('info',  $stmt->rowCount().' RankingPos deleted ('.$date_from->format("Y-m-d").' // id#'.$rankingExist[0]["id"].').');
						$arrResults["messages"][]=[
							'type' => 'info',
							'msg' => $nbDeletes->rowCount().' RankingPos deleted ('.$date_from->format("Y-m-d").' // id#'.$rankingExist[0]["id"].').'
						];

					}

					// and then delete the rankings
					$sql = '
			    DELETE FROM Ranking WHERE id = :idR
			    ';
					$stmt = $em->getConnection()->prepare($sql);
					$nbDeletes = $stmt->execute(['idR' => $rankingExist[0]["id"]]);
					if ($nbDeletes->rowCount()>0) {
						//$request->getSession()->getFlashBag()->add('info', 'Ranking deleted ('.$date_from->format("Y-m-d").' // id#'.$rankingExist[0]["id"].').');
						$arrResults["messages"][]=[
							'type' => 'info',
							'msg' => 'Ranking deleted ('.$date_from->format("Y-m-d").' // id#'.$rankingExist[0]["id"].').'
						];
					}

				}
				
      }

    }

    $sql = '
	    SELECT DISTINCT idPlayer1 FROM Matchs WHERE date <= :date
	    ';
		$stmt = $em->getConnection()->prepare($sql);
		$exec = $stmt->execute(['date' => $date_from->format("Y-m-d")]);
		$players1 = $exec->fetchAll();
		
		$sql = '
	    SELECT DISTINCT idPlayer2 FROM Matchs WHERE date <= :date
	    ';
		$stmt = $em->getConnection()->prepare($sql);
		$exec = $stmt->execute(['date' => $date_from->format("Y-m-d")]);
		$players2 = $exec->fetchAll();
		
		
	  if ($based_ranking!="init") {
	  	$sql = ' SELECT id, idPlayer1, idPlayer2, tie FROM Matchs WHERE date < :date AND date>= :date_based ';
	  	$stmt = $em->getConnection()->prepare($sql);
			$exec = $stmt->execute(['date' => $date_from->format("Y-m-d"), 'date_based' => $ranking->getDate()->format("Y-m-d")]);
	  }
	  else {
	  	$sql = 'SELECT id, idPlayer1, idPlayer2, tie FROM Matchs WHERE date < :date';
	  	$stmt = $em->getConnection()->prepare($sql);
			$exec = $stmt->execute(['date' => $date_from->format("Y-m-d")]);
	  }
		
		$matchs = $exec->fetchAll();

		$arrPlayers=array();
		$arrResults["playersDisplay"]=array();

		foreach ($players1 as $row) {
			$arrPlayers[]=$row["idPlayer1"];
		}
		foreach ($players2 as $row) {
			$arrPlayers[]=$row["idPlayer2"];
		}

		$elo = new EloRatingSystem(100, 50);

		foreach($arrPlayers as $pId) {
			$player = $em->getRepository('App\Entity\Player')->findOneBy(array("id" => $pId));
			$arrResults["playersDisplay"][$pId]=$player->getNameshort();

			if (!in_array($player->getNameshort(), $arrResults["playersDeactivate"])) {				
				// get the last match played by the player
				$lastMatchP = $em->getRepository('App\Entity\Matchs')->findLastMatchPerPlayer($pId);
				//print_r($lastMatchP);
				$now_1_Y = date('Y-m-d', strtotime('-1 year'));

				// if player did not play for a year => deactivate
				if ($lastMatchP->getDate()->format("Y-m-d") <= $now_1_Y) {
					if ($player->getActiveTennis()==1) {
						$arrResults["playersDeactivate"][]=$player->getNameshort();
						if ($generate_ranking==1) {
							$player->setActivetennis(0);
							$em->flush();
						}
					} 
				}
				else {
					// if player was inactive => reactivate
					if (!in_array($player->getNameshort(), $arrResults["playersActivate"]) && $player->getActiveTennis()==0) {
						$arrResults["playersActivate"][]=$player->getNameshort();
						if ($generate_ranking==1) {
							$player->setActivetennis(1);
							$em->flush();
						}
					}
				}
			}


			// get the ranking expected
			if ($based_ranking=="init") {
				$basedRate=$player->getInitialRatingTennis();
			}
			else {
				$rankPos = $em->getRepository('App\Entity\Rankingpos')->findOneBy(array("idranking" => $based_ranking, "idplayer" => $player->getId()));
				if ($rankPos) {
					$basedRate = $rankPos->getScore();
				}
				else {
					// echo "RIEN".$based_ranking."/".$player->getId()."/".count($rankPos)."<br>";
					$basedRate = $player->getInitialRatingTennis();
				}
			}

			$elo->addCompetitor(new EloCompetitor($player->getId(), $player->getNameshort(), $basedRate));
		}
		
		foreach($matchs as $m) {
			if ($m["tie"]==1) $tie=true;
			else $tie=false;
			$elo->addResult($m["idPlayer1"], $m["idPlayer2"], $tie);
		}

		$elo->updateRatings();

	  $tabRank = $elo->getRankings();

	  if ($generate_ranking==1) {

			$ranking=new Ranking();
			$ranking->setDate($date_from);
			$ranking->setDategeneration(new \DateTime(date("Y-m-d H:i:s")));

			$em->persist($ranking);
      $em->flush();

      $arrResults["messages"][]=['type' => 'success', 'msg' => 'Ranking created #'.$ranking->getId()];

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

	    $arrResults["rankFinal"][]=$row;

	    if ($generate_ranking==1) {
	    	$ranking_pos = new Rankingpos();
	    	$player = $em->getRepository('App\Entity\Player')->findOneBy(array("id" => $expl_rank[0]));
	    	
	    	$ranking_pos->setIdranking($ranking);
	    	$ranking_pos->setIdplayer($player);
	    	$ranking_pos->setPosition($pos);
	    	$ranking_pos->setScore($val);

				$em->persist($ranking_pos);
	    }

	    $oldR=$val;
	  }

		if ($generate_ranking==1) {

			foreach($matchs as $m) {
				$match = $em->getRepository('App\Entity\Matchs')->findOneBy(array("id" => $m["id"]));
				$match->setIdranking($ranking->getId());
				$em->persist($match);
			}

     	$em->flush();
		}

		$arrResults["matchs"]=$matchs;

		return $arrResults;


  }

  /**
   * @Route("/rankings/generate", name="rankings_generate_tennis")
   */
  public function generateRankings(Request $request)
  {
  	
	  $matchs = array();
		$date_from="";
		
		$em = $this->getDoctrine()->getManager();

		$arrRank=array();
		$rankings = $em->getRepository('App\Entity\Ranking')->findBy(array(), array('date' => 'DESC'));
		foreach ($rankings as $rank) {
			$arrRank[$rank->getDate()->format("Y-m-d")] = $rank->getId();
		}
		$arrRank["Initial Rankings"]='init';

		$defaultData = array('message' => 'Type your message here');
		$formBuilder = $this->createFormBuilder($defaultData);

		$arrResults=array();
		$arrResults["playersDisplay"]=array();
		$arrResults["playersDeactivate"]=array();
		$arrResults["playersActivate"]=array();
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
	    'label'    => 'Generate rankings and deactivate players (no match during the last year)',
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
      $based_ranking_date="";

      if ($based_ranking!="init") {
	      $rankingBase = $em->getRepository('App\Entity\Ranking')->findOneBy(array('id' => $based_ranking));
	      $based_ranking_date = $rankingBase->getDate()->format("Y-m-d");
      }
      
      $arrResults = $this->calculateRankings ($em, $date_from, $generate_ranking, $based_ranking);
      if (isset($arrResults["messages"])) {
      	foreach($arrResults["messages"] as $elt ) {
      		$request->getSession()->getFlashBag()->add($elt["type"], $elt["msg"]);
      	}
      }

      // check if there are matches without any ranking linked

      $matchesWithoutRankings = $em->getRepository('App\Entity\Matchs')->getMatchesWithoutRankings($based_ranking_date);

      foreach($matchesWithoutRankings as $matW) {
      	$matchesWithoutRankingsFinal[]=array(
      		"id" => $matW->getId(),
      		"idPlayer1" => $matW->getIdplayer1()->getNameshort(),
      		"idPlayer2" => $matW->getIdplayer2()->getNameshort(),
      		"date" => $matW->getDate()->format("Y-m-d")
      	);
      }

    }

	  return $this->render('site/generate_rankings_tennis.html.twig', [
	    'controller_name' => 'RankingsController',
	    'form' => $form->createView(),
	    'arrRankFinal' => $arrResults["rankFinal"],
	    'dateFrom' => $date_from,
	    'arrMatchs' => $arrResults["matchs"],
	    'arrPlayersDisplay' => $arrResults["playersDisplay"],
	    'arrDeactivate' => $arrResults["playersDeactivate"],
	    'arrActivate' => $arrResults["playersActivate"],
	    'arrMatchesWithoutRankings' => $matchesWithoutRankingsFinal
	  ]);
	}


	/**
   * @Route("/rankings/view", name="rankings_view", defaults={"id" = null, "RI" = 0, "AO" = 1})
   * @Route("/rankings/view/{id}", name="rankings_view_id", defaults={"RI" = 0, "AO" = 1})
   * @Route("/rankings/view/{id}/{RI}/{AO}", name="rankings_view_id_ao")
   */
  public function viewRanking($id=null, $RI=0, $AO=1, Request $request)
  {
  	$em = $this->getDoctrine()->getManager();

  	if ($id<>null) {
			$ranking = $em->getRepository('App\Entity\Ranking')->findOneBy(array('id' => $id));
		}
  	else {
  		$ranking = $em->getRepository('App\Entity\Ranking')->getLastRanking();
  	}

  	if ($id<>null) $defaultId=$id;
  	else $defaultId=$ranking->getId();

  	$activeOnly=$AO;
  	$withRatingIndex=$RI;

		$arrRank=array();
		$rankings = $em->getRepository('App\Entity\Ranking')->findBy(array(), array('date' => 'DESC'));
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
		->add('with_rating_index', CheckboxType::class, array(
			'label' => "Show the average Rating Index (unique ratings over the past 6 months)",
		   'required' => false,
		   'data' => ($withRatingIndex==1 ? true : false)
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
	      $withRatingIndex=(isset($data['with_rating_index']) && $data['with_rating_index']!="" ? $data['with_rating_index'] : 0);

	      $url = $this->generateUrl('rankings_view_id_ao', array('id' => $idRanking, 'RI' => $withRatingIndex, 'AO' => $activeOnly));
	      return $this->redirect($url);
		}	
		/*elseif ($id<>null) {
			$ranking = $em->getRepository('App\Entity\Ranking')->findOneBy(array('id' => $id));
		}
  	else {
  		$ranking = $em->getRepository('App\Entity\Ranking')->getLastRanking();
  	}*/

		$ranking_1 =  $em->getRepository('App\Entity\Ranking')->getRankingBefore($ranking->getDate()->format("Y-m-d"));

		if ($withRatingIndex) {
			$ratingIndexRt =  $em->getRepository('App\Entity\Ranking')->getRatingIndex(180);
			$arrRatingIndex=array();
			if (count($ratingIndexRt)>0) {
				foreach ($ratingIndexRt as $rowRI) {
					$arrRatingIndex[$rowRI["id"]]=$rowRI["avg_score"];
				}
			}
		}

  	$detailsRankings=$em->getRepository('App\Entity\Rankingpos')->findBy(array('idranking' => $ranking));
  	$detailsPlayer=array();
		$arrTotal=array();
  	$arrTotal["score"]=0;
  	$arrTotal["evolscore"]=0;
  	$arrTotal["evolpos"]=0;
  	$arrTotal["wins"]=0;
  	$arrTotal["defeats"]=0;
  	$arrTotal["ties"]=0;
  	$arrTotal["total"]=0;
  	$arrRabbits=array();

  	if ($ranking && $detailsRankings) {


  		// SCORE EVOL
  		foreach ($detailsRankings as $det) {

	  		// if ratingIndex activated, add the data if it exists
	  		if ($withRatingIndex) {
	  			if (isset($arrRatingIndex[$det->getIdplayer()->getId()])) 
	  				$detailsPlayer[$det->getIdplayer()->getId()]["RI"]=$arrRatingIndex[$det->getIdplayer()->getId()];
	  			else
	  				$detailsPlayer[$det->getIdplayer()->getId()]["RI"]="-";
	  		}

  			$detailsRankings_1=$em->getRepository('App\Entity\Rankingpos')->findOneBy(array('idranking' => $ranking_1, "idplayer" => $det->getIdplayer()));
  			if ($detailsRankings_1) {
  				$evol = $det->getScore() - $detailsRankings_1->getScore();
  			}
  			else {
  				$evol=$det->getScore() - $det->getIdplayer()->getInitialRatingTennis();
  			}

				if ($evol>0) $detailsPlayer[$det->getIdplayer()->getId()]["evol"]="+".number_format($evol, 0);
				else $detailsPlayer[$det->getIdplayer()->getId()]["evol"]=number_format($evol, 0);

				$arrTotal["evolscore"]+=$evol;
				$arrTotal["score"]+=$det->getScore();


				$sql_best = 'SELECT MAX(score) AS score FROM RankingPos RP, Ranking R WHERE 
							R.id=RP.idRanking
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


  		// WINS
			$sql = '
	    	SELECT COUNT(*) AS tot, idPlayer1 
	    	FROM Matchs 
	    	WHERE tie=0 AND date<:date
	    	GROUP BY idPlayer1 
		    ';
			$stmt = $em->getConnection()->prepare($sql);
			$exec = $stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
			$wins = $exec->fetchAll();

			foreach ($wins as $win) {

				$detailsPlayer[$win["idPlayer1"]]["wins"]=$win["tot"];
				$detailsPlayer[$win["idPlayer1"]]["ties"]=0;
				$detailsPlayer[$win["idPlayer1"]]["defeats"]=0;

				$arrTotal["wins"]+=$win["tot"];

			}

			// TIES P1
			$sql = '
	    	SELECT COUNT(*) AS tot, idPlayer1 
	    	FROM Matchs 
	    	WHERE tie=1 AND date<:date
	    	GROUP BY idPlayer1 
		    ';
			$stmt = $em->getConnection()->prepare($sql);
			$exec = $stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
			$ties1 = $exec->fetchAll();

			foreach ($ties1 as $tie) {
				
				$detailsPlayer[$tie["idPlayer1"]]["ties"]=$tie["tot"];			

				if (!isset($detailsPlayer[$tie["idPlayer1"]]["wins"])) {
					$detailsPlayer[$tie["idPlayer1"]]["wins"]=0;
				}
				if (!isset($detailsPlayer[$tie["idPlayer1"]]["defeats"])) {
					$detailsPlayer[$tie["idPlayer1"]]["defeats"]=0;
				}

				$arrTotal["ties"]+=$tie["tot"];

			}

			// TIES P2
			$sql = '
	    	SELECT COUNT(*) AS tot, idPlayer2
	    	FROM Matchs 
	    	WHERE tie=1 AND date<:date
	    	GROUP BY idPlayer2 
		    ';
			$stmt = $em->getConnection()->prepare($sql);
			$exec = $stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
			$ties1 = $exec->fetchAll();

			foreach ($ties1 as $tie) {
				
				if (!isset($detailsPlayer[$tie["idPlayer2"]]["ties"])) {
					$detailsPlayer[$tie["idPlayer2"]]["ties"]=$tie["tot"];			
				}
				else $detailsPlayer[$tie["idPlayer2"]]["ties"]+=$tie["tot"];

				if (!isset($detailsPlayer[$tie["idPlayer2"]]["wins"])) {
					$detailsPlayer[$tie["idPlayer2"]]["wins"]=0;
				}
				if (!isset($detailsPlayer[$tie["idPlayer2"]]["defeats"])) {
					$detailsPlayer[$tie["idPlayer2"]]["defeats"]=0;
				}

				$arrTotal["ties"]+=$tie["tot"];

			}

			// DEFEATS
			$sql = '
	    	SELECT COUNT(*) AS tot, idPlayer2 
	    	FROM Matchs 
	    	WHERE tie=0 AND date<:date
	    	GROUP BY idPlayer2
		    ';
			$stmt = $em->getConnection()->prepare($sql);
			$exec = $stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
			$defeats = $exec->fetchAll();

			foreach ($defeats as $defeat) {
				$detailsPlayer[$defeat["idPlayer2"]]["defeats"]=$defeat["tot"];
				if (!isset($detailsPlayer[$defeat["idPlayer2"]]["wins"])) {
					$detailsPlayer[$defeat["idPlayer2"]]["wins"]=0;
				}
				if (!isset($detailsPlayer[$defeat["idPlayer2"]]["ties"])) {
					$detailsPlayer[$defeat["idPlayer2"]]["ties"]=0;
				}

				$arrTotal["defeats"]+=$defeat["tot"];

			}

			$arrTotal["total"]=$arrTotal["wins"]+$arrTotal["ties"]+$arrTotal["defeats"];


	  	$rabbits=$em->getRepository('App\Entity\Rabbit')->findBy(array("isover" =>false), array('id' => 'DESC'));
	  	foreach($rabbits as $rabbit){
	  		$arrRabbits[]=$rabbit->getIdplayerlast()->getId();
	  	}

print_r($arrRabbits);
	  }
	  else {
	  	$request->getSession()->getFlashBag()->add('error', 'Error selecting rankings ('.$id.')');
	  }

		return $this->render('site/rankings_view.html.twig', [
	    'controller_name' => 'RankingsController',
	    'form' => $form->createView(),
	    'ranking' => $ranking,
	    'arrRankings' => $arrRank,
	    'detailsRankings' => $detailsRankings,
	    'detailsPlayer' => $detailsPlayer,
	    'activeOnly' => $activeOnly,
	    'withRatingIndex' => $withRatingIndex,
	    'arrTotal' => $arrTotal,
	    'arrRabbits' => $arrRabbits,
	  ]);
	}

	/**
   * @Route("/simulator", name="simulator")
   */
  public function simulator(Request $request)
  {
  	$arrRt=array();

  	$defaultData = array('message' => 'Type your message here');
		$formBuilder = $this->createFormBuilder($defaultData);

	  $formBuilder
	  ->add('rating_player1', TextType::class, array(
	    'label'    => 'Rating player 1',
	    'required'   => true,
	  ))
	  ->add('rating_player2', TextType::class, array(
	    'label'    => 'Rating player 2',
	    'required'   => true,
	  ))
	  ->add('result', ChoiceType::class, array(
      'choices' => array("player 1 wins" => 1, "player 2 wins" => 2, "tie" => 0),
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
      $result=$data['result'];

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
					}
					elseif ($exp[0]==2) {
						$evol=$val-$rating_player2;
						if ($evol>0) $arrRt[2]="+".number_format($evol, 1);
						else $arrRt[2]=number_format($evol, 1);
					}

				}

			}

    }

    return $this->render('site/simulator.html.twig', [
	    'controller_name' => 'RankingsController',
	    'form' => $form->createView(),
	    'arrRt' => $arrRt,
	  ]);

  }

}
