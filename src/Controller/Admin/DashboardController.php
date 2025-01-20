<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\OneTimeEvent;
use App\Entity\RecurringEvent;
use App\Repository\AdherentRepository;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

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
        public AdherentRepository $adherentRepository
    )
    {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        // avoir le compte des 6 derniers mois.
        // a partir du mois d'aujourd'hui, un tableau des 6 dernier mois
        $months = []; // month to the chart
        $dataSet = []; 
        $today = new \DateTimeImmutable('now');
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
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
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

        if($this->isGranted('ROLE_ADMIN')){

            //? Voir le nombre d'abonnées ( chart par mois ) + call to action gestion utilisateurs
            
            
            
            //? 




        }else{

        }
        
        
        return $this->render('admin/dashboard.html.twig' ,[
            'chart' => $chart,

        ]);
        
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
        // yield MenuItem::linkToCrud('Utilisateur', 'fas fa-list', User::class);
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


}
