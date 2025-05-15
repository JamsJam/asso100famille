<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\OneTimeEvent;
use App\Entity\RecurringEvent;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\AdherentRepository;
use App\Service\RecurringEventsService;
use App\Repository\OneTimeEventRepository;
use App\Repository\RecurringEventRepository;
use App\Service\StripeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Stripe\Stripe;
use Symfony\Component\Validator\Constraints\Length;

class DashboardController extends AbstractDashboardController
{
    public const MONTHS = [
        1  => 'Janvier',
        2  => 'Février',
        3  => 'Mars',
        4  => 'Avril',
        5  => 'Mai',
        6  => 'Juin',
        7  => 'Juillet',
        8  => 'Août',
        9  => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre',
    ];

    public function __construct(
        public ChartBuilderInterface $chartBuilder,
        public AdherentRepository $adherentRepository,
        public RecurringEventRepository $recurringEventRepository,
        public OneTimeEventRepository $oneTimeEventRepository,
        public RecurringEventsService $recurringEventsService,
        public StripeService $stripeService
    )
    {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
                // dd($events);
        // if($this->isGranted('ROLE_ADMIN')){
            $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
            $today = new \DateTimeImmutable('now',new \DateTimeZone('America/Guadeloupe'));

            //?  ========================== Chart
                // avoir le compte des 6 derniers mois.
                // a partir du mois d'aujourd'hui, un tableau des 6 dernier mois
                $months = []; // month to the chart
                $dataSet = []; 
                for ($i=-6; $i <= 0; $i++) { 
                    $months[] = clone($today)
                        ->modify($i . 'month');
                }
                $dataSet = array_map(
                    function($month){

                        
                        return $this->adherentRepository->getMonthlyCount($month->modify('last day of this month'));
                    },$months);
                    // dd($months,$dataSet);
                // dd($months);
                $chart->setData([

                    'labels' => array_map(
                        function($date) {
                            $month = $date->format('m');
                            $date->format('Y');
                            if ($month == 1 || $month == 12) {
                                return $this->getMonthName(intval($month)) . ' ' . $date->format('Y');
                            } else {
                                return $this->getMonthName(intval($month));
                            }
                            
                        },$months),
                    'datasets' => [
                        [
                            'label' => 'Nombre total d\'abonnés ',
                            'backgroundColor' => 'hsl(338, 27.70%, 36.90%)',
                            'borderColor' => 'hsl(338, 27.70%, 46.90%)',
                            'data' => $dataSet
                        ],
                    ],
                ]);

                $chart->setOptions([
                    'scales' => [
                        'y' => [
                            'suggestedMin' => 0,
                            'suggestedMax' => 100,
                        ],
                    ],
                ]);

            //! ---------
            
            //?  ========================== Events


                $events = array_merge(
                    $this->oneTimeEventRepository->findNextEvents($today),
                    $this->recurringEventsService->getOccurrences(
                        $this->recurringEventRepository->findNextEvents($today),
                        $today,$today->modify('+1 week')
                    )
                );


                usort($events,[self::class, 'sortTable']);
                // $events[] = $this->recurringEventsService->getOccurrences($this->recurringEventRepository->findNextEvents($today),$today,$today->modify('+1 week')) ; 
                // dd($today,$today->modify('+1 day'));
            //! ---------

            $filterEvents = [];
            for ($i=0; $i < 7; $i++) { 
                $date = $today->modify("+" .$i. "days");
                $thisDayEvent = $this->filterOnDate($events,$date);
                if(count($thisDayEvent) === 0 ){
                    continue;
                }
                $formatter = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);
                $formatter->setPattern('EEEE dd MMMM');
                // $formatter->format($date);
                $filterEvents[]= [$formatter->format($date) => $thisDayEvent];
                
            };
            // dd($filterEvents);

            $this->stripeService->getSessionCheckout('cs_test_b15p1Xjo2v7UisI8DEy8PXze5DY45XsnxdvSNr5WuocWs0DUskHOU1OOyi');


            $userInfo = $this->getUser();
            // dd($userInfo);


            return $this->render('admin/dashboard.html.twig' ,[
                'chart' => $chart,
                'events'=> $filterEvents,
                'user' => $userInfo
            ]);
            
        // }else{
            
            // get data client
            // return $this->render('admin/dashboard.html.twig' ,[
            //     'chart' => $chart,
            //     'events'=> $events,
    
    
            // ]);

        // }
        
    }
    
    //todo 1) affichier Les evenements de cette semaine
    //todo 2) affichier ne nombre d'utilisateur et un call to action pour la gestion
    //todo 3) afficher les donnes de l'utilisateur connecter et possibilité de le modifier si non admin
    public function configureDashboard(): Dashboard
    {
        if($this->isGranted('ROLE_ADMIN')){

            
            return Dashboard::new()
                ->setTitle('Espace Administrateur')
                ->disableDarkMode()
                ->setDefaultColorScheme('light')
            ;
        }else{
            
            return Dashboard::new()
                ->setTitle('Espace Utilisateur')
                ->disableDarkMode()
                ->setDefaultColorScheme('light')
            ;
        }
            
        
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-back', 'app_home');
        yield MenuItem::section('Evenement');
        yield MenuItem::linkToCrud('Evenements ponctuels', 'fas fa-list', OneTimeEvent::class);
        yield MenuItem::linkToCrud('Evenements recurrents', 'fas fa-list', RecurringEvent::class);
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-list', User::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }


    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            
        ;
    }

    public function configureAssets(): Assets
    {
        return Assets::new()

            // you can also import multiple entries
            // it's equivalent to calling {{ importmap(['app', 'admin']) }}
            ->addAssetMapperEntry('admin')
            // ->addCssFile('build/admin.css')
        ;
    }

    public static function getMonthName(int $monthNumber): ?string
    {
        return self::MONTHS[$monthNumber] ?? null;
    }

    public static function getMonthNumber(string $monthName): ?int
    {
        return array_flip(self::MONTHS)[$monthName] ?? null;
    }

    public static function sortTable($event1, $event2) {
        $date1 = clone $event1->getStartDate(); // Supposons qu'une méthode getStartDate() existe
        $date2 = clone $event2->getStartDate();
    
        $time1 = $date1->setTime(
            $event1->getStartHour()->format("H"),
            $event1->getStartHour()->format("i"),
            $event1->getStartHour()->format("s")
        );
    
        $time2 = $date2->setTime(
            $event2->getStartHour()->format("H"),
            $event2->getStartHour()->format("i"),
            $event2->getStartHour()->format("s")
        );
    
        if ($time1 == $time2) {
            return 0;
        }
        return ($time1 < $time2) ? -1 : 1;
    }

    public static function filterOnDate( array $events, \DateTimeImmutable $date) {
        return $filterArray = array_filter($events, function($event) use ($date){
            
            return $event->getStartDate()->format('d/m/Y') == $date->format('d/m/Y');
        });

        
    }


}
