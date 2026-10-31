<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\EntityManagerTrait;

use App\Entity\LeagueRound;
use App\Entity\LeagueRoundParticipant;

class LeagueRoundController extends AbstractController
{

    use EntityManagerTrait;

    // ✅ Constructor injection
    public function __construct(EntityManagerInterface $em)
    {
      $this->setEntityManager($em);
    } 

    /**
     * @Route("/league-round/{idLeagueRound}", name="league_round")
     */
    public function viewLeagueRound($idLeagueRound)
    {
        $em = $this->em();

        // Get LeagueRound information
        $leagueRound = $em->getRepository('App\Entity\LeagueRound')
            ->find($idLeagueRound);

        if (!$leagueRound) {
            throw $this->createNotFoundException('League round not found.');
        }

        // Get all participants
        $participants = $em->getRepository(LeagueRoundParticipant::class)
            ->findBy(
                ['idleagueround' => $leagueRound->getId()],
                ['rating' => 'DESC']
            );

        return $this->render('site/league-round.html.twig', [
            'controller_name' => 'LeagueRoundController',
            'leagueRound' => $leagueRound,
            'participants' => $participants,
        ]);
    }
}
