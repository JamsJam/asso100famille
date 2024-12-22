<?php

namespace App\Service;

class RecurringEventsService{

    public function getOccurrences(array $events, \DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array
    {
        $occurrences = [];


        foreach ($events as $event) {
            // Vérifie si l'événement est actif
            if (!$event->getRecurringRule()->isActive()) {
                continue;
            }

            // Récupère les jours de la semaine et la date de début
            $daysOfWeek = $event->getRecurringRule()->getDaysOfWeek();
            $eventStartDate = $event->getStartDate();

                // Trouve le premier jour correspondant à la règle après le début de la plage
                $currentDate = (clone $startDate)->modify("next ". $daysOfWeek);
                // while ($currentDate < $day) {
                //     $currentDate->modify('+1 day');
                // }

                // Vérifie si la date calculée est dans la plage et après la date de début de l'événement
                while ($currentDate < $endDate) {
                    // if ($currentDate >= $eventStartDate) {
                    //     $newEvent = $event;
                    //     // $newEvent = $currentDate;
                    //      // Met à jour la startDate avec l'occurrence
                    //     $occurrences[] = $newEvent;
                    // }
                    $newEvent = clone $event;
                    // $eventStartDate
                    $newEvent->setStartDate($currentDate);
                    // $newEvent->getRecurringRule()->setDaysOfWeek(($jours)[$newEvent->getRecurringRule()->getDaysOfWeek()]);
                    $occurrences[] = $newEvent;
                    // Ajoute une semaine pour trouver la prochaine occurrence du même jour
                    $currentDate = $currentDate->modify('+1 week');
                }
            


            
        }

        return $occurrences;
    }
}