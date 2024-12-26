<?php

namespace App\Controller;


use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{

    #[Route('/stripe/success', name: 'app_stripe_success')]
    public function sucess(Request $request, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        
        //? ================== success after reservation
            if($session->has('reservationContext')){
                
                /** @var int $context */
                $context = $session->get('reservationContext');
                

                /** @var \App\Entity\Reservation $reservation */
                $reservation = $reservationRepository->findOneBy(['id' => $context]);
                $reservation
                    ->setActiv(true)
                    ->setPaid(true)
                ;
                $entityManager->flush();

                //? ------------- send mail to subscriber


                //? ------------- clean session

                $session->remove('reservationContext');

                //? ------------- create Flash
                    $this->addFlash(
                        'stripe_success',
                        'Votre reservation a bien été pris en compte'
                    );
                //? ------------- Redirect
                    return $this->redirectToRoute('app_home', [
                        
                    ]);

            }
        //? ----------------------



        //? ================== success after register
            if ($session->has("registerContext")) {
                    # code...

                //? ------------- send mail to subscriber



                //? ------------- clean session

                $session->remove('reservationContext');


                //? ------------- create Flash
                    $this->addFlash(
                        'stripe_success',
                        'Votre inscription à bien été pris en compte'
                    );
                //? ------------- Redirect
                    return $this->redirectToRoute('app_home', [
                        
                    ]);

            }





        //? ================== success after .........
        
        
        
        //? ================== -> unatorized access
            return $this->redirect("app_home", 401);
    }


    #[Route('/stripe/cancel', name: 'app_stripe_cancel')]
    public function cancel(Request $request): Response
    {
        $session = $request->getSession();


        //? ================== fail after reservation
            if($session->has('reservationContext')){

                $request->getSession()->remove('reservationContext');
                $this->addFlash(
                    'stripe_fail',
                    'Votre reservation n\' pas pu aboutir'
                );

                return $this->redirectToRoute('app_home', [
                
                ]);
            }



        //? ================== fail after register
            if ($session->has('reservationContext')) {
                
                

            }

        //? ================== -> unatorized access
        return $this->redirect("app_home", 401);
    }


    #[Route('/stripe/return', name: 'app_stripe_return')]
    public function return(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }
}
