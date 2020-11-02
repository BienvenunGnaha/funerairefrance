<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DevisRepository::class)
 */
class Devis
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity=Granit::class, inversedBy="devis")
     */
    private $granit;

    /**
     * @ORM\ManyToOne(targetEntity=Stele::class, inversedBy="devis")
     */
    private $stele;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="devis")
     */
    private $productId;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $svg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $download;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalTtc;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="devis")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $plan;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subscription;

    /**
     * @ORM\ManyToOne(targetEntity=PaymentMethod::class, inversedBy="devis")
     */
    private $paymentMethod;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cycle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $requiredUpdatePm = false;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceText = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceImage = 0;

    /**
     * @ORM\Column(type="json")
     */
    private $metaData = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled = false;

    /**
     * @ORM\ManyToOne(targetEntity=Method::class, inversedBy="devis")
     */
    private $method;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="devis")
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $secondFeePayed = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $firstFeePayed = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $nextPaymentAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cycleTotal;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="devis")
     */
    private $messages;

    /**
     * @ORM\ManyToOne(targetEntity=Gallery::class, inversedBy="devis")
     */
    private $gallery;

    /**
     * @ORM\ManyToOne(targetEntity=Fixation::class, inversedBy="devis")
     */
    private $fixation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otherFees;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $discount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otherFeesPro;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $colis;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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

    public function getStele(): ?Stele
    {
        return $this->stele;
    }

    public function setStele(?Stele $stele): self
    {
        $this->stele = $stele;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

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

    public function getProductId(): ?Product
    {
        return $this->productId;
    }

    public function setProductId(?Product $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getSvg(): ?string
    {
        return $this->svg;
    }

    public function setSvg(?string $svg): self
    {
        $this->svg = $svg;

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->name;
        // to show the id of the Category in the select
        // return $this->id;
    }

    public function getDownload(): ?string
    {
        return $this->download;
    }

    public function setDownload(?string $download): self
    {
        $this->download = $download;

        return $this;
    }

    public function getTotalTtc(): ?int
    {
        return $this->totalTtc;
    }

    public function setTotalTtc(?int $totalTtc): self
    {
        $this->totalTtc = $totalTtc;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPm(): ?string
    {
        return $this->pm;
    }

    public function setPm(?string $pm): self
    {
        $this->pm = $pm;

        return $this;
    }

    public function getPlan(): ?string
    {
        return $this->plan;
    }

    public function setPlan(?string $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function getSubscription(): ?string
    {
        return $this->subscription;
    }

    public function setSubscription(?string $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?PaymentMethod $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getCycle(): ?int
    {
        return $this->cycle;
    }

    public function setCycle(?int $cycle): self
    {
        $this->cycle = $cycle;

        return $this;
    }

    public function getRequiredUpdatePm(): ?bool
    {
        return $this->requiredUpdatePm;
    }

    public function setRequiredUpdatePm(bool $requiredUpdatePm): self
    {
        $this->requiredUpdatePm = $requiredUpdatePm;

        return $this;
    }

    public function getPriceText(): ?int
    {
        return $this->priceText;
    }

    public function setPriceText(int $priceText): self
    {
        $this->priceText = $priceText;

        return $this;
    }

    public function getPriceImage(): ?int
    {
        return $this->priceImage;
    }

    public function setPriceImage(int $priceImage): self
    {
        $this->priceImage = $priceImage;

        return $this;
    }

    public function getMetaData(): ?array
    {
        return $this->metaData;
    }

    public function setMetaData($metaData): self
    {
        if(is_string($metaData)){
            $this->metaData = json_decode($metaData, true);
        }
        else{
            $this->metaData = $metaData;
        }
        

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getMethod(): ?Method
    {
        return $this->method;
    }

    public function setMethod(?Method $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSecondFeePayed(): ?bool
    {
        return $this->secondFeePayed;
    }

    public function setSecondFeePayed(bool $secondFeePayed): self
    {
        $this->secondFeePayed = $secondFeePayed;

        return $this;
    }

    public function getFirstFeePayed(): ?bool
    {
        return $this->firstFeePayed;
    }

    public function setFirstFeePayed(bool $firstFeePayed): self
    {
        $this->firstFeePayed = $firstFeePayed;

        return $this;
    }

    public function getNextPaymentAt(): ?\DateTimeInterface
    {
        return $this->nextPaymentAt;
    }

    public function setNextPaymentAt(?\DateTimeInterface $nextPaymentAt): self
    {
        $this->nextPaymentAt = $nextPaymentAt;

        return $this;
    }

    public function getCycleTotal(): ?int
    {
        return $this->cycleTotal;
    }

    public function setCycleTotal(?int $cycleTotal): self
    {
        $this->cycleTotal = $cycleTotal;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setDevis($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getDevis() === $this) {
                $message->setDevis(null);
            }
        }

        return $this;
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

    public function getOtherFees(): ?int
    {
        return $this->otherFees;
    }

    public function setOtherFees(?int $otherFees): self
    {
        $this->otherFees = $otherFees;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getOtherFeesPro(): ?int
    {
        return $this->otherFeesPro;
    }

    public function setOtherFeesPro(?int $otherFeesPro): self
    {
        $this->otherFeesPro = $otherFeesPro;

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
}
