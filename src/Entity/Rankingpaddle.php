<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rankingpaddle
 *
 * @ORM\Table(name="RankingPaddle")
 * @ORM\Entity(repositoryClass="App\Repository\RankingpaddleRepository")
 */
class Rankingpaddle
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateGeneration", type="datetime", nullable=true)
     */
    private $dategeneration;

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

    public function getDategeneration(): ?\DateTimeInterface
    {
        return $this->dategeneration;
    }

    public function setDategeneration(?\DateTimeInterface $dategeneration): self
    {
        $this->dategeneration = $dategeneration;

        return $this;
    }


}
