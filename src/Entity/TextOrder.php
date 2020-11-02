<?php

namespace App\Entity;

use App\Repository\TextOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TextOrderRepository::class)
 */
class TextOrder
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
    private $content;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="text1")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="text2")
     */
    private $t2Orders;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->t2Orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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
            $order->setText1($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getText1() === $this) {
                $order->setText1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getT2Orders(): Collection
    {
        return $this->t2Orders;
    }

    public function addT2Order(Order $t2Order): self
    {
        if (!$this->t2Orders->contains($t2Order)) {
            $this->t2Orders[] = $t2Order;
            $t2Order->setText2($this);
        }

        return $this;
    }

    public function removeT2Order(Order $t2Order): self
    {
        if ($this->t2Orders->contains($t2Order)) {
            $this->t2Orders->removeElement($t2Order);
            // set the owning side to null (unless already changed)
            if ($t2Order->getText2() === $this) {
                $t2Order->setText2(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->content;
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
}
