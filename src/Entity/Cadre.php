<?php

namespace App\Entity;

use App\Repository\CadreRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CadreRepository::class)
 */
class Cadre
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $pricepro;

    /**
     * @ORM\Column(type="integer")
     */
    private $height;

    /**
     * @ORM\Column(type="integer")
     */
    private $width;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCircle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ovale;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isGravure;

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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
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

    public function getPricepro(): ?int
    {
        return $this->pricepro;
    }

    public function setPricepro(int $pricepro): self
    {
        $this->pricepro = $pricepro;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getIsCircle(): ?bool
    {
        return $this->isCircle;
    }

    public function setIsCircle(bool $isCircle): self
    {
        $this->isCircle = $isCircle;

        return $this;
    }

    public function getOvale(): ?bool
    {
        return $this->ovale;
    }

    public function setOvale(bool $ovale): self
    {
        $this->ovale = $ovale;

        return $this;
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
