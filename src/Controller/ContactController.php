<?php

namespace App\Controller;

use Symfony\UX\Map\Map;
use Symfony\UX\Map\Point;
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\Polygon;
use Symfony\UX\Map\InfoWindow;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        // 1. Create a new map instance
        $myMap = (new Map())
            // Explicitly set the center and zoom
            ->center(new Point(15.888971, -61.299697))
            ->zoom(12)
            // Or automatically fit the bounds to the markers
            // ->fitBoundsToMarkers()
        ;
        //https://www.google.com/maps/place/15.8737351,-61.2918605
        // 2. You can add markers
        $myMap
            ->addMarker(new Marker(
                position: new Point(15.8737351,-61.2918605), 
                title: 'Association 1OO% Famille',
                infoWindow: new InfoWindow('Association 1OO% Famille')
            ));

        // 3. You can also add Polygons, which represents an area enclosed by a series of `Point` instances
    //     $myMap->addPolygon(
    //         new Polygon(
    //         points: [
    //             new Point(48.8566, 2.3522),
    //             new Point(45.7640, 4.8357),
    //             new Point(43.2965, 5.3698),
    //             new Point(44.8378, -0.5792),
    //         ],
    //         infoWindow: new InfoWindow(
    //             content: 'Paris, Lyon, Marseille, Bordeaux',
    //         ),
    //     )
    // );

        // 4. And inject the map in your template to render it
        return $this->render('contact/index.html.twig', [
            'my_map' => $myMap,
        ]);
    }
}

