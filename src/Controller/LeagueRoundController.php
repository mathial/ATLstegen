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
        $participantIdForPhone = array();

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

        // for players connected, display the phone numbers for +6/-6 players
        if ($this->getUser()!== NULL) {
            $idPlayerUser = $this->getUser()->getIdplayer()->getId();

            // Find the connected player's position in the participants array
            foreach ($participants as $index => $participant) {

                if ($participant->getIdplayer()->getId() == $idPlayerUser) {

                    // Get 6 participants above and 6 below
                    $start = max(0, $index - 6);
                    $end = min(count($participants) - 1, $index + 6);

                    for ($i = $start; $i <= $end; $i++) {
                        $participantIdForPhone[] = $participants[$i]->getIdplayer()->getId();
                    }

                    break;
                }
            }
        }
        //print_r($participantIdForPhone);

        return $this->render('site/league-round.html.twig', [
            'controller_name' => 'LeagueRoundController',
            'leagueRound' => $leagueRound,
            'participants' => $participants,
            'participantIdForPhone' => $participantIdForPhone,
        ]);
    }
}
