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



    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $today = new DateTime();
        $sevenDaysLater = (new DateTime())->add(new DateInterval('P7D'));

        $events = "meh";


        return $this->render('home/index.html.twig', [
            // 'events' => $weekEnvent ,
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
