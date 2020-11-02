<?php

namespace App\Entity;

use App\Repository\SepultureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SepultureRepository::class)
 */
class Sepulture
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
     * @ORM\OneToMany(targetEntity=MoreInfo::class, mappedBy="sepulture")
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $moreInfo->setSepulture($this);
        }

        return $this;
    }

    public function removeMoreInfo(MoreInfo $moreInfo): self
    {
        if ($this->moreInfos->contains($moreInfo)) {
            $this->moreInfos->removeElement($moreInfo);
            // set the owning side to null (unless already changed)
            if ($moreInfo->getSepulture() === $this) {
                $moreInfo->setSepulture(null);
            }
        }

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->name;
        // to show the id of the Category in the select
        // return $this->id;
    }
}
