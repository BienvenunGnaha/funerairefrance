<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $allowedCondition;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $size;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPriceOff;

    /**
     * @ORM\Column(type="integer")
     */
    private $cprice;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pprice;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $view = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $allowSeo = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metaKeys;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metaDesc;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $catId;

    /**
     * @ORM\ManyToOne(targetEntity=Subcategory::class, inversedBy="products")
     */
    private $subCatId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $inSlider = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $gallery = [];

    /**
     * @ORM\OneToMany(targetEntity=Devis::class, mappedBy="productId")
     */
    private $devis;

    /**
     * @ORM\Column(type="integer")
     */
    private $pricepro;

    /**
     * @ORM\ManyToOne(targetEntity=Config::class, inversedBy="products")
     */
    private $tva;

    /**
     * @ORM\ManyToMany(targetEntity=Granit::class,  mappedBy="product")
     */
    private $granits;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="productId")
     */
    private $orders;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $guaranted;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fixation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeGranit;

    /**
     * @ORM\Column(type="integer", nullable=true, nullable=true)
     */
    private $nbreColis;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matiere;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $contenance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motifUrne;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $eligibleUrne;

    /**
     * @ORM\OneToMany(targetEntity=Rating::class, mappedBy="product")
     */
    private $ratings;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sale;

    /**
     * @ORM\Column(type="integer")
     */
    private $shCost = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $shTime = 1;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity=Wishlist::class, mappedBy="product")
     */
    private $wishlists;

    /**
     * @ORM\OneToMany(targetEntity=Gallery::class, mappedBy="product")
     */
    private $galleries;

    /**
     * @ORM\ManyToOne(targetEntity=GranitPlaque::class, inversedBy="products")
     */
    private $granitPlaque;

    /**
     * @ORM\ManyToOne(targetEntity=Eligible::class, inversedBy="products")
     */
    private $eligible;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberTextCustomize;

    /**
     * @ORM\ManyToOne(targetEntity=Fixation::class, inversedBy="products")
     */
    private $fixxation;

    /**
     * @ORM\ManyToOne(targetEntity=FormPlaque::class, inversedBy="products")
     */
    private $formPlaque;

    /**
     * @ORM\ManyToOne(targetEntity=MotifUrne::class, inversedBy="products")
     */
    private $UrneMotif;

    /**
     * @ORM\ManyToOne(targetEntity=ColorProduct::class, inversedBy="products")
     */
    private $colorProduct;

    /**
     * @ORM\ManyToOne(targetEntity=TypeUrne::class, inversedBy="products")
     */
    private $typeUrne;

    /**
     * @ORM\ManyToOne(targetEntity=ThemePlaque::class, inversedBy="products")
     */
    private $themePlaque;

    /**
     * @ORM\ManyToMany(targetEntity=Granit::class, inversedBy="products")
     */
    private $granitTailored;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priceTailored;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priceproTailored;

    /**
     * @ORM\OneToMany(targetEntity=Gallery::class, mappedBy="productTailored")
     */
    private $galleriesTailored;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photoPlaque;

    /**
     * @ORM\OneToMany(targetEntity=Gallery::class, mappedBy="prodFixation")
     */
    private $galleriesFixation;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $metaData = [];

    /**
     * @ORM\OneToMany(targetEntity=Gallery::class, mappedBy="productThreeDay")
     */
    private $galleriesThreeDay;

    /**
     * @ORM\Column(type="boolean")
     */
    private $needContact;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="products")
     */
    private $page;

    /**
     * @ORM\ManyToMany(targetEntity=Component::class, inversedBy="products")
     */
    private $components;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
        $this->granits = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->wishlists = new ArrayCollection();
        $this->galleries = new ArrayCollection();
        $this->granitTailored = new ArrayCollection();
        $this->galleriesTailored = new ArrayCollection();
        $this->galleriesFixation = new ArrayCollection();
        $this->galleriesThreeDay = new ArrayCollection();
        $this->components = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }



    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

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

    public function getAllowedCondition(): ?bool
    {
        return $this->allowedCondition;
    }

    public function setAllowedCondition(?bool $allowedCondition): self
    {
        $this->allowedCondition = $allowedCondition;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getIsPriceOff(): ?bool
    {
        return $this->isPriceOff;
    }

    public function setIsPriceOff(bool $isPriceOff): self
    {
        $this->isPriceOff = $isPriceOff;

        return $this;
    }

    public function getCprice(): ?int
    {
        return $this->cprice;
    }

    public function setCprice(int $cprice): self
    {
        $this->cprice = $cprice;

        return $this;
    }

    public function getPprice(): ?int
    {
        return $this->pprice;
    }

    public function setPprice(?int $pprice): self
    {
        $this->pprice = $pprice;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getView(): ?int
    {
        return $this->view;
    }

    public function setView(int $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function getAllowSeo(): ?bool
    {
        return $this->allowSeo;
    }

    public function setAllowSeo(bool $allowSeo): self
    {
        $this->allowSeo = $allowSeo;

        return $this;
    }

    public function getMetaKeys(): ?string
    {
        return $this->metaKeys;
    }

    public function setMetaKeys(?string $metaKeys): self
    {
        $this->metaKeys = $metaKeys;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMetaDesc(): ?string
    {
        return $this->metaDesc;
    }

    public function setMetaDesc(?string $metaDesc): self
    {
        $this->metaDesc = $metaDesc;

        return $this;
    }

    public function getCatId(): ?Category
    {
        return $this->catId;
    }

    public function setCatId(?Category $catId): self
    {
        $this->catId = $catId;

        return $this;
    }

    public function getSubCatId(): ?Subcategory
    {
        return $this->subCatId;
    }

    public function setSubCatId(?Subcategory $subCatId): self
    {
        $this->subCatId = $subCatId;

        return $this;
    }

    public function getInSlider(): ?bool
    {
        return $this->inSlider;
    }

    public function setInSlider(bool $inSlider): self
    {
        $this->inSlider = $inSlider;

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

    public function getGallery(): ?array
    {
        return $this->gallery;
    }

    public function setGallery(?array $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->name;
        // to show the id of the Category in the select
        // return $this->id;
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
            $devi->setProductId($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->contains($devi)) {
            $this->devis->removeElement($devi);
            // set the owning side to null (unless already changed)
            if ($devi->getProductId() === $this) {
                $devi->setProductId(null);
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
            $granit->addProduct($this);
        }

        return $this;
    }

    public function removeGranit(Granit $granit): self
    {
        if ($this->granits->contains($granit)) {
            $this->granits->removeElement($granit);
            $granit->removeProduct($this);
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
            $order->setProductId($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getProductId() === $this) {
                $order->setProductId(null);
            }
        }

        return $this;
    }

    public function getGuaranted(): ?string
    {
        return $this->guaranted;
    }

    public function setGuaranted(?string $guaranted): self
    {
        $this->guaranted = $guaranted;

        return $this;
    }

    public function getFixation(): ?string
    {
        return $this->fixation;
    }

    public function setFixation(?string $fixation): self
    {
        $this->fixation = $fixation;

        return $this;
    }

    public function getTypeGranit(): ?string
    {
        return $this->typeGranit;
    }

    public function setTypeGranit(?string $typeGranit): self
    {
        $this->typeGranit = $typeGranit;

        return $this;
    }

    public function getNbreColis(): ?int
    {
        return $this->nbreColis;
    }

    public function setNbreColis(?int $nbreColis): self
    {
        $this->nbreColis = $nbreColis;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getContenance(): ?string
    {
        return $this->contenance;
    }

    public function setContenance(?string $contenance): self
    {
        $this->contenance = $contenance;

        return $this;
    }

    public function getMotifUrne(): ?string
    {
        return $this->motifUrne;
    }

    public function setMotifUrne(?string $motifUrne): self
    {
        $this->motifUrne = $motifUrne;

        return $this;
    }

    public function getEligibleUrne(): ?string
    {
        return $this->eligibleUrne;
    }

    public function setEligibleUrne(?string $eligibleUrne): self
    {
        $this->eligibleUrne = $eligibleUrne;

        return $this;
    }

    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setProduct($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);
            // set the owning side to null (unless already changed)
            if ($rating->getProduct() === $this) {
                $rating->setProduct(null);
            }
        }

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSale(): ?int
    {
        return $this->sale;
    }

    public function setSale(?int $sale): self
    {
        $this->sale = $sale;

        return $this;
    }

    public function getShCost(): ?int
    {
        return $this->shCost;
    }

    public function setShCost(int $sh_cost): self
    {
        $this->shCost = $sh_cost;

        return $this;
    }

    public function getShTime(): ?int
    {
        return $this->shTime;
    }

    public function setShTime(int $shTime): self
    {
        $this->shTime = $shTime;

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

    /**
     * @return Collection|Wishlist[]
     */
    public function getWishlists(): Collection
    {
        return $this->wishlists;
    }

    public function addWishlist(Wishlist $wishlist): self
    {
        if (!$this->wishlists->contains($wishlist)) {
            $this->wishlists[] = $wishlist;
            $wishlist->addProduct($this);
        }

        return $this;
    }

    public function removeWishlist(Wishlist $wishlist): self
    {
        if ($this->wishlists->contains($wishlist)) {
            $this->wishlists->removeElement($wishlist);
            $wishlist->removeProduct($this);
        }

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
            $gallery->setProduct($this);
        }

        return $this;
    }

    public function removeGallery(Gallery $gallery): self
    {
        if ($this->galleries->contains($gallery)) {
            $this->galleries->removeElement($gallery);
            // set the owning side to null (unless already changed)
            if ($gallery->getProduct() === $this) {
                $gallery->setProduct(null);
            }
        }

        return $this;
    }

    public function getGranitPlaque(): ?GranitPlaque
    {
        return $this->granitPlaque;
    }

    public function setGranitPlaque(?GranitPlaque $granitPlaque): self
    {
        $this->granitPlaque = $granitPlaque;

        return $this;
    }

    public function getEligible(): ?Eligible
    {
        return $this->eligible;
    }

    public function setEligible(?Eligible $eligible): self
    {
        $this->eligible = $eligible;

        return $this;
    }

    public function getNumberTextCustomize(): ?int
    {
        return $this->numberTextCustomize;
    }

    public function setNumberTextCustomize(int $numberTextCustomize): self
    {
        $this->numberTextCustomize = $numberTextCustomize;

        return $this;
    }


    public function getFixxation(): ?Fixation
    {
        return $this->fixxation;
    } 

    public function setFixxation(?Fixation $fixxation): self
    {
        $this->fixxation = $fixxation;

        return $this;
    }

    public function getFormPlaque(): ?FormPlaque
    {
        return $this->formPlaque;
    }

    public function setFormPlaque(?FormPlaque $formPlaque): self
    {
        $this->formPlaque = $formPlaque;

        return $this;
    }

    public function getUrneMotif(): ?MotifUrne
    {
        return $this->UrneMotif;
    }

    public function setUrneMotif(?MotifUrne $UrneMotif): self
    {
        $this->UrneMotif = $UrneMotif;

        return $this;
    }

    public function getColorProduct(): ?ColorProduct
    {
        return $this->colorProduct;
    }

    public function setColorProduct(?ColorProduct $colorProduct): self
    {
        $this->colorProduct = $colorProduct;

        return $this;
    }

    public function getTypeUrne(): ?TypeUrne
    {
        return $this->typeUrne;
    }

    public function setTypeUrne(?TypeUrne $typeUrne): self
    {
        $this->typeUrne = $typeUrne;

        return $this;
    }

    public function getThemePlaque(): ?ThemePlaque
    {
        return $this->themePlaque;
    }

    public function setThemePlaque(?ThemePlaque $themePlaque): self
    {
        $this->themePlaque = $themePlaque;

        return $this;
    }

    /**
     * @return Collection|Granit[]
     */
    public function getGranitTailored(): Collection
    {
        return $this->granitTailored;
    }

    public function addGranitTailored(Granit $granitTailored): self
    {
        if (!$this->granitTailored->contains($granitTailored)) {
            $this->granitTailored[] = $granitTailored;
        }

        return $this;
    }

    public function removeGranitTailored(Granit $granitTailored): self
    {
        if ($this->granitTailored->contains($granitTailored)) {
            $this->granitTailored->removeElement($granitTailored);
        }

        return $this;
    }

    public function getPriceTailored(): ?int
    {
        return $this->priceTailored;
    }

    public function setPriceTailored(?int $priceTailored): self
    {
        $this->priceTailored = $priceTailored;

        return $this;
    }

    public function getPriceproTailored(): ?int
    {
        return $this->priceproTailored;
    }

    public function setPriceproTailored(?int $priceproTailored): self
    {
        $this->priceproTailored = $priceproTailored;

        return $this;
    }

    /**
     * @return Collection|Gallery[]
     */
    public function getGalleriesTailored(): Collection
    {
        return $this->galleriesTailored;
    }

    public function addGalleriesTailored(Gallery $galleriesTailored): self
    {
        if (!$this->galleriesTailored->contains($galleriesTailored)) {
            $this->galleriesTailored[] = $galleriesTailored;
            $galleriesTailored->setProductTailored($this);
        }

        return $this;
    }

    public function removeGalleriesTailored(Gallery $galleriesTailored): self
    {
        if ($this->galleriesTailored->contains($galleriesTailored)) {
            $this->galleriesTailored->removeElement($galleriesTailored);
            // set the owning side to null (unless already changed)
            if ($galleriesTailored->getProductTailored() === $this) {
                $galleriesTailored->setProductTailored(null);
            }
        }

        return $this;
    }

    public function getPhotoPlaque(): ?string
    {
        return $this->photoPlaque;
    }

    public function setPhotoPlaque(?string $photoPlaque): self
    {
        $this->photoPlaque = $photoPlaque;

        return $this;
    }

    /**
     * @return Collection|Gallery[]
     */
    public function getGalleriesFixation(): Collection
    {
        return $this->galleriesFixation;
    }

    public function addGalleriesFixation(Gallery $galleriesFixation): self
    {
        if (!$this->galleriesFixation->contains($galleriesFixation)) {
            $this->galleriesFixation[] = $galleriesFixation;
            $galleriesFixation->setProdFixation($this);
        }

        return $this;
    }

    public function removeGalleriesFixation(Gallery $galleriesFixation): self
    {
        if ($this->galleriesFixation->contains($galleriesFixation)) {
            $this->galleriesFixation->removeElement($galleriesFixation);
            // set the owning side to null (unless already changed)
            if ($galleriesFixation->getProdFixation() === $this) {
                $galleriesFixation->setProdFixation(null);
            }
        }

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

    /**
     * @return Collection|Gallery[]
     */
    public function getGalleriesThreeDay(): Collection
    {
        return $this->galleriesThreeDay;
    }

    public function addGalleriesThreeDay(Gallery $galleriesThreeDay): self
    {
        if (!$this->galleriesThreeDay->contains($galleriesThreeDay)) {
            $this->galleriesThreeDay[] = $galleriesThreeDay;
            $galleriesThreeDay->setProductThreeDay($this);
        }

        return $this;
    }

    public function removeGalleriesThreeDay(Gallery $galleriesThreeDay): self
    {
        if ($this->galleriesThreeDay->contains($galleriesThreeDay)) {
            $this->galleriesThreeDay->removeElement($galleriesThreeDay);
            // set the owning side to null (unless already changed)
            if ($galleriesThreeDay->getProductThreeDay() === $this) {
                $galleriesThreeDay->setProductThreeDay(null);
            }
        }

        return $this;
    }

    public function getNeedContact(): ?bool
    {
        return $this->needContact;
    }

    public function setNeedContact(bool $needContact): self
    {
        $this->needContact = $needContact;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return Collection|Component[]
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(Component $component): self
    {
        if (!$this->components->contains($component)) {
            $this->components[] = $component;
        }

        return $this;
    }

    public function removeComponent(Component $component): self
    {
        if ($this->components->contains($component)) {
            $this->components->removeElement($component);
        }

        return $this;
    }
}
