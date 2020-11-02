<?php

namespace App\Entity;

use App\Repository\GalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GalleryRepository::class)
 */
class Gallery
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity=Granit::class, inversedBy="galleries")
     */
    private $granit;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="galleries")
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $pricepro;

    /**
     * @ORM\OneToMany(targetEntity=Devis::class, mappedBy="gallery")
     */
    private $devis;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="galleriesTailored")
     */
    private $productTailored;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="gallery")
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity=Fixation::class, inversedBy="galleries")
     */
    private $fixation;

    /**
     * @ORM\ManyToOne(targetEntity=Stele::class, inversedBy="galleries")
     */
    private $stele;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="galleriesFixation")
     */
    private $prodFixation;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $metaData = [];

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="galleriesThreeDay")
     */
    private $productThreeDay;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
        $this->orders = new ArrayCollection();
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

    public function setPhoto(?string $photo): self
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function __toString(){
        return $this->name;
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

    public function getPricepro(): ?int
    {
        return $this->pricepro;
    }

    public function setPricepro(int $pricepro): self
    {
        $this->pricepro = $pricepro;

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
            $devi->setGallery($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->contains($devi)) {
            $this->devis->removeElement($devi);
            // set the owning side to null (unless already changed)
            if ($devi->getGallery() === $this) {
                $devi->setGallery(null);
            }
        }

        return $this;
    }

    public function getProductTailored(): ?Product
    {
        return $this->productTailored;
    }

    public function setProductTailored(?Product $productTailored): self
    {
        $this->productTailored = $productTailored;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setGallery($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getGallery() === $this) {
                $order->setGallery(null);
            }
        }

        return $this;
    }

    public function getFixation(): ?Fixation
    {
        return $this->fixation;
    }

    public function setFixation(?Fixation $fixation): self
    {
        $this->fixation = $fixation;

        return $this;
    }

    public function getStele(): ?Stele
    {
        return $this->stele;
    }

    public function setStele(?Stele $stele): self
    {
        $this->stele = $stele;

        return $this;
    }

    public function getProdFixation(): ?Product
    {
        return $this->prodFixation;
    }

    public function setProdFixation(?Product $prodFixation): self
    {
        $this->prodFixation = $prodFixation;

        return $this;
    }

    public function getMetaData()
    {
        return json_encode($this->metaData);
    }

    public function setMetaData($metaData): self
    {
        $this->metaData = $metaData;

        return $this;
    }

    public function getProductThreeDay(): ?Product
    {
        return $this->productThreeDay;
    }

    public function setProductThreeDay(?Product $productThreeDay): self
    {
        $this->productThreeDay = $productThreeDay;

        return $this;
    }
}
