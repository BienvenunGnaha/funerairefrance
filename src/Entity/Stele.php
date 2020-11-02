<?php

namespace App\Entity;

use App\Repository\SteleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SteleRepository::class)
 */
class Stele
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
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity=Granit::class, inversedBy="steles")
     */
    private $granit;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=Devis::class, mappedBy="stele")
     */
    private $devis;

    /**
     * @ORM\Column(type="integer")
     */
    private $pricepro;

    /**
     * @ORM\ManyToOne(targetEntity=Config::class, inversedBy="steles")
     */
    private $tva;

    /**
     * @ORM\OneToMany(targetEntity=Gallery::class, mappedBy="stele")
     */
    private $galleries;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
        $this->galleries = new ArrayCollection();
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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getGranit(): ?Granit
    {
        return $this->granit;
    }

    public function setGranit(?Granit $granit): self
    {
        $this->granit = $granit;

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->name;
        // to show the id of the Category in the select
        // return $this->id;
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

    /**
     * @return Collection|Devis[]
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(Devis $devi): self
    {
        if (!$this->devis->contains($devi)) {
            $this->devis[] = $devi;
            $devi->setStele($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->contains($devi)) {
            $this->devis->removeElement($devi);
            // set the owning side to null (unless already changed)
            if ($devi->getStele() === $this) {
                $devi->setStele(null);
            }
        }

        return $this;
    }

    public function getPricepro(): ?int
    {
        return $this->pricepro;
    }

    public function setPricepro(int $pricepro): self
    {
        $this->pricepro = $pricepro;

        return $this;
    }

    public function getTva(): ?Config
    {
        return $this->tva;
    }

    public function setTva(?Config $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * @return Collection|Gallery[]
     */
    public function getGalleries(): Collection
    {
        return $this->galleries;
    }

    public function addGallery(Gallery $gallery): self
    {
        if (!$this->galleries->contains($gallery)) {
            $this->galleries[] = $gallery;
            $gallery->setStele($this);
        }

        return $this;
    }

    public function removeGallery(Gallery $gallery): self
    {
        if ($this->galleries->contains($gallery)) {
            $this->galleries->removeElement($gallery);
            // set the owning side to null (unless already changed)
            if ($gallery->getStele() === $this) {
                $gallery->setStele(null);
            }
        }

        return $this;
    }
}
