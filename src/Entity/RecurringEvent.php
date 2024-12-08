<?php

namespace App\Entity;

use App\Entity\RecurringRule;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Abstract\EventAbstract;
use App\Repository\RecurringEventRepository;

#[ORM\Entity(repositoryClass: RecurringEventRepository::class)]
class RecurringEvent extends EventAbstract
{
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?RecurringRule $recurringRule = null;

    public function getRecurringRule(): ?RecurringRule
    {
        return $this->recurringRule;
    }

    public function setRecurringRule(?RecurringRule $recurringRule): static
    {
        $this->recurringRule = $recurringRule;

        return $this;
    }
}
