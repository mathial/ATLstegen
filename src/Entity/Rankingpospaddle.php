<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rankingpospaddle
 *
 * @ORM\Table(name="RankingPosPaddle", indexes={@ORM\Index(name="RankingPosPaddleRanking", columns={"idRankingPaddle"}), @ORM\Index(name="RankingPosPaddlePlayer", columns={"idPlayer"})})
 * @ORM\Entity
 */
class Rankingpospaddle
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
     * @var \Rankingpaddle
     *
     * @ORM\ManyToOne(targetEntity="Rankingpaddle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idRankingPaddle", referencedColumnName="id")
     * })
     */
    private $idrankingpaddle;

    /**
     * @var \Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPlayer", referencedColumnName="id")
     * })
     */
    private $idplayer;

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

    public function getIdrankingpaddle(): ?Rankingpaddle
    {
        return $this->idrankingpaddle;
    }

    public function setIdrankingpaddle(?Rankingpaddle $idrankingpaddle): self
    {
        $this->idrankingpaddle = $idrankingpaddle;

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


}
