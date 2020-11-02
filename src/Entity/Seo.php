<?php

namespace App\Entity;

use App\Repository\SeoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeoRepository::class)
 */
class Seo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $googleAnalytic;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metaKeys;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bingWebmaster;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $otherSearchEngine;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGoogleAnalytic(): ?string
    {
        return $this->googleAnalytic;
    }

    public function setGoogleAnalytic(?string $googleAnalytic): self
    {
        $this->googleAnalytic = $googleAnalytic;

        return $this;
    }

    public function getMetaKeys(): ?string
    {
        return $this->metaKeys;
    }

    public function setMetaKeys(?string $metaKeys): self
    {
        $this->metaKeys = $metaKeys;

        return $this;
    }

    public function getBingWebmaster(): ?string
    {
        return $this->bingWebmaster;
    }

    public function setBingWebmaster(?string $bingWebmaster): self
    {
        $this->bingWebmaster = $bingWebmaster;

        return $this;
    }

    public function getOtherSearchEngine(): ?string
    {
        return $this->otherSearchEngine;
    }

    public function setOtherSearchEngine(?string $otherSearchEngine): self
    {
        $this->otherSearchEngine = $otherSearchEngine;

        return $this;
    }
}
