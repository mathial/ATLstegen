<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Player;
use App\Entity\Ranking;
use App\Entity\Rankingpos;

class SiteController extends AbstractController
{

    private function getTopLastTennisPerf($nbMax=3) {

        $em = $this->getDoctrine()->getManager();

        // TENNIS

        $ranking = $em->getRepository('App:Ranking')->getLastRanking();
        $ranking_1 =  $em->getRepository('App:Ranking')->getRankingBefore($ranking->getDate()->format("Y-m-d"));

        $detailsRankings=$em->getRepository('App:Rankingpos')->findBy(array('idranking' => $ranking));
        $tabEvol=array();
        $tabEvolDetails=array();

        if ($ranking && $detailsRankings) {

            // SCORE EVOL
            foreach ($detailsRankings as $det) {

                $detailsRankings_1=$em->getRepository('App:Rankingpos')->findOneBy(array('idranking' => $ranking_1, "idplayer" => $det->getIdplayer()));

                if ($detailsRankings_1) {
                    $evol = $det->getScore() - $detailsRankings_1->getScore();

                    if ($evol!=0) {
                        $tabEvol[$det->getIdplayer()->getId()]=$evol;
                        $tabEvolDetails[$det->getIdplayer()->getId()]=$det->getIdplayer();
                    }
                }

            }
        }

        arsort($tabEvol);
        $nbEvol = count($tabEvol);

        $topTennisPerf=array();
        $iT=0;
        foreach ($tabEvol as $idP => $ev) {
            $iT++;

            $line=array();

            if ($ev>0) $ev="+".number_format($ev, 0);
            else $ev=number_format($ev, 0);

            $line["evol"]=$ev;
            $line["player"]=$tabEvolDetails[$idP];

            $topTennisPerf[$iT]=$line;

            if ($iT==$nbMax) break;

        }

        asort($tabEvol);

        $lastTennisPerf=array();
        $iT=0;
        foreach ($tabEvol as $idP => $ev) {
            $iT++;

            $line=array();

            if ($ev>0) $ev="+".number_format($ev, 0);
            else $ev=number_format($ev, 0);

            $line["evol"]=$ev;
            $line["player"]=$tabEvolDetails[$idP];

            $lastTennisPerf[$iT]=$line;

            if ($iT==3) break;

        }

        $rt["top"]=$topTennisPerf;
        $rt["last"]=$lastTennisPerf;
        $rt["nbEvol"]=$nbEvol;

        return $rt;

    }

    private function getBestSeries($minMatchsPlayed=5, $nbDays=60) {

        $em = $this->getDoctrine()->getManager();


        $sql='
        SELECT SUM(tot) as bigTot, idPlayer FROM
            (SELECT COUNT(*) AS tot, idPlayer1 AS idPlayer FROM Matchs M WHERE date >= DATE(NOW()) - INTERVAL :nbDays DAY GROUP BY idPlayer1
            UNION
            SELECT COUNT(*) AS tot, idPlayer2 AS idPlayer FROM Matchs M  WHERE date >= DATE(NOW()) - INTERVAL :nbDays DAY GROUP BY idPlayer2
            ) AS unionplayersmatch
        GROUP BY idPlayer  
        HAVING bigTot >= :minMatchsPlayed
        ORDER BY SUM(tot)  DESC'
        ;

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute(['minMatchsPlayed' => $minMatchsPlayed, 'nbDays' => $nbDays]);
        $players = $stmt->fetchAll();

        $arrRecapPlayers=array();

        foreach ($players as $player) {
            $arrRecapPlayers[$player["idPlayer"]]["tot"]=$player["bigTot"];
            $arrRecapPlayers[$player["idPlayer"]]["W"]=0;
            $arrRecapPlayers[$player["idPlayer"]]["D"]=0;
            $arrRecapPlayers[$player["idPlayer"]]["T"]=0;

            $sql='SELECT * FROM Matchs 
                    WHERE (idPlayer1= :idPlayer OR idPlayer2= :idPlayer)
                    AND date >= DATE(NOW()) - INTERVAL :nbDays DAY';

            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute(['idPlayer' => $player["idPlayer"], 'nbDays' => $nbDays]);
            $matchs = $stmt->fetchAll();
            
            foreach ($matchs as $match) {
                if ($match["tie"]==1) $arrRecapPlayers[$player["idPlayer"]]["T"]++;
                elseif ($match["idPlayer1"]==$player["idPlayer"]) $arrRecapPlayers[$player["idPlayer"]]["W"]++;
                else $arrRecapPlayers[$player["idPlayer"]]["D"]++;
            } 
        }

        $arrRecapSorted=array();

        foreach ($arrRecapPlayers as $idP=>$p) {
            $arrRecapSorted[$idP]=$p["W"]*100+$p["T"]-$p["D"]*100;
        }

        arsort($arrRecapSorted);

        $rt=array();
        foreach ($arrRecapSorted as $idP=>$p) {
            $rt[$idP]=$arrRecapPlayers[$idP];

            $rt[$idP]["score"]=$p;

            $player = $em->getRepository('App:Player')->findOneBy(array("id" => $idP));

            $rt[$idP]["player"]=$player;

        }
        return $rt;
    }

    /**
     * @Route("/", name="site")
     */
    public function index()
    {   
        return $this->render('common/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/tennis-index", name="tennis_index")
     */
    public function indexTennis()
    {   
        $rtTopLast=$this->getTopLastTennisPerf();

        $rtSeries=$this->getBestSeries();

//print_r($rtSeries);

        return $this->render('site/tennis_index.html.twig', [
            'controller_name' => 'SiteController',
            'top3TennisPerf' => $rtTopLast["top"],
            'last3TennisPerf' => $rtTopLast["last"],
            'nbEvol' => $rtTopLast["nbEvol"],
            'rtSeries' => $rtSeries,
        ]);
    }

    /**
     * @Route("/rules-sv", name="rules_sv")
     */
    public function rules()
    {
        return $this->render('common/rules_sv.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/tennis-calendar-events", name="tennis_calendar_events")
     */
    public function tennisCalendarEvents()
    {
        return $this->render('site/events.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/summer-tournament", name="summer_longformat_tournament")
     */
    public function tennisSummerTournament()
    {
        return $this->render('site/longformat_summer_tournament.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/longformat-tournament-2021", name="longformat_tournament_2021")
     */
    public function tennisLongFormatTournament2021()
    {
        return $this->render('site/longformat_tournament_2021.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/the-division-league", name="division_league")
     */
    public function tennisDivisionLeague()
    {
        return $this->render('site/division_league.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }
}

