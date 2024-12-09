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



    #[ORM\Column(length: 50)]
    private ?string $frequency = null;

    #[ORM\Column]
    private ?int $finterval = null;

    #[ORM\Column]
    private array $daysOfWeek = [];

    #[ORM\Column(nullable: true)]
    private ?\DateTimeInterface $until = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(string $frequency): static
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getFinterval(): ?int
    {
        return $this->finterval;
    }

    public function setFinterval(int $finterval): static
    {
        $this->finterval = $finterval;

        return $this;
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

    public function getUntil(): ?\DateInterval
    {
        return $this->until;
    }

    public function setUntil(?\DateInterval $until): static
    {
        $this->until = $until;

        return $this;
    }
}
