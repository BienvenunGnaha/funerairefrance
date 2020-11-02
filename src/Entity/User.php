<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Country;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActivated = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeCustomer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paypalCustomer;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="userId")
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="userId")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=PaymentMethod::class, mappedBy="user_id")
     */
    private $paymentMethods;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity=TypeAccount::class, inversedBy="users")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Cart::class, mappedBy="user")
     */
    private $carts;

    /**
     * @ORM\OneToOne(targetEntity=Wishlist::class, cascade={"persist", "remove"})
     */
    private $wishlist;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender")
     */
    private $msgSenders;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="receiver")
     */
    private $msgReceivers;

    /**
     * @ORM\OneToMany(targetEntity=LastMessage::class, mappedBy="user")
     */
    private $lastMessages;

    /**
     * @ORM\OneToMany(targetEntity=Devis::class, mappedBy="user")
     */
    private $devis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetPWToken;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified;

    /**
     * @ORM\OneToMany(targetEntity=Rating::class, mappedBy="user")
     */
    private $ratings;

    /**
     * @ORM\Column(type="boolean")
     */
    private $news;


    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->paymentMethods = new ArrayCollection();
        $this->carts = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->messagesAdmin = new ArrayCollection();
        $this->msgSenders = new ArrayCollection();
        $this->msgReceivers = new ArrayCollection();
        $this->lastMessages = new ArrayCollection();
        $this->devis = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(string $address2): self
    {
        $this->address2 = $address2;

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->Country;
    }

    public function setCountry(?string $Country): self
    {
        $this->Country = $Country;

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

    public function getIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    public function getStripeCustomer(): ?string
    {
        return $this->stripeCustomer;
    }

    public function setStripeCustomer(?string $stripeCustomer): self
    {
        $this->stripeCustomer = $stripeCustomer;

        return $this;
    }

    public function getPaypalCustomer(): ?string
    {
        return $this->paypalCustomer;
    }

    public function setPaypalCustomer(?string $paypalCustomer): self
    {
        $this->paypalCustomer = $paypalCustomer;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUserId($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getUserId() === $this) {
                $address->setUserId(null);
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
            $order->setUserId($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getUserId() === $this) {
                $order->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PaymentMethod[]
     */
    public function getPaymentMethods(): Collection
    {
        return $this->paymentMethods;
    }

    public function addPaymentMethod(PaymentMethod $paymentMethod): self
    {
        if (!$this->paymentMethods->contains($paymentMethod)) {
            $this->paymentMethods[] = $paymentMethod;
            $paymentMethod->setUserId($this);
        }

        return $this;
    }

    public function removePaymentMethod(PaymentMethod $paymentMethod): self
    {
        if ($this->paymentMethods->contains($paymentMethod)) {
            $this->paymentMethods->removeElement($paymentMethod);
            // set the owning side to null (unless already changed)
            if ($paymentMethod->getUserId() === $this) {
                $paymentMethod->setUserId(null);
            }
        }

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getType(): ?TypeAccount
    {
        return $this->type;
    }

    public function setType(?TypeAccount $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function __toString()
    {
        return $this->name.' : '.$this->email;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->setUser($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->contains($cart)) {
            $this->carts->removeElement($cart);
            // set the owning side to null (unless already changed)
            if ($cart->getUser() === $this) {
                $cart->setUser(null);
            }
        }

        return $this;
    }

    public function getWishlist(): ?Wishlist
    {
        return $this->wishlist;
    }

    public function setWishlist(?Wishlist $wishlist): self
    {
        $this->wishlist = $wishlist;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): self
    {
        if(is_string($photo)){
            $this->photo = $photo;
        }
        elseif($photo instanceof UploadedFile){
            $this->photo = $photo;
        }
        else{
            $this->photo = null;
        }
        

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMsgSenders(): Collection
    {
        return $this->msgSenders;
    }

    public function addMsgSender(Message $msgSender): self
    {
        if (!$this->msgSenders->contains($msgSender)) {
            $this->msgSenders[] = $msgSender;
            $msgSender->setSender($this);
        }

        return $this;
    }

    public function removeMsgSender(Message $msgSender): self
    {
        if ($this->msgSenders->contains($msgSender)) {
            $this->msgSenders->removeElement($msgSender);
            // set the owning side to null (unless already changed)
            if ($msgSender->getSender() === $this) {
                $msgSender->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMsgReceivers(): Collection
    {
        return $this->msgReceivers;
    }

    public function addMsgReceiver(Message $msgReceiver): self
    {
        if (!$this->msgReceivers->contains($msgReceiver)) {
            $this->msgReceivers[] = $msgReceiver;
            $msgReceiver->setReceiver($this);
        }

        return $this;
    }

    public function removeMsgReceiver(Message $msgReceiver): self
    {
        if ($this->msgReceivers->contains($msgReceiver)) {
            $this->msgReceivers->removeElement($msgReceiver);
            // set the owning side to null (unless already changed)
            if ($msgReceiver->getReceiver() === $this) {
                $msgReceiver->setReceiver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LastMessage[]
     */
    public function getLastMessages(): Collection
    {
        return $this->lastMessages;
    }

    public function addLastMessage(LastMessage $lastMessage): self
    {
        if (!$this->lastMessages->contains($lastMessage)) {
            $this->lastMessages[] = $lastMessage;
            $lastMessage->setUser($this);
        }

        return $this;
    }

    public function removeLastMessage(LastMessage $lastMessage): self
    {
        if ($this->lastMessages->contains($lastMessage)) {
            $this->lastMessages->removeElement($lastMessage);
            // set the owning side to null (unless already changed)
            if ($lastMessage->getUser() === $this) {
                $lastMessage->setUser(null);
            }
        }

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
            $devi->setUser($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->contains($devi)) {
            $this->devis->removeElement($devi);
            // set the owning side to null (unless already changed)
            if ($devi->getUser() === $this) {
                $devi->setUser(null);
            }
        }

        return $this;
    }

    public function getResetPWToken(): ?string
    {
        return $this->resetPWToken;
    }

    public function setResetPWToken(?string $resetPWToken): self
    {
        $this->resetPWToken = $resetPWToken;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

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
            $rating->setUser($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);
            // set the owning side to null (unless already changed)
            if ($rating->getUser() === $this) {
                $rating->setUser(null);
            }
        }

        return $this;
    }

    public function getNews(): ?bool
    {
        return $this->news;
    }

    public function setNews(bool $news): self
    {
        $this->news = $news;

        return $this;
    }

}
