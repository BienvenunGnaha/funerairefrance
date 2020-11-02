<?php

namespace App\Entity;

use App\Repository\LastMessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LastMessageRepository::class)
 */
class LastMessage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="lastMessages")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Message::class, inversedBy="lastMessages")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $count = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getLastAt(): ?\DateTimeInterface
    {
        return $this->lastAt;
    }

    public function setLastAt(\DateTimeInterface $lastAt): self
    {
        $this->lastAt = $lastAt;

        return $this;
    }

    public function __toString(){
        return $this->user->getName();
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }
}
