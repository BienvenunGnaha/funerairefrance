<?php

namespace App\Entity;

use App\Repository\BookPdfRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookPdfRepository::class)
 */
class BookPdf
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\OneToMany(targetEntity=Guide::class, mappedBy="book")
     */
    private $guides;

    public function __construct()
    {
        $this->guides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return Collection|Guide[]
     */
    public function getGuides(): Collection
    {
        return $this->guides;
    }

    public function addGuide(Guide $guide): self
    {
        if (!$this->guides->contains($guide)) {
            $this->guides[] = $guide;
            $guide->setBook($this);
        }

        return $this;
    }

    public function removeGuide(Guide $guide): self
    {
        if ($this->guides->contains($guide)) {
            $this->guides->removeElement($guide);
            // set the owning side to null (unless already changed)
            if ($guide->getBook() === $this) {
                $guide->setBook(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
