<?php

namespace App\Entity;

use App\Repository\PaymentMethodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentMethodRepository::class)
 */
class PaymentMethod
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="paymentMethods")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typePayment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payId;

    /**
     * @ORM\OneToMany(targetEntity=Devis::class, mappedBy="paymentMethod")
     */
    private $devis;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getTypePayment(): ?string
    {
        return $this->typePayment;
    }

    public function setTypePayment(string $typePayment): self
    {
        $this->typePayment = $typePayment;

        return $this;
    }

    public function getPayId(): ?string
    {
        return $this->payId;
    }

    public function setPayId(string $payId): self
    {
        $this->payId = $payId;

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
            $devi->setPaymentMethod($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->contains($devi)) {
            $this->devis->removeElement($devi);
            // set the owning side to null (unless already changed)
            if ($devi->getPaymentMethod() === $this) {
                $devi->setPaymentMethod(null);
            }
        }

        return $this;
    }
}
