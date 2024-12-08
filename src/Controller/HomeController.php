<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Service\ApiFetchService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $event_url;

    public function __construct(string $strapi_event_url)
    {
        $this->event_url = $strapi_event_url;
    }


    #[Route('/', name: 'app_home')]
    public function index(ApiFetchService $fetch): Response
    {
        $today = new DateTime();
        $sevenDaysLater = new DateTime();
        $sevenDaysLater->add(new DateInterval('P7D'));
            //?=========== fetch this week event Data

        $events = $fetch->getApiData($this->event_url . '?populate=*');
        $thisWeekEvents = array_filter($events, function ($evenement) use ($today, $sevenDaysLater) {
        // Mettre à jour la date si l'événement est récurrent
        if ($evenement['type_evenement'] === 'recurent') {
            $evenement['date'] = $this->getProchainEvenement($evenement['date_type'][0]['jour']);
        } else {
            $evenement['date'] = $evenement['date_type'][0]['date'];
        }

        // Vérifier si la date de l'événement est comprise entre aujourd'hui et dans 7 jours
        $eventDate = new DateTime($evenement['date']);
        return $eventDate >= $today && $eventDate <= $sevenDaysLater;
    });

    // dd($thisWeekEvents);

    // foreach ($thisWeekEvents as $event) {
    //     if ($event['type_evenement'] === 'recurent') {
    //         $event['date'] = $this->getProchainEvenement($event['date_type'][0]['jour']);
    //     } else {
    //         $event['date'] = $event['date_type'][0]['date'];
    //     }
    // }
    $weekEnvent = array_map(function($event){
        if ($event['type_evenement'] === 'recurent') {
            $event['date'] = $this->getProchainEvenement($event['date_type'][0]['jour']);
        } else {
            $event['date'] = $event['date_type'][0]['date'];
        }
        return $event;
    },$thisWeekEvents);

    // dd($thisWeekEvents, $weekEnvent);

        return $this->render('home/index.html.twig', [
            'events' => $weekEnvent ,
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
}