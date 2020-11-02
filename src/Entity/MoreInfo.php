<?php

namespace App\Entity;

use App\Repository\MoreInfoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoreInfoRepository::class)
 */
class MoreInfo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true, nullable=true)
     */
    private $callAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nameCimetiere;

    /**
     * @ORM\ManyToOne(targetEntity=Sepulture::class, inversedBy="moreInfos")
     */
    private $sepulture;

    /**
     * @ORM\ManyToOne(targetEntity=Horaire::class, inversedBy="moreInfos")
     */
    private $horaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emplacement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $concession;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $acteConcession;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $photoConcession;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $more;

    /**
     * @ORM\OneToOne(targetEntity=Devis::class, cascade={"persist", "remove"})
     */
    private $devis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCallAt(): ?\DateTimeInterface
    {
        return $this->callAt;
    }

    public function setCallAt(\DateTimeInterface $callAt): self
    {
        $this->callAt = $callAt;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getNameCimetiere(): ?string
    {
        return $this->nameCimetiere;
    }

    public function setNameCimetiere(string $nameCimetiere): self
    {
        $this->nameCimetiere = $nameCimetiere;

        return $this;
    }

    public function getSepulture(): ?Sepulture
    {
        return $this->sepulture;
    }

    public function setSepulture(?Sepulture $sepulture): self
    {
        $this->sepulture = $sepulture;

        return $this;
    }

    public function getHoraire(): ?Horaire
    {
        return $this->horaire;
    }

    public function setHoraire(?Horaire $horaire): self
    {
        $this->horaire = $horaire;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(string $emplacement): self
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getConcession(): ?string
    {
        return $this->concession;
    }

    public function setConcession(string $concession): self
    {
        $this->concession = $concession;

        return $this;
    }

    public function getActeConcession(): ?string
    {
        return $this->acteConcession;
    }

    public function setActeConcession(string $acteConcession): self
    {
        $this->acteConcession = $acteConcession;

        return $this;
    }

    public function getPhotoConcession(): ?array
    {
        return $this->photoConcession;
    }

    public function setPhotoConcession(?array $photoConcession): self
    {
        $this->photoConcession = $photoConcession;

        return $this;
    }

    public function getMore(): ?string
    {
        return $this->more;
    }

    public function setMore(?string $more): self
    {
        $this->more = $more;

        return $this;
    }

    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    public function setDevis(?Devis $devis): self
    {
        $this->devis = $devis;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }
}
