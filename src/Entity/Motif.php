<?php

namespace App\Entity;

use App\Repository\MotifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MotifRepository::class)
 */
class Motif
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
     * @ORM\Column(type="json", nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=MotifGallery::class, mappedBy="motif")
     */
    private $motifGalleries;

    public function __construct()
    {
        $this->motifGalleries = new ArrayCollection();
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

    public function getPhoto(): ?array
    {
        return $this->photo;
    }

    public function setPhoto(?array $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|MotifGallery[]
     */
    public function getMotifGalleries(): Collection
    {
        return $this->motifGalleries;
    }

    public function addMotifGallery(MotifGallery $motifGallery): self
    {
        if (!$this->motifGalleries->contains($motifGallery)) {
            $this->motifGalleries[] = $motifGallery;
            $motifGallery->setMotif($this);
        }

        return $this;
    }

    public function removeMotifGallery(MotifGallery $motifGallery): self
    {
        if ($this->motifGalleries->contains($motifGallery)) {
            $this->motifGalleries->removeElement($motifGallery);
            // set the owning side to null (unless already changed)
            if ($motifGallery->getMotif() === $this) {
                $motifGallery->setMotif(null);
            }
        }

        return $this;
    }
}
