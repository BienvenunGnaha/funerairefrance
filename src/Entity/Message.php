<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCustomer;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $replyTo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $forMe;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="msgSenders")
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="msgReceivers")
     */
    private $receiver;

    /**
     * @ORM\OneToMany(targetEntity=LastMessage::class, mappedBy="message")
     */
    private $lastMessages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $labelLink;

    /**
     * @ORM\ManyToOne(targetEntity=Devis::class, inversedBy="messages")
     */
    private $devis;


    public function __construct()
    {
        $this->lastMessages = new ArrayCollection();
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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

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

    public function getIsCustomer(): ?bool
    {
        return $this->isCustomer;
    }

    public function setIsCustomer(bool $isCustomer): self
    {
        $this->isCustomer = $isCustomer;

        return $this;
    }


    public function getReplyTo(): ?int
    {
        return $this->replyTo;
    }

    public function setReplyTo(?int $replyTo): self
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    public function getForMe(): ?bool
    {
        return $this->forMe;
    }

    public function setForMe(?bool $forMe): self
    {
        $this->forMe = $forMe;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

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
            $lastMessage->setMessage($this);
        }

        return $this;
    }

    public function removeLastMessage(LastMessage $lastMessage): self
    {
        if ($this->lastMessages->contains($lastMessage)) {
            $this->lastMessages->removeElement($lastMessage);
            // set the owning side to null (unless already changed)
            if ($lastMessage->getMessage() === $this) {
                $lastMessage->setMessage(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->content;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getLabelLink(): ?string
    {
        return $this->labelLink;
    }

    public function setLabelLink(?string $labelLink): self
    {
        $this->labelLink = $labelLink;

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

}
