<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idSender = null;

    #[ORM\Column]
    private ?int $idReceiver = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;
    public function __construct()
    {
        $this->creationDate = new \DateTime(); 
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSender(): ?int
    {
        return $this->idSender;
    }

    public function setIdSender(int $idSender): static
    {
        $this->idSender = $idSender;

        return $this;
    }

    public function getIdReceiver(): ?int
    {
        return $this->idReceiver;
    }

    public function setIdReceiver(int $idReceiver): static
    {
        $this->idReceiver = $idReceiver;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }
}
