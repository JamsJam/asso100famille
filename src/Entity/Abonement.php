<?php

namespace App\Entity;

use App\Repository\AbonementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbonementRepository::class)]
class Abonement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $expiredAt = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $invoiceId = null;

    #[ORM\Column(length: 255)]
    private ?string $sessionId = null;


    #[ORM\OneToOne(inversedBy: 'abonement', cascade: ['persist', 'remove'])]
    private ?Adherent $adherent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeImmutable
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTimeImmutable $expiredAt): static
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): static
    {
        $this->adherent = $adherent;

        return $this;
    }

    public function getInvoiceId(): ?string
    {
        return $this->invoiceId ;
    }

    public function setInvoiceId(?string $invoiceId): ?static
    {
        $this->invoiceId = $invoiceId;
        
        return $this ;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId ;
    }

    public function setSessionId(?string $sessionId): ?static
    {
        $this->sessionId = $sessionId;

        return $this ;
    }


}
