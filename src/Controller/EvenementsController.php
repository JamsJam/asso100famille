<?php

namespace App\Controller;

use App\Entity\RecurringEvent;
use DateTime;
use DateInterval;
use App\Entity\Reservation;
use App\Service\ApiFetchService;
use App\Form\Evenement\InscriptionType;
use App\Repository\OneTimeEventRepository;
use App\Repository\RecurringEventRepository;
use App\Service\RecurringEventsService;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class EvenementsController extends AbstractController
{

    #[Route('/evenements', name: 'app_evenements')]
    public function index(OneTimeEventRepository $oter , RecurringEventRepository $rer, RecurringEventsService $recurringEventsService): Response
    {
        $today = new \DateTimeImmutable();

        $ponctualEvents = $oter->findThisMonthEvent($today);
        $recurringEvents = $rer->findActivEvents();

        $events = array_merge($ponctualEvents,$recurringEventsService->getOccurrences($recurringEvents,$today,$today->modify("+30 day")));

        
        usort($events, function ($a, $b) {
            return $a->getStartDate() <=> $b->getStartDate();
        });
        // dd($events);

        return $this->render('evenements/index.html.twig', [
            'events' => $events,
            'jours' => $jours = [
                    'lundi'    => 'monday',
                    'mardi'    => 'tuesday',
                    'mercredi' => 'wednesday',
                    'jeudi'    => 'thursday',
                    'vendredi' => 'friday',
                    'samedi'   => 'saturday',
                    'dimanche' => 'sunday',
                ]
            // 'events' => $new_events,
        ]);
    }


    #[Route('/evenement/{id}', name: 'app_evenements_show')]
    public function show(string $id, Request $request,OneTimeEventRepository $oter, RecurringEventRepository $rer, RecurringEventsService $recurringEventsService): Response
    {
        $today = new DateTime();
        $sevenDaysLater = new DateTime();
        $sevenDaysLater->add(new DateInterval('P7D'));


        //?====== Form definition

        $reservation = new Reservation();
        $form = $this->createForm(InscriptionType::class, $reservation);
        $form->handleRequest($request);


        //?=========== fetch event Data

        // $event = $fetch->getApiData($this->event_url.''.$id.'?populate=*');
        // dd($event);
        // if ($event['type_evenement'] === 'recurent') {
        //     $event['date'] = $this->getProchainEvenement($event['date_type'][0]['jour']);
        // }else{
        //     $event['date'] = $event['date_type'][0]['date'];
        // }

    // //?=========== fetch this week event Data

    // $events = $fetch->getApiData($this->event_url . '?populate=*');
    // $thisWeekEvents = array_filter($events, function ($evenement) use ($today, $sevenDaysLater) {
    //     // Mettre à jour la date si l'événement est récurrent
    //     if ($evenement['type_evenement'] === 'recurent') {
    //         $evenement['date'] = $this->getProchainEvenement($evenement['date_type'][0]['jour']);
    //     } else {
    //         $evenement['date'] = $evenement['date_type'][0]['date'];
    //     }

    //     // Vérifier si la date de l'événement est comprise entre aujourd'hui et dans 7 jours
    //     $eventDate = new DateTime($evenement['date']);
    //     return $eventDate >= $today && $eventDate <= $sevenDaysLater;
    // });


        //?=========== form handle
        if ($form->isSubmitted() && $form->isValid()){
            
            //stripe

        }

        return $this->render('evenements/show.html.twig', [
            // 'event' => $event,
            // 'thisWeek' => $thisWeekEvents,
            'form' => $form
        ]);
    }




    // Méthode privée qui calcule le prochain événement basé sur le jour en français
    private function getProchainEvenement(string $jourFrancais): string
    {
        // Tableau de correspondance entre les jours en français et en anglais
        $jours = [
            'lundi'    => 'Monday',
            'mardi'    => 'Tuesday',
            'mercredi' => 'Wednesday',
            'jeudi'    => 'Thursday',
            'vendredi' => 'Friday',
            'samedi'   => 'Saturday',
            'dimanche' => 'Sunday',
        ];

        // Convertir le jour en anglais
        if (isset($jours[strtolower($jourFrancais)])) {
            $jourAnglais = $jours[strtolower($jourFrancais)];
        } else {
            return "Jour invalide !";
        }

        // Date actuelle
        $aujourdhui = date('Y-m-d');

        // Calculer la date du prochain jour de l'événement
        if (date('l') == $jourAnglais) {
            $prochainEvenement = $aujourdhui; // Si aujourd'hui est le jour de l'événement
        } else {
            $prochainEvenement = date('Y-m-d', strtotime("next $jourAnglais", strtotime($aujourdhui)));
        }

        // Retourner la date du prochain événement
        return $prochainEvenement;
    }

    private function getOccurrences(array $events, DateTimeImmutable $startDate, DateTimeImmutable $endDate): array
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

