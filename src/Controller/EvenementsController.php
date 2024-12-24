<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use DateTimeImmutable;
use Stripe\StripeClient;
use App\Entity\Reservation;
use App\Entity\RecurringEvent;
use App\Service\ApiFetchService;
use App\Form\Evenement\InscriptionType;
use App\Service\RecurringEventsService;
use App\Repository\OneTimeEventRepository;
use App\Repository\RecurringEventRepository;
use App\Repository\ReservationRepository;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Stripe\V2\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class EvenementsController extends AbstractController
{

    #[Route('/evenements', name: 'app_evenements')]
    public function index(OneTimeEventRepository $oter , RecurringEventRepository $rer,RecurringEventsService $recurringEventsService,): Response
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
    public function show(string $id, Request $request, OneTimeEventRepository $oter, RecurringEventRepository $rer, StripeService $stripeService, EntityManagerInterface $entityManager, RecurringEventsService $recurringEventsService): Response
    {
        $today = new DateTimeImmutable();
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $sevenDaysLater = new DateTime();
        $sevenDaysLater->add(new DateInterval('P7D'));


        //?====== Form definition

        $reservation = new Reservation();
        $form = $this->createForm(InscriptionType::class, $reservation);
        $form->handleRequest($request);


        //?=========== get type Event from post parameter

        $typeEvent = $request->query->get('type');


        //?=========== get event 

        $event = match ($typeEvent) {
            'ponctuel'=> $oter->findOneBy(['id'=>$id]),
            'recurring'=> $rer->findOneBy(['id'=>$id]),
            default => 'none',
        };
        if($event === 'none'){
            return $this->redirectToRoute('app_home');
        }

        //?=========== get event  of the month

        $ponctualEvents = $oter->findThisWeekEvent($today);
        $recurringEvents = $rer->findActivEvents();

        $thisWeekEvents = array_merge($ponctualEvents,$recurringEventsService->getOccurrences($recurringEvents,$today,$today->modify("+1 week")));


        //?=========== form handle
        if ($form->isSubmitted() && $form->isValid()){
            
            // dd($form->getData(), $event);
            $price = $user ? $event->getPrice() : $event->getUserPrice() ;
            $produit = [
                "productName" => $event->getTitle(),
                "quantity"=>$form->getData()->getQuantity(),
                "amount"=>$price,
                'type'=> "payment",
                "interval"=> null
            ];
            

                //?=========== create reservation


                    if($typeEvent === 'ponctuel'){
                        $reservation 
                            ->setOtEvent($event)// ontime eventID
                        ;
                        
                    }else if($typeEvent === 'ponctuel'){
                        $reservation 
                            ->setREvent($event) // recuring eventID
                        ;
                    }else {
                            throw new \InvalidArgumentException('Type d\'événement invalide.');
                        }
        
        
                    if($user){
                        $reservation 
                            ->setUser($user)
                            ->setPrix($event->isFree() ? 0 : $event->getUserPrice)
                            ->setNom($user->getNom())
                            ->setPrenom($user->getPrenom())
                            ->setEmail($user->getEmail())
                        ;
                    }else{
                        $reservation 
                            ->setPrix($event->isFree() ? 0 : $event->getPrice)
                            // customer data fill by the form
                        ;
                    }
        
                    $reservation 
                        ->setCreatedAt($today)
                        ->setTypeEvent($typeEvent)
                        ->setFinalPrice($reservation->getPrix() * $reservation->getQuantity())
                    ;


            $entityManager->persist($reservation);
            $entityManager->flush();
            $request->getSession()->set('reservationContext',$reservation->getId());

            return $this->redirect($stripeService->createCheckoutSession($produit));

        }

        return $this->render('evenements/show.html.twig', [
            'event' => $event,
            'type_event'=> $typeEvent,
            'thisWeek' => $thisWeekEvents,
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

