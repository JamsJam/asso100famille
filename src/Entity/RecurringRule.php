<?php

namespace App\Entity;

use App\Repository\RecurringRuleRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

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
    private array $daysOfWeek = [];

    #[ORM\Column]
    private bool $isActive = true;

    // #[ORM\Column(nullable: true)]
    // private ?\DateTimeImmutable $until = null;

    public function getId(): ?int
    {
        return $this->id;
    }

 

    public function getDaysOfWeek(): array
    {
        return $this->daysOfWeek;
    }

    public function setDaysOfWeek(array $daysOfWeek): static
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

    public function isActive(bool $isActive): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }
}
