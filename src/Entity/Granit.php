<?php

namespace App\Entity;

use App\Repository\GranitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GranitRepository::class)
 */
class Granit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Subcategory::class, inversedBy="granits")
     */
    private $subcats;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity=Stele::class, mappedBy="granit")
     */
    private $steles;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="granitId")
     */
    private $orders;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=Devis::class, mappedBy="granit")
     */
    private $devis;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, inversedBy="granits")
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $pricepro;

    /**
     * @ORM\ManyToOne(targetEntity=Config::class, inversedBy="granits")
     */
    private $tva;

    /**
     * @ORM\OneToMany(targetEntity=Gallery::class, mappedBy="granit")
     */
    private $galleries;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="granits")
     */
    private $granits;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="granitTailored")
     */
    private $products;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isGravure;

    public function __construct()
    {
        $this->subcats = new ArrayCollection();
        $this->steles = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->devis = new ArrayCollection();
        $this->product = new ArrayCollection();
        $this->galleries = new ArrayCollection();
        $this->granits = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->productss = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Subcategory[]
     */
    public function getSubcats(): Collection
    {
        return $this->subcats;
    }

    public function addSubcat(Subcategory $subcat): self
    {
        if (!$this->subcats->contains($subcat)) {
            $this->subcats[] = $subcat;
        }

        return $this;
    }

    public function removeSubcat(Subcategory $subcat): self
    {
        if ($this->subcats->contains($subcat)) {
            $this->subcats->removeElement($subcat);
        }

        return $this;
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
            $stele->setGranit($this);
        }

        return $this;
    }

    public function removeStele(Stele $stele): self
    {
        if ($this->steles->contains($stele)) {
            $this->steles->removeElement($stele);
            // set the owning side to null (unless already changed)
            if ($stele->getGranit() === $this) {
                $stele->setGranit(null);
            }
        }

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
            $order->setGranitId($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getGranitId() === $this) {
                $order->setGranitId(null);
            }
        }

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
            $devi->setGranit($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->contains($devi)) {
            $this->devis->removeElement($devi);
            // set the owning side to null (unless already changed)
            if ($devi->getGranit() === $this) {
                $devi->setGranit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->contains($product)) {
            $this->product->removeElement($product);
        }

        return $this;
    }


    public function addProducts(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProducts(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
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
            $gallery->setGranit($this);
        }

        return $this;
    }

    public function removeGallery(Gallery $gallery): self
    {
        if ($this->galleries->contains($gallery)) {
            $this->galleries->removeElement($gallery);
            // set the owning side to null (unless already changed)
            if ($gallery->getGranit() === $this) {
                $gallery->setGranit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getGranits(): Collection
    {
        return $this->granits;
    }

    public function addGranit(Category $granit): self
    {
        if (!$this->granits->contains($granit)) {
            $this->granits[] = $granit;
        }

        return $this;
    }

    public function removeGranit(Category $granit): self
    {
        if ($this->granits->contains($granit)) {
            $this->granits->removeElement($granit);
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function getIsGravure(): ?bool
    {
        return $this->isGravure;
    }

    public function setIsGravure(bool $isGravure): self
    {
        $this->isGravure = $isGravure;

        return $this;
    }
}
