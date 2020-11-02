<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Address::class, inversedBy="orders")
     */
    private $addressId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     */
    private $userId;

    /**
     * @ORM\OneToMany(targetEntity=StatusOrder::class, mappedBy="orders")
     */
    private $statusId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Granit::class, inversedBy="orders")
     */
    private $granitId;

    /**
     * @ORM\Column(type="integer")
     */
    private $qty;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="orders")
     */
    private $productId;

    /**
     * @ORM\ManyToOne(targetEntity=TextOrder::class, inversedBy="orders")
     */
    private $text1;

    /**
     * @ORM\ManyToOne(targetEntity=TextOrder::class, inversedBy="t2Orders")
     */
    private $text2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $t1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $t2;

    /**
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="orders")
     */
    private $cart;

    /**
     * @ORM\ManyToOne(targetEntity=Gallery::class, inversedBy="orders")
     */
    private $gallery;

    /**
     * @ORM\ManyToOne(targetEntity=Fixation::class, inversedBy="orders")
     */
    private $fixation;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $metaData = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCustom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $colis;

    /**
     * @ORM\ManyToOne(targetEntity=Component::class, inversedBy="orders")
     */
    private $component;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $invoice;


    public function __construct()
    {
        $this->statusId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddressId(): ?Address
    {
        return $this->addressId;
    }

    public function setAddressId(?Address $addressId): self
    {
        $this->addressId = $addressId;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return Collection|StatusOrder[]
     */
    public function getStatusId(): Collection
    {
        return $this->statusId;
    }

    public function addStatusId(StatusOrder $statusId): self
    {
        if (!$this->statusId->contains($statusId)) {
            $this->statusId[] = $statusId;
            $statusId->setOrders($this);
        }

        return $this;
    }

    public function removeStatusId(StatusOrder $statusId): self
    {
        if ($this->statusId->contains($statusId)) {
            $this->statusId->removeElement($statusId);
            // set the owning side to null (unless already changed)
            if ($statusId->getOrders() === $this) {
                $statusId->setOrders(null);
            }
        }

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getGranitId(): ?Granit
    {
        return $this->granitId;
    }

    public function setGranitId(?Granit $granitId): self
    {
        $this->granitId = $granitId;

        return $this;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getProductId(): ?Product
    {
        return $this->productId;
    }

    public function setProductId(?Product $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getText1(): ?TextOrder
    {
        return $this->text1;
    }

    public function setText1(?TextOrder $text1): self
    {
        $this->text1 = $text1;

        return $this;
    }

    public function getText2(): ?TextOrder
    {
        return $this->text2;
    }

    public function setText2(?TextOrder $text2): self
    {
        $this->text2 = $text2;

        return $this;
    }

    public function getT1(): ?string
    {
        return $this->t1;
    }

    public function setT1(?string $t1): self
    {
        $this->t1 = $t1;

        return $this;
    }

    public function getT2(): ?string
    {
        return $this->t2;
    }

    public function setT2(?string $t2): self
    {
        $this->t2 = $t2;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function __toString(){
        return (string)$this->id;
    }

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): self
    {
        $this->gallery = $gallery;

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

    public function getMetaData()
    {
        return json_encode($this->metaData);
    }

    public function setMetaData($metaData): self
    {
        
        $this->metaData = $metaData;
        /*if(is_string($metaData)){
            $this->metaData = $metaData; 
        }*/

        return $this;
    }

    public function getIsCustom(): ?bool
    {
        return $this->isCustom;
    }

    public function setIsCustom(bool $isCustom): self
    {
        $this->isCustom = $isCustom;

        return $this;
    }

    public function getColis(): ?string
    {
        return $this->colis;
    }

    public function setColis(?string $colis): self
    {
        $this->colis = $colis;

        return $this;
    }

    public function getComponent(): ?Component
    {
        return $this->component;
    }

    public function setComponent(?Component $component): self
    {
        $this->component = $component;

        return $this;
    }

    public function getInvoice(): ?string
    {
        return $this->invoice;
    }

    public function setInvoice(?string $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

}
