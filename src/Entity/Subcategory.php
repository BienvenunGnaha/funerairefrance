<?php

namespace App\Entity;

use App\Repository\SubcategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubcategoryRepository::class)
 */
class Subcategory
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
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="subcategories")
     */
    private $catId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDisplayedInMenu;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="subCatId")
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
     * @ORM\Column(type="boolean")
     */
    private $customizable = 0;

    /**
     * @ORM\ManyToMany(targetEntity=Granit::class, mappedBy="subcats")
     */
    private $granits;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isText1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isText2;

    /**
     * @ORM\Column(type="text")
     */
    private $desscription;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="subcategories")
     */
    private $page;

    /**
     * @ORM\Column(type="boolean")
     */
    private $customizableBeforeOrder;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $needSteleFixation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $labelCustomStep;

    /**
     * @ORM\Column(type="boolean")
     */
    private $needPhotoStep;

    public function __construct()
    {
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

    public function getCatId(): ?Category
    {
        return $this->catId;
    }

    public function setCatId(?Category $catId): self
    {
        $this->catId = $catId;

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
            $product->setSubCatId($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getSubCatId() === $this) {
                $product->setSubCatId(null);
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

    public function getCustomizable(): ?bool
    {
        return $this->customizable;
    }

    public function setCustomizable(bool $customizable): self
    {
        $this->customizable = $customizable;

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
            $granit->addSubcat($this);
        }

        return $this;
    }

    public function removeGranit(Granit $granit): self
    {
        if ($this->granits->contains($granit)) {
            $this->granits->removeElement($granit);
            $granit->removeSubcat($this);
        }

        return $this;
    }

    public function getIsText1(): ?bool
    {
        return $this->isText1;
    }

    public function setIsText1(?bool $isText1): self
    {
        $this->isText1 = $isText1;

        return $this;
    }

    public function getIsText2(): ?bool
    {
        return $this->isText2;
    }

    public function setIsText2(?bool $isText2): self
    {
        $this->isText2 = $isText2;

        return $this;
    }

    public function getDesscription(): ?string
    {
        return $this->desscription;
    }

    public function setDesscription(string $desscription): self
    {
        $this->desscription = $desscription;

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

    public function getCustomizableBeforeOrder(): ?bool
    {
        return $this->customizableBeforeOrder;
    }

    public function setCustomizableBeforeOrder(bool $customizableBeforeOrder): self
    {
        $this->customizableBeforeOrder = $customizableBeforeOrder;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNeedSteleFixation(): ?bool
    {
        return $this->needSteleFixation;
    }

    public function setNeedSteleFixation(bool $needSteleFixation): self
    {
        $this->needSteleFixation = $needSteleFixation;

        return $this;
    }

    public function getLabelCustomStep(): ?string
    {
        return $this->labelCustomStep;
    }

    public function setLabelCustomStep(?string $labelCustomStep): self
    {
        $this->labelCustomStep = $labelCustomStep;

        return $this;
    }

    public function getNeedPhotoStep(): ?bool
    {
        return $this->needPhotoStep;
    }

    public function setNeedPhotoStep(bool $needPhotoStep): self
    {
        $this->needPhotoStep = $needPhotoStep;

        return $this;
    }
}
