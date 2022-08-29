<?php

// src/Command/GenerateTennisSingleRankingsCommand.php
namespace App\Command;

use App\Service\MessageGenerator;
use App\Service\RankingsGenerator;
use App\Controller\RankingsController;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\DependencyInjection\ContainerInterface;

class GenerateTennisSingleRankingsCommand extends Command
{
  // the name of the command (the part after "bin/console")
  protected static $defaultName = 'app:generate-tennis-single-rankings';

  private $container;

  public function __construct(ContainerInterface $container)
  {
    parent::__construct();
    $this->container = $container;
  }

  protected function configure()
  {
    $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Generate the rankings for the date specified (YYYY-MM-AA), single tennis.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('Generate the rankings for the date specified (YYYY-MM-AA), single tennis.')
        ->addArgument('dateRankings', InputArgument::REQUIRED, 'Date for the rankings (YYYY-MM-AA)')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $inputDate=$input->getArgument('dateRankings');
    $output->writeln('Generating tennis single rankings for '.$inputDate);


    if (strlen($inputDate)==10 && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$inputDate)) {

        $em = $this->container->get('doctrine')->getManager();

        // get the last ranking
        $ranking = $em->getRepository('App:Ranking')->getLastRanking();
        $output->writeln("Last ranking id is ".$ranking->getId());


        //$rkgC = $this->container->get('RankingsController');        
        $rkgC = new RankingsController();
        $results = $rkgC->calculateRankings($em, new \DateTime($inputDate), 1, $ranking->getId());
        
        if (isset($results["messages"])) {
          foreach($results["messages"] as $elt) {
            $output->writeln($elt["type"]. " : ".$elt["msg"]);
          }
        }

        if (isset($results["playersDeactivate"])) {
          $output->writeln(count($results["playersDeactivate"]). "player(s) to deactivate");
          print_r($results["playersDeactivate"]);
        }

        if (isset($results["playersActivate"])) {
          $output->writeln(count($results["playersActivate"]). "player(s) to activate");
          print_r($results["playersActivate"]);
        }

    } else {
        $output->writeln('ERROR : wrong date format, must be YYYY-MM-AA');

    }
  }
}


?>