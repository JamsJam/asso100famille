<?php

namespace App\Controller;

use Stripe\Stripe;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{




    #[Route('/stripe/success', name: 'app_stripe_success')]
    public function sucess(Request $request): Response
    {
        $this->addFlash(
            'stripe_success',
            'Your changes were saved!'
        );
        return $this->redirectToRoute('app_home', [
            
        ]);
    }


    #[Route('/stripe/cancel', name: 'app_stripe_cancel')]
    public function cancel(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }


    #[Route('/stripe/return', name: 'app_stripe_return')]
    public function return(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }
}
