<?php

namespace App\Entity;

use App\Repository\RequiredUpdatePmRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RequiredUpdatePmRepository::class)
 */
class RequiredUpdatePm
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
