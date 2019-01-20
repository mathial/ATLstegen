<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matchs
 *
 * @ORM\Table(name="Matchs", indexes={@ORM\Index(name="MatchToPlayer1", columns={"idPlayer1"}), @ORM\Index(name="MatchToPlayer2", columns={"idPlayer2"})})
 * @ORM\Entity(repositoryClass="App\Repository\MatchsRepository")
 */
class Matchs
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var string|null
     *
     * @ORM\Column(name="context", type="string", length=50, nullable=true)
     */
    private $context;

    /**
     * @var string|null
     *
     * @ORM\Column(name="score", type="string", length=50, nullable=true)
     */
    private $score;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="tie", type="boolean", nullable=true)
     */
    private $tie;

    /**
     * @var string|null
     *
     * @ORM\Column(name="conditions", type="string", length=20, nullable=true)
     */
    private $conditions;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idRanking", type="integer", nullable=true)
     */
    private $idranking;

    /**
     * @var \Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPlayer1", referencedColumnName="id")
     * })
     */
    private $idplayer1;

    /**
     * @var \Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPlayer2", referencedColumnName="id")
     * })
     */
    private $idplayer2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(?string $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getTie(): ?bool
    {
        return $this->tie;
    }

    public function setTie(?bool $tie): self
    {
        $this->tie = $tie;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(?string $conditions): self
    {
        $this->conditions = $conditions;

        return $this;
    }

    public function getIdranking(): ?int
    {
        return $this->idranking;
    }

    public function setIdranking(?int $idranking): self
    {
        $this->idranking = $idranking;

        return $this;
    }

    public function getIdplayer1(): ?Player
    {
        return $this->idplayer1;
    }

    public function setIdplayer1(?Player $idplayer1): self
    {
        $this->idplayer1 = $idplayer1;

        return $this;
    }

    public function getIdplayer2(): ?Player
    {
        return $this->idplayer2;
    }

    public function setIdplayer2(?Player $idplayer2): self
    {
        $this->idplayer2 = $idplayer2;

        return $this;
    }


}
