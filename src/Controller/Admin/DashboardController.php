<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\OneTimeEvent;
use App\Entity\RecurringEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        
        //todo 1) affichier Les evenements de cette semaine
        //todo 2) affichier ne nombre d'utilisateur et un call to action pour la gestion
        //todo 3) afficher les donnes de l'utilisateur connecter et possibilité de le modifier si non admin
        //todo 3) afficher les donnes de l'utilisateur connecter et possibilité de le modifier si non admin
        return $this->render('admin/dashboard.html.twig' ,[
            
        ]);
    }

    public function configureDashboard(): Dashboard
    {

        return Dashboard::new()
            ->setTitle('Dashboard')

            ->setDefaultColorScheme('light')
            ;
            
        
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
}
