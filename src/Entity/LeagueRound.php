<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LeagueRound
 *
 * @ORM\Table(name="LeagueRound")
 * @ORM\Entity
 */
class LeagueRound
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
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateStart", type="date", nullable=false)
     */
    private $datestart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFinish", type="date", nullable=false)
     */
    private $datefinish;

    /**
     * @var int
     *
     * @ORM\Column(name="roundNumber", type="integer", nullable=false)
     */
    private $roundnumber;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbPlayers", type="integer", nullable=true)
     */
    private $nbplayers;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbGroups", type="integer", nullable=true)
     */
    private $nbgroups;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->datestart;
    }

    public function setDateStart(\DateTimeInterface $datestart): self
    {
        $this->datestart = $datestart;

        return $this;
    }

    public function getDateFinish(): ?\DateTimeInterface
    {
        return $this->datefinish;
    }

    public function setDateFinish(\DateTimeInterface $datefinish): self
    {
        $this->datefinish = $datefinish;

        return $this;
    }

    public function getRoundNumber(): ?int
    {
        return $this->roundnumber;
    }

    public function setRoundNumber(int $roundnumber): self
    {
        $this->roundnumber = $roundnumber;

        return $this;
    }

    public function getNbPlayers(): ?int
    {
        return $this->nbplayers;
    }

    public function setNbPlayers(?int $nbplayers): self
    {
        $this->nbplayers = $nbplayers;

        return $this;
    }

    public function getNbGroups(): ?int
    {
        return $this->nbgroups;
    }

    public function setNbGroups(?int $nbgroups): self
    {
        $this->nbgroups = $nbgroups;

        return $this;
    }
}
