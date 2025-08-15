<?php

namespace App\Controller;

use App\Repository\OneTimeEventRepository;
use App\Repository\RecurringEventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SitemapController extends AbstractController
{
    #[Route('/sitemap', name: 'app_sitemap')]
    public function index(
        OneTimeEventRepository $otr,
        RecurringEventRepository $rer,
    ): Response
    {
        $paths = [
            'app_home',
            'app_evenements',
            'app_contact',
            'app_about',
            'app_login',
            'app_forgot_password_request',
            'app_register',
        ];
        // find published blog posts from db
        $otEvents = $otr->findAll();
        $rEvents = $rer->findAll();
        $today = new \DateTimeImmutable();
        $events = [];


        

        $urls = [];
                foreach ($paths as $path) {
            $urls[] = [
                'loc' => $this->generateUrl(
                    $path,
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'lastmod' => "2025-08-15",
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ];
        }


        foreach ($otEvents as $event) {
            if($event->getStartDate() >= $today ){
                array_push($events,$event);
            }
        } 
        foreach ($rEvents as $event) {
            if($event->getRecurringRule()->isActive()){
                array_push($events,$event);
            }
        } 

        
        foreach ($events as $event) {
            $isRecurring = method_exists($event, 'getRecurring') && $event->getRecurring() !== null;

            
            $urls[] = [
                'loc' => $this->generateUrl(
                    'app_evenements_show',
                    [
                        'id' => $event->getId(),
                        'type' => $isRecurring ? "recurring" : "ponctuel"
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'lastmod' => $event->getCreatedAt() ? $event->getCreatedAt()->format('Y-m-d') : $today->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' =>'0.5',
            ];
        }

        $response = new Response(
            $this->renderView('sitemap/index.html.twig', ['urls' => $urls]),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
