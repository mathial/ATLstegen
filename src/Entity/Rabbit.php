<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rabbit
 *
 * @ORM\Table(name="Rabbit", indexes={@ORM\Index(name="RabbitToPlayerFirst", columns={"idPlayerFirst"}), @ORM\Index(name="RabbitToPlayerLast", columns={"idPlayerLast"})})
 * @ORM\Entity
 */
class Rabbit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nameRabbit", type="string", length=80, nullable=true)
     */
    private $namerabbit;

    /**
     * @var \Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPlayerFirst", referencedColumnName="id")
     * })
     */
    private $idplayerfirst;

    /**
     * @var \Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPlayerLast", referencedColumnName="id")
     * })
     */
    private $idplayerlast;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime", nullable=false)
     */
    private $datecreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateUpdated", type="datetime", nullable=false)
     */
    private $dateupdated;

    /**
     * @var int
     *
     * @ORM\Column(name="nbPts", type="integer", nullable=false)
     */
    private $nbpts = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="isOver", type="boolean", nullable=false)
     */
    private $isover = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="imgRabbit", type="string", length=100, nullable=true)
     */
    private $imgrabbit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamerabbit(): ?string
    {
        return $this->namerabbit;
    }

    public function setNamerabbit(?string $namerabbit): self
    {
        $this->namerabbit = $namerabbit;

        return $this;
    }

    public function getIdplayerfirst(): ?Player
    {
        return $this->idplayerfirst;
    }

    public function setIdplayerfirst(Player $idplayerfirst): self
    {
        $this->idplayerfirst = $idplayerfirst;

        return $this;
    }

    public function getIdplayerlast(): ?Player
    {
        return $this->idplayerlast;
    }

    public function setIdplayerlast(?Player $idplayerlast): self
    {
        $this->idplayerlast = $idplayerlast;

        return $this;
    }

    public function getDatecreated(): ?\DateTimeInterface
    {
        return $this->datecreated;
    }

    public function setDatecreated(\DateTimeInterface $datecreated): self
    {
        $this->datecreated = $datecreated;

        return $this;
    }

    public function getDateupdated(): ?\DateTimeInterface
    {
        return $this->dateupdated;
    }

    public function setDateupdated(\DateTimeInterface $dateupdated): self
    {
        $this->dateupdated = $dateupdated;

        return $this;
    }

    public function getNbpts(): ?int
    {
        return $this->nbpts;
    }

    public function setNbpts(int $nbpts): self
    {
        $this->nbpts = $nbpts;

        return $this;
    }

    public function getIsover(): ?bool
    {
        return $this->isover;
    }

    public function setIsover(bool $isover): self
    {
        $this->isover = $isover;

        return $this;
    }

    public function getImgrabbit(): ?string
    {
        return $this->imgrabbit;
    }

    public function setImgrabbit(?string $imgrabbit): self
    {
        $this->imgrabbit = $imgrabbit;

        return $this;
    }


}
