<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Player
 *
 * @ORM\Table(name="Player", indexes={@ORM\Index(name="PlayerToCountry", columns={"idCountry"})})
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
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
     * @ORM\Column(name="nameShort", type="string", length=50, nullable=true)
     */
    private $nameshort;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nameLong", type="string", length=100, nullable=true)
     */
    private $namelong;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="birthdate", type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="avatar", type="string", length=200, nullable=true)
     */
    private $avatar;

    /**
     * @var string|null
     *
     * @ORM\Column(name="details", type="text", length=65535, nullable=true)
     */
    private $details;

    /**
     * @var bool
     *
     * @ORM\Column(name="activeTennis", type="boolean", nullable=false, options={"default"="1"})
     */
    private $activetennis = '1';

    /**
     * @var int|null
     *
     * @ORM\Column(name="initialRatingTennis", type="integer", nullable=true)
     */
    private $initialratingtennis = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="initialRatingPaddle", type="integer", nullable=true)
     */
    private $initialratingpaddle = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="activePaddle", type="boolean", nullable=false)
     */
    private $activepaddle = '0';

    /**
     * @var \Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCountry", referencedColumnName="id")
     * })
     */
    private $idcountry;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameshort(): ?string
    {
        return $this->nameshort;
    }

    public function setNameshort(?string $nameshort): self
    {
        $this->nameshort = $nameshort;

        return $this;
    }

    public function getNamelong(): ?string
    {
        return $this->namelong;
    }

    public function setNamelong(?string $namelong): self
    {
        $this->namelong = $namelong;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getActivetennis(): ?bool
    {
        return $this->activetennis;
    }

    public function setActivetennis(bool $activetennis): self
    {
        $this->activetennis = $activetennis;

        return $this;
    }

    public function getInitialratingtennis(): ?int
    {
        return $this->initialratingtennis;
    }

    public function setInitialratingtennis(?int $initialratingtennis): self
    {
        $this->initialratingtennis = $initialratingtennis;

        return $this;
    }

    public function getInitialratingpaddle(): ?int
    {
        return $this->initialratingpaddle;
    }

    public function setInitialratingpaddle(?int $initialratingpaddle): self
    {
        $this->initialratingpaddle = $initialratingpaddle;

        return $this;
    }

    public function getActivepaddle(): ?bool
    {
        return $this->activepaddle;
    }

    public function setActivepaddle(bool $activepaddle): self
    {
        $this->activepaddle = $activepaddle;

        return $this;
    }
    
    public function getCountry(): ?Country
    {
        return $this->idcountry;
    }

    public function setCountry(?Country $idcountry): self
    {
        $this->idcountry = $idcountry;

        return $this;
    }

    public function getIdcountry(): ?Country
    {
        return $this->idcountry;
    }

    public function setIdcountry(?Country $idcountry): self
    {
        $this->idcountry = $idcountry;

        return $this;
    }


}
