<?php

namespace App\Entity;

use App\Repository\CustomProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomProductRepository::class)
 */
class CustomProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $textes = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $images = [];

    /**
     * @ORM\OneToOne(targetEntity=Order::class, cascade={"persist", "remove"})
     */
    private $order_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTextes(): ?array
    {
        return $this->textes;
    }

    public function setTextes(array $textes): self
    {
        $this->textes = $textes;

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(array $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getOrderId(): ?Order
    {
        return $this->order_id;
    }

    public function setOrderId(?Order $order_id): self
    {
        $this->order_id = $order_id;

        return $this;
    }
}
