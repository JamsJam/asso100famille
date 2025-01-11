<?php

namespace App\Controller;

use App\Repository\AdherentRepository;
use App\Service\MailerService;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{

    #[Route('/stripe/success', name: 'app_stripe_success')]
    public function sucess(Request $request, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager, MailerService $mailerService, AdherentRepository $adherentRepository): Response
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
                $mailerService->sendTemplatedMail(
                    new Address ("contact@tiers-lieu100p100famille.fr","Association 100% Famille"),
                    $reservation->getEmail(),
                    "Confirmation de reservation",
                    "emails/reservation_confitmation.html.twig",
                    [
                        "username" => $reservation->getNom() . $reservation->getPrenom(),
                        "event_name" => $reservation->getOtEvent() ? $reservation->getOtEvent()->getTitle() : $reservation->getREvent()->getTitle(),
                        "event_desc" => $reservation->getOtEvent() ? $reservation->getOtEvent()->getDescription() : $reservation->getREvent()->getDescription(),
                    ]
                );

                //? ------------- send mail to admin
                $mailerService->sendTemplatedMail(
                    new Address ("contact@tiers-lieu100p100famille.fr","Association 100% Famille"),
                    new Address ("contact@tiers-lieu100p100famille.fr","Association 100% Famille"),
                    "Une nouvelle reservation",
                    "emails/admin_reservation_confitmation.html.twig",
                    [
                        "user_fullname" => $reservation->getPrenom() . " " . $reservation->getNom(),
                        "user_email" =>  $reservation->getEmail(),
                        "event_name" => $reservation->getOtEvent() ? $reservation->getOtEvent()->getTitle() : $reservation->getREvent()->getTitle()
                    ]
                );


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
                    $user = $adherentRepository->findOneBy(["id"=> $session->has("registerContext")]);

                //? ------------- send mail to subscriber
                
                $mailerService->sendTemplatedMail(
                    new Address ("contact@tiers-lieu100p100famille.fr","Association 100% Famille"),
                    $user->getEmail(),
                    "Confirmation d'adhesion",
                    "emails/register_confirmation.html.twig",
                    [
                        "fullname" => $user->getPrenom() . " " . $user->getNom(),
                        "email" => $user->getEmail(),
                        "membership_date" => $user->createdAt(),
                    ]
                );
                
                //? ------------- send mail to admin

                $mailerService->sendTemplatedMail(
                    new Address ("contact@tiers-lieu100p100famille.fr","Association 100% Famille"),
                    new Address ("contact@tiers-lieu100p100famille.fr","Association 100% Famille"),
                    "Une nouvelle Adhesion",
                    "emails/admin_register_confirmation.html.twig",
                    [
                        "new_member_fullname" => $user->getPrenom() . " " . $user->getNom(),
                        "new_member_email" => $user->getEmail(),
                        "membership_date" => $user->createdAt(),
                    ]
                );
                

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
