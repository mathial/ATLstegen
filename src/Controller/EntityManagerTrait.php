<?php
// src/Controller/EntityManagerTrait.php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;

trait EntityManagerTrait
{
    private EntityManagerInterface $entityManager;

    /**
     * This setter must be called by the controller constructor.
     */
    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->entityManager = $em;
    }

    /**
     * Get the EntityManager instance.
     */
    protected function em(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}