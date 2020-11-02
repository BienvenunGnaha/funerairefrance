<?php

namespace App\Entity;

use App\Repository\ActiveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActiveRepository::class)
 */
class Active
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $slider;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlider(): ?bool
    {
        return $this->slider;
    }

    public function setSlider(bool $slider): self
    {
        $this->slider = $slider;

        return $this;
    }
}
