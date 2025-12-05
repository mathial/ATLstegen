<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Player;
use App\Entity\Ranking;
use App\Entity\Rankingpos;

class SiteController extends AbstractController
{
    private function getYears() {
        $years=array();
        for ($iY=2017;$iY<=date("Y");$iY++) {
          $years[]=$iY;
        }
        return $years;
    }

    private function getGenericSatsNbMatchs() {

        $em = $this->getDoctrine()->getManager();

        $sql='SELECT COUNT(*) AS nbM, context, YEAR(date) as yr FROM Matchs
              GROUP BY YEAR(date), context' ;

        $stmt = $em->getConnection()->prepare($sql);
        $exec = $stmt->execute();
        $nbmatchs = $exec->fetchAll();

        $years=$this->getYears();

        $initYears=array();
        foreach($years as $yr){
            $initYears[$yr]=0;
        }
        $initYears["alltime"]=0;

        /*$finalNbM=array("Summer tournament" => array("alltime" => 0), 
                        "Division League" => array("alltime" => 0), 
                        "Stegen Slutspel" => array("alltime" => 0), 
                        "TOTAL" => array("alltime" => 0));*/
        $finalNbM=array("TOTAL" => $initYears);

        foreach($nbmatchs as $nbm) {

            if (strpos($nbm["context"], "Summer tournament") !== false) {
                $contextIndex="Summer tournament";
            }
            elseif (strpos($nbm["context"], "Division League") !== false) {
                $contextIndex="Division League";
            }
            elseif (strpos($nbm["context"], "Stegen Slutspel") !== false) {
                $contextIndex="Stegen Slutspel";
            }
            else { 
                $contextIndex=$nbm["context"];
            }
//print_r($nbm);

            if (!isset($finalNbM[$contextIndex])) {
                $finalNbM[$contextIndex]=$initYears;
            }

            $finalNbM[$contextIndex][$nbm["yr"]]+=$nbm["nbM"];
         
            $finalNbM[$contextIndex]["alltime"]+=$nbm["nbM"];

            $finalNbM["TOTAL"][$nbm["yr"]]+=$nbm["nbM"];
            $finalNbM["TOTAL"]["alltime"]+=$nbm["nbM"];

        }
//print_r($finalNbM);
        return $finalNbM;
    }

    private function getGenericSatsNbPlayers() {

        $em = $this->getDoctrine()->getManager();

        $sql='SELECT COUNT(DISTINCT idP) as nbP, yr FROM(
                SELECT idPlayer1 as idP, YEAR(date) as yr FROM Matchs GROUP BY YEAR(date), idPlayer1
                UNION
                SELECT idPlayer2 as idP, YEAR(date) as yr FROM Matchs GROUP BY YEAR(date), idPlayer2
            ) as listP
            GROUP BY yr
            ORDER BY yr' ;

        $stmt = $em->getConnection()->prepare($sql);
        $exec = $stmt->execute();
        $nbplayers = $exec->fetchAll();
        $years=$this->getYears();

        $rtFinal=array();
        foreach($years as $yr){
            $rtFinal[$yr]=0;
        }

        foreach($nbplayers as $nbp) { 
            $rtFinal[$nbp["yr"]]=$nbp["nbP"];
        }

        // getting the total number of different players since start
        $sql='SELECT COUNT(DISTINCT idP) as nbP FROM(
                SELECT idPlayer1 as idP, YEAR(date) as yr FROM Matchs idPlayer1
                UNION
                SELECT idPlayer2 as idP, YEAR(date) as yr FROM Matchs idPlayer2
            ) as listP' ;

        $stmt = $em->getConnection()->prepare($sql);
        $exec = $stmt->execute();
        $nbplayers = $exec->fetchAll();

        $rtFinal["TOTAL"]=$nbplayers[0]["nbP"];

        return $rtFinal;
    }



    private function getTopLastTennisPerf($nbMax=3) {

        $em = $this->getDoctrine()->getManager();

        // TENNIS

        $ranking = $em->getRepository('App\Entity\Ranking')->getLastRanking();
        $ranking_1 =  $em->getRepository('App\Entity\Ranking')->getRankingBefore($ranking->getDate()->format("Y-m-d"));

        $detailsRankings=$em->getRepository('App\Entity\Rankingpos')->findBy(array('idranking' => $ranking));
        $tabEvol=array();
        $tabEvolDetails=array();

        if ($ranking && $detailsRankings) {

            // SCORE EVOL
            foreach ($detailsRankings as $det) {

                $detailsRankings_1=$em->getRepository('App\Entity\Rankingpos')->findOneBy(array('idranking' => $ranking_1, "idplayer" => $det->getIdplayer()));

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

    private function getBestUpsets ($nbRows=20) {
        $em = $this->getDoctrine()->getManager();

        $matches = $em->getRepository('App\Entity\Matchs')->findBy(array(), array("ptsevol"=> "DESC"), $nbRows);

        $rt=array();
        foreach($matches as $mat) {
            $line=array();

            $line["p1"]=$mat->getIdplayer1();
            $line["p2"]=$mat->getIdplayer2();
//            $line["nameP1"]=$mat->getIdplayer1()->getNameshort();
//            $line["nameP2"]=$mat->getIdplayer2()->getNameshort();
            $line["date"]=$mat->getDate()->format("Y-m-d");
            $line["score"]=$mat->getScore();
            $line["context"]=$mat->getContext();
            $line["conditions"]=$mat->getConditions();
            $line["ptsEvol"]=$mat->getPtsevol();
            $rt[]=$line;
        }

        return $rt;

    }


    private function getStackedContextTennisMatches() {

        $em = $this->getDoctrine()->getManager();


        $sql='
        SELECT COUNT(*) as tot,
            YEAR(date) as yearnum,
            WEEK(date) as weeknum,
            context
        FROM Matchs
        GROUP BY yearnum, weeknum, context
        ORDER BY yearnum, weeknum, context
        ';

        $stmt = $em->getConnection()->prepare($sql);
        $exec = $stmt->execute();
        $stackedMatches = $exec->fetchAll();

        $arrMatchesStacked=array();

        foreach ($stackedMatches as $stM) {
            print_r($stM);
            if (!isset($arrMatchesStacked["yearnum"]))
                $arrMatchesStacked["yearnum"]=array();
            if (!isset($arrMatchesStacked["yearnum"]["weeknum"]))
                $arrMatchesStacked["yearnum"]["weeknum"]=0;
        }
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
        $exec = $stmt->execute(['minMatchsPlayed' => $minMatchsPlayed, 'nbDays' => $nbDays]);
        $players = $exec->fetchAll();

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
            $exec = $stmt->execute(['idPlayer' => $player["idPlayer"], 'nbDays' => $nbDays]);
            $matchs = $exec->fetchAll();
            
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

            $player = $em->getRepository('App\Entity\Player')->findOneBy(array("id" => $idP));

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
        $years=$this->getYears();

        $rtTopLast=$this->getTopLastTennisPerf();

        $rtBestUpsets=$this->getBestUpsets(30);

        $rtSeries=$this->getBestSeries();

        $rtGenericStatsNbMatchs=$this->getGenericSatsNbMatchs();

        $rtGenericStatsNbPlayers=$this->getGenericSatsNbPlayers();

//print_r($rtGenericStatsNbPlayers);

        return $this->render('site/tennis_index.html.twig', [
            'controller_name' => 'SiteController',
            'years' => $years,
            'genericStatsNbMatchs' => $rtGenericStatsNbMatchs,
            'genericStatsNbPlayers' => $rtGenericStatsNbPlayers,
            'top3TennisPerf' => $rtTopLast["top"],
            'last3TennisPerf' => $rtTopLast["last"],
            'bestUpsets' => $rtBestUpsets,
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
     * @Route("/summer-tournaments", name="generation_matchup")
     */
    public function tennisGenerationMatchup()
    {
        return $this->render('site/generation_matchup.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/summer-tournaments", name="summer_tournaments")
     */
    public function tennisSummerTournaments()
    {
        return $this->render('site/summer_tournaments.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/summer-tournament-2024", name="summer_longformat_tournament_2024")
     */
    public function tennisSummerTournament2024()
    {
        return $this->render('site/summer_tournaments_2024.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/summer-tournament-2023", name="summer_longformat_tournament_2023")
     */
    public function tennisSummerTournament2023()
    {
        return $this->render('site/summer_tournaments_2023.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/summer-tournament-2022", name="summer_longformat_tournament_2022")
     */
    public function tennisSummerTournament2022()
    {
        return $this->render('site/longformat_summer_tournament_2022.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/summer-longformat-tournament-2021", name="summer_longformat_tournament_2021")
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

    /**
     * @Route("/chase-the-rabbits", name="rabbit_rules")
     */
    public function tennisChaseTheRabbit()
    {
        return $this->render('site/rabbit_rules.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }
}

