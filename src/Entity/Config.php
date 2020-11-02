<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigRepository::class)
 */
class Config
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
    private $confKey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $required;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="tva")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=Granit::class, mappedBy="tva")
     */
    private $granits;

    /**
     * @ORM\OneToMany(targetEntity=Stele::class, mappedBy="tva")
     */
    private $steles;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->granits = new ArrayCollection();
        $this->steles = new ArrayCollection();
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

    public function getConfKey(): ?string
    {
        return $this->confKey;
    }

    public function setConfKey(string $confKey): self
    {
        $this->confKey = $confKey;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setTva($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getTva() === $this) {
                $product->setTva(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->name;
    }

    /**
     * @return Collection|Granit[]
     */
    public function getGranits(): Collection
    {
        return $this->granits;
    }

    public function addGranit(Granit $granit): self
    {
        if (!$this->granits->contains($granit)) {
            $this->granits[] = $granit;
            $granit->setTva($this);
        }

        return $this;
    }

    public function removeGranit(Granit $granit): self
    {
        if ($this->granits->contains($granit)) {
            $this->granits->removeElement($granit);
            // set the owning side to null (unless already changed)
            if ($granit->getTva() === $this) {
                $granit->setTva(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Stele[]
     */
    public function getSteles(): Collection
    {
        return $this->steles;
    }

    public function addStele(Stele $stele): self
    {
        if (!$this->steles->contains($stele)) {
            $this->steles[] = $stele;
            $stele->setTva($this);
        }

        return $this;
    }

    public function removeStele(Stele $stele): self
    {
        if ($this->steles->contains($stele)) {
            $this->steles->removeElement($stele);
            // set the owning side to null (unless already changed)
            if ($stele->getTva() === $this) {
                $stele->setTva(null);
            }
        }

        return $this;
    }
}
