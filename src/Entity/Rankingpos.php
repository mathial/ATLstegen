<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rankingpos
 *
 * @ORM\Table(name="RankingPos", indexes={@ORM\Index(name="RankingPosRanking", columns={"idRanking"}), @ORM\Index(name="RankingPosPlayer", columns={"idPlayer"})})
 * @ORM\Entity(repositoryClass="App\Repository\RankingposRepository")
 */
class Rankingpos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer", nullable=false)
     */
    private $position;

    /**
     * @var float
     *
     * @ORM\Column(name="score", type="float", precision=10, scale=0, nullable=false)
     */
    private $score;

    /**
     * @var \Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPlayer", referencedColumnName="id")
     * })
     */
    private $idplayer;

    /**
     * @var \Ranking
     *
     * @ORM\ManyToOne(targetEntity="Ranking")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idRanking", referencedColumnName="id")
     * })
     */
    private $idranking;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getIdplayer(): ?Player
    {
        return $this->idplayer;
    }

    public function setIdplayer(?Player $idplayer): self
    {
        $this->idplayer = $idplayer;

        return $this;
    }

    public function getIdranking(): ?Ranking
    {
        return $this->idranking;
    }

    public function setIdranking(?Ranking $idranking): self
    {
        $this->idranking = $idranking;

        return $this;
    }


}
