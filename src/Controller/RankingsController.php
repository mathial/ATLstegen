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

  /**
   * @Route("/rankings/generate", name="rankings_generate_test")
   */
  public function generateTest(Request $request)
  {
  	
	  $arrRankFinal = array();
	  $matchs = array();
		$date_from="";
		
		$em = $this->getDoctrine()->getManager();

		$arrRank=array();
		$rankings = $em->getRepository('App:Ranking')->findBy(array(), array('date' => 'DESC'));
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
      	$ranking = $em->getRepository('App:Ranking')->findOneBy(array("id" => $based_ranking));
      }

      $sql = '
		    SELECT DISTINCT idPlayer1 FROM Matchs WHERE date <= :date
		    ';
			$stmt = $em->getConnection()->prepare($sql);
			$stmt->execute(['date' => $date_from->format("Y-m-d")]);
			$players1 = $stmt->fetchAll();
			
			$sql = '
		    SELECT DISTINCT idPlayer2 FROM Matchs WHERE date <= :date
		    ';
			$stmt = $em->getConnection()->prepare($sql);
			$stmt->execute(['date' => $date_from->format("Y-m-d")]);
			$players2 = $stmt->fetchAll();
			
			
		  if ($based_ranking!="init") {
		  	$sql = ' SELECT id, idPlayer1, idPlayer2, tie FROM Matchs WHERE date < :date AND date>= :date_based ';
		  	$stmt = $em->getConnection()->prepare($sql);
				$stmt->execute(['date' => $date_from->format("Y-m-d"), 'date_based' => $ranking->getDate()->format("Y-m-d")]);
		  }
		  else {
		  	$sql = 'SELECT id, idPlayer1, idPlayer2, tie FROM Matchs WHERE date < :date';
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

			$elo = new EloRatingSystem(100, 50);

			foreach($arrPlayers as $pId) {
				$player = $em->getRepository('App:Player')->findOneBy(array("id" => $pId));
				$arrPlayersDisplay[$pId]=$player->getNameshort();

				// get the ranking expected
				if ($based_ranking=="init") {
					$basedRate=$player->getInitialRating();
				}
				else {
					$rankPos = $em->getRepository('App:Rankingpos')->findOneBy(array("idranking" => $based_ranking, "idplayer" => $player->getId()));
					if ($rankPos) {
						$basedRate = $rankPos->getScore();
					}
					else {
						// echo "RIEN".$based_ranking."/".$player->getId()."/".count($rankPos)."<br>";
						$basedRate = $player->getInitialRating();
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
				$sql = '
			    DELETE FROM Ranking WHERE date = :date
			    ';
				$stmt = $em->getConnection()->prepare($sql);
				$nbDeletes = $stmt->execute(['date' => $date_from->format("Y-m-d")]);
				if ($nbDeletes>0) {
					$request->getSession()->getFlashBag()->add('info', 'Ranking deleted ('.$date_from->format("Y-m-d").').');
				}

				$ranking=new Ranking();
				$ranking->setDate($date_from);
				$ranking->setDategeneration(new \DateTime(date("Y-m-d H:i:s")));

				$em->persist($ranking);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Ranking created');
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
		    	$ranking_pos = new Rankingpos();
		    	$player = $em->getRepository('App:Player')->findOneBy(array("id" => $expl_rank[0]));
		    	
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
					$match = $em->getRepository('App:Matchs')->findOneBy(array("id" => $m["id"]));
					$match->setIdranking($ranking->getId());
					$em->persist($match);
				}

       	$em->flush();
			}

    }

	  return $this->render('site/generate_rankings_test.html.twig', [
	    'controller_name' => 'RankingsController',
	    'form' => $form->createView(),
	    'arrRankFinal' => $arrRankFinal,
	    'dateFrom' => $date_from,
	    'arrMatchs' => $matchs,
	    'arrPlayersDisplay' => $arrPlayersDisplay,
	  ]);
	}


	/**
   * @Route("/rankings/view", name="rankings_view")
   * @Route("/rankings/view/{id}", name="rankings_view_id")
   * @Route("/rankings/view/{id}/{AO}", name="rankings_view_id_ao")
   */
  public function viewRanking($id=null, $AO=1, Request $request)
  {
  	if ($id<>null) $defaultId=$id;
  	else $defaultId=57;

  	$em = $this->getDoctrine()->getManager();

  	$activeOnly=$AO;
	$arrRank=array();
	$rankings = $em->getRepository('App:Ranking')->findBy(array(), array('date' => 'DESC'));
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

      $url = $this->generateUrl('rankings_view_id_ao', array('id' => $idRanking, 'AO' => $activeOnly));
      return $this->redirect($url);
	}	
	elseif ($id<>null) {
		$ranking = $em->getRepository('App:Ranking')->findOneBy(array('id' => $id));
	}
  	else {
  		$ranking = $em->getRepository('App:Ranking')->getLastRanking();
  	}

	$ranking_1 =  $em->getRepository('App:Ranking')->getRankingBefore($ranking->getDate()->format("Y-m-d"));

  	$detailsRankings=$em->getRepository('App:Rankingpos')->findBy(array('idranking' => $ranking));
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

  			$detailsRankings_1=$em->getRepository('App:Rankingpos')->findOneBy(array('idranking' => $ranking_1, "idplayer" => $det->getIdplayer()));
  			if ($detailsRankings_1) {
  				$evol = $det->getScore() - $detailsRankings_1->getScore();
  			}
  			else {
  				$evol=$det->getScore() - $det->getIdplayer()->getInitialrating();
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
		    $stmt->execute();
		    $best = $stmt->fetchAll();
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
		$stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
		$wins = $stmt->fetchAll();

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
		$stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
		$ties1 = $stmt->fetchAll();

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
		$stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
		$ties1 = $stmt->fetchAll();

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
		$stmt->execute(['date' => $ranking->getDate()->format("Y-m-d")]);
		$defeats = $stmt->fetchAll();

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
	    'arrTotal' => $arrTotal,
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
