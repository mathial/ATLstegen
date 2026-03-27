<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Table(name="User", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_2DA17977F85E0677", columns={"username"})})
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
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

    /**
     * New required method in Symfony >=5.3
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * For backward compatibility
     */
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user has at least ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getSalt()
    {
        // not needed for modern hashing algorithms (bcrypt, argon2)
    }

    public function eraseCredentials()
    {
        // clear temporary sensitive data if needed
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