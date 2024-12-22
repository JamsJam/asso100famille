<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\OneTimeEvent;
use App\Entity\RecurringEvent;
use App\Service\ApiFetchService;
use App\Service\RecurringEventsService;
use App\Repository\OneTimeEventRepository;
use App\Repository\RecurringEventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{



    #[Route('/', name: 'app_home')]
    public function index(OneTimeEventRepository $oter , RecurringEventRepository $rer,RecurringEventsService $recurringEventsService ): Response
    {
        // $today = new DateTime();
        // $sevenDaysLater = (new DateTime())->add(new DateInterval('P7D'));

        // $events = $oter->findAll();
        // // dd($events,$rer->findAll());


        $today = new \DateTimeImmutable();

        $ponctualEvents = $oter->findThisWeekEvent($today);
        $recurringEvents = $rer->findActivEvents();

        $events = array_merge($ponctualEvents,$recurringEventsService->getOccurrences($recurringEvents,$today,$today->modify("+7 day")));

        
        usort($events, function ($a, $b) {
            return $a->getStartDate() <=> $b->getStartDate();
        });


        return $this->render('home/index.html.twig', [
            'events' => $events ,
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
