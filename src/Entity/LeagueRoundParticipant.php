<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LeagueRoundParticipant
 *
 * @ORM\Table(name="LeagueRoundParticipant", indexes={
 *     @ORM\Index(name="LeagueRoundParticipantToLeagueRound", columns={"idLeagueRound"}),
 *     @ORM\Index(name="LeagueRoundParticipantToPlayer", columns={"idPlayer"})
 * })
 * @ORM\Entity
 */
class LeagueRoundParticipant
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
     * @var \LeagueRound
     *
     * @ORM\ManyToOne(targetEntity="LeagueRound")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idLeagueRound", referencedColumnName="id")
     * })
     */
    private $idleagueround;

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
     * @var int
     *
     * @ORM\Column(name="rating", type="integer", nullable=false)
     */
    private $rating;

    /**
     * @var int|null
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @var int|null
     *
     * @ORM\Column(name="points", type="integer", nullable=true)
     */
    private $points;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdleagueround(): ?LeagueRound
    {
        return $this->idleagueround;
    }

    public function setIdleagueround(?LeagueRound $idleagueround): self
    {
        $this->idleagueround = $idleagueround;

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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }
}
