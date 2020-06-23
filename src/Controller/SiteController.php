<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Player;
use App\Entity\Ranking;
use App\Entity\Rankingpos;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="site")
     */
    public function index()
    {   

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

        $top3TennisPerf=array();
        $iT=0;
        foreach ($tabEvol as $idP => $ev) {
            $iT++;

            $line=array();

            if ($ev>0) $ev="+".number_format($ev, 0);
            else $ev=number_format($ev, 0);

            $line["evol"]=$ev;
            $line["player"]=$tabEvolDetails[$idP];

            $top3TennisPerf[$iT]=$line;

            if ($iT==3) break;

        }

        return $this->render('common/index.html.twig', [
            'controller_name' => 'SiteController',
            'top3TennisPerf' => $top3TennisPerf
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
}

