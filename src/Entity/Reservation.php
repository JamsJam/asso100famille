<?php

namespace App\Entity;

use App\Entity\Adherent;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(length: 5)]
    private ?string $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;


    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?OneTimeEvent $otEvent = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?RecurringEvent $rEvent = null;

    #[ORM\Column]
    private ?int $finalPrice = null;

    #[ORM\Column]
    private ?bool $isActiv = null;

    #[ORM\Column]
    private ?bool $isPaid = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Adherent $adherent = null;

    #[ORM\Column(length: 10)]
    private ?string $typeEvent = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

   



    public function getId(): ?int
    {
        return $this->id;
    }


    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
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

    public function getOtEvent(): ?OneTimeEvent
    {
        return $this->otEvent;
    }

    public function setOtEvent(?OneTimeEvent $otEvent): static
    {
        $this->otEvent = $otEvent;

        return $this;
    }

    public function getREvent(): ?RecurringEvent
    {
        return $this->rEvent;
    }

    public function setREvent(?RecurringEvent $rEvent): static
    {
        $this->rEvent = $rEvent;

        return $this;
    }

    public function getFinalPrice(): ?int
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(int $finalPrice): static
    {
        $this->finalPrice = $finalPrice;

        return $this;
    }

    public function isActiv(): ?bool
    {
        return $this->isActiv;
    }

    public function setActiv(bool $isActiv): static
    {
        $this->isActiv = $isActiv;

        return $this;
    }

    public function isPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setPaid(bool $isPaid): static
    {
        $this->isPaid = $isPaid;

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

    public function getTypeEvent(): ?string
    {
        return $this->typeEvent;
    }

    public function setTypeEvent(string $typeEvent): static
    {
        $this->typeEvent = $typeEvent;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }


}
