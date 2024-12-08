<?php

namespace App\Entity\Abstract;


use Doctrine\ORM\Mapping as ORM;


#[ORM\MappedSuperclass]
abstract class EventAbstract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeImmutable $startDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeImmutable $startHour;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeImmutable $endHour = null;

    #[ORM\Column]
    private bool $isFree = false;

    #[ORM\Column(nullable:true)]
    private ?int $price = null;

    #[ORM\Column(nullable:true)]
    private ?int $userPrice = null;

    // Méthodes communes
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getStartHour(): ?\DateTimeImmutable
    {
        return $this->startHour;
    }

    public function setStartHour(?\DateTimeImmutable $startHour): self
    {
        $this->startHour = $startHour;
        return $this;
    }

    public function getEndHour(): ?\DateTimeImmutable
    {
        return $this->endHour;
    }

    public function setEndHour(?\DateTimeImmutable $endHour): self
    {
        $this->endHour = $endHour;
        return $this;
    }

    public function isFree(): bool
    {
        return $this->isFree;
    }

    public function setFree(bool $isFree): static
    {
        $this->isFree = $isFree;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getUserPrice(): ?int
    {
        return $this->userPrice;
    }

    public function setUserPrice(?int $userPrice): self
    {
        $this->userPrice = $userPrice;
        return $this;
    }

}
