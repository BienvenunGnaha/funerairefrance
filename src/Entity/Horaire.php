<?php

namespace App\Entity;

use App\Repository\HoraireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HoraireRepository::class)
 */
class Horaire
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
    private $tranche;

    /**
     * @ORM\OneToMany(targetEntity=MoreInfo::class, mappedBy="horaire")
     */
    private $moreInfos;

    public function __construct()
    {
        $this->moreInfos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTranche(): ?string
    {
        return $this->tranche;
    }

    public function setTranche(string $tranche): self
    {
        $this->tranche = $tranche;

        return $this;
    }

    /**
     * @return Collection|MoreInfo[]
     */
    public function getMoreInfos(): Collection
    {
        return $this->moreInfos;
    }

    public function addMoreInfo(MoreInfo $moreInfo): self
    {
        if (!$this->moreInfos->contains($moreInfo)) {
            $this->moreInfos[] = $moreInfo;
            $moreInfo->setHoraire($this);
        }

        return $this;
    }

    public function removeMoreInfo(MoreInfo $moreInfo): self
    {
        if ($this->moreInfos->contains($moreInfo)) {
            $this->moreInfos->removeElement($moreInfo);
            // set the owning side to null (unless already changed)
            if ($moreInfo->getHoraire() === $this) {
                $moreInfo->setHoraire(null);
            }
        }

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->tranche;
        // to show the id of the Category in the select
        // return $this->id;
    }
}
