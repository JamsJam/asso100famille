<?php

namespace App\Entity;

use App\Repository\RecurringRuleRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: RecurringRuleRepository::class)]
class RecurringRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    // #[ORM\Column(length: 50)]
    // private ?string $frequency = null;

    // #[ORM\Column]
    // private ?int $finterval = null;

    #[ORM\Column]
    private ?string $daysOfWeek = null;

    #[ORM\Column]
    private bool $isActive = true;

    // #[ORM\Column(nullable: true)]
    // private ?\DateTimeImmutable $until = null;

    public function getId(): ?int
    {
        return $this->id;
    }

 

    public function getDaysOfWeek(): ?string
    {
        return $this->daysOfWeek;
    }

    public function setDaysOfWeek(?string $daysOfWeek): static
    {
        $this->daysOfWeek = $daysOfWeek;

        return $this;
    }

    // public function getUntil(): ?\DateTimeImmutable
    // {
    //     return $this->until;
    // }

    // public function setUntil(?\DateTimeImmutable $until): static
    // {
    //     $this->until = $until;

    //     return $this;
    // }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    // public function __toString(): string
    // {
    //     // Adaptez ceci en fonction des propriétés pertinentes de RecurringRule
    //     return $this->daysOfWeek ? implode(', ', $this->daysOfWeek) : 'Règle de récurrence';

    // }


}
