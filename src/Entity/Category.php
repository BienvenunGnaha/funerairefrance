<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $displayOrder;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Subcategory::class, mappedBy="catId")
     */
    private $subcategories;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDisplayedInMenu;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="catId")
     */
    private $products;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $displayedSubcatDesc;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="categories")
     */
    private $page;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPlaque;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isUrne;

    /**
     * @ORM\ManyToMany(targetEntity=Granit::class, mappedBy="granits")
     */
    private $granits;

    /**
     * @ORM\Column(type="boolean")
     */
    private $seeAll;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $seeAllLabel;

    /**
     * @ORM\Column(type="boolean")
     */
    private $displayedAvis;

    public function __construct()
    {
        $this->subcategories = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->granits = new ArrayCollection();
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

    public function getDisplayOrder(): ?int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(?int $displayOrder): self
    {
        $this->displayOrder = $displayOrder;

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

    /**
     * @return Collection|Subcategory[]
     */
    public function getSubcategories(): Collection
    {
        return $this->subcategories;
    }

    public function addSubcategory(Subcategory $subcategory): self
    {
        if (!$this->subcategories->contains($subcategory)) {
            $this->subcategories[] = $subcategory;
            $subcategory->setCatId($this);
        }

        return $this;
    }

    public function removeSubcategory(Subcategory $subcategory): self
    {
        if ($this->subcategories->contains($subcategory)) {
            $this->subcategories->removeElement($subcategory);
            // set the owning side to null (unless already changed)
            if ($subcategory->getCatId() === $this) {
                $subcategory->setCatId(null);
            }
        }

        return $this;
    }

    public function getIsDisplayedInMenu(): ?bool
    {
        return $this->isDisplayedInMenu;
    }

    public function setIsDisplayedInMenu(bool $isDisplayedInMenu): self
    {
        $this->isDisplayedInMenu = $isDisplayedInMenu;

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
            $product->setCatId($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getCatId() === $this) {
                $product->setCatId(null);
            }
        }

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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->name;
        // to show the id of the Category in the select
        // return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDisplayedSubcatDesc(): ?bool
    {
        return $this->displayedSubcatDesc;
    }

    public function setDisplayedSubcatDesc(bool $displayedSubcatDesc): self
    {
        $this->displayedSubcatDesc = $displayedSubcatDesc;

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

    public function getIsPlaque(): ?bool
    {
        return $this->isPlaque;
    }

    public function setIsPlaque(bool $isPlaque): self
    {
        $this->isPlaque = $isPlaque;

        return $this;
    }

    public function getIsUrne(): ?bool
    {
        return $this->isUrne;
    }

    public function setIsUrne(bool $isUrne): self
    {
        $this->isUrne = $isUrne;

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
            $granit->addGranit($this);
        }

        return $this;
    }

    public function removeGranit(Granit $granit): self
    {
        if ($this->granits->contains($granit)) {
            $this->granits->removeElement($granit);
            $granit->removeGranit($this);
        }

        return $this;
    }

    public function getSeeAll(): ?bool
    {
        return $this->seeAll;
    }

    public function setSeeAll(bool $seeAll): self
    {
        $this->seeAll = $seeAll;

        return $this;
    }

    public function getSeeAllLabel(): ?string
    {
        return $this->seeAllLabel;
    }

    public function setSeeAllLabel(string $seeAllLabel): self
    {
        $this->seeAllLabel = $seeAllLabel;

        return $this;
    }

    public function getDisplayedAvis(): ?bool
    {
        return $this->displayedAvis;
    }

    public function setDisplayedAvis(bool $displayedAvis): self
    {
        $this->displayedAvis = $displayedAvis;

        return $this;
    }
}
