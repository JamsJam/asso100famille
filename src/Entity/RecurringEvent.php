<?php

namespace App\Entity;

use App\Entity\RecurringRule;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Abstract\EventAbstract;
use App\Repository\RecurringEventRepository;

#[ORM\Entity(repositoryClass: RecurringEventRepository::class)]
class RecurringEvent extends EventAbstract
{
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?RecurringRule $recurringRule = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'rEvent')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getRecurringRule(): ?RecurringRule
    {
        return $this->recurringRule;
    }

    public function setRecurringRule(?RecurringRule $recurringRule): static
    {
        $this->recurringRule = $recurringRule;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setREvent($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getREvent() === $this) {
                $reservation->setREvent(null);
            }
        }

        return $this;
    }
}
