<?php

namespace App\Controller\Admin\Evenements;

use DateTimeImmutable;
use App\Entity\OneTimeEvent;
use App\Entity\RecurringRule;
use App\Form\Admin\EventType;
use App\Service\ThemeService;
use App\Entity\RecurringEvent;
use App\DTO\Evenement\EventDTO;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OneTimeEventRepository;
use App\Repository\RecurringEventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Polyfill\Intl\Icu\DateFormat\DayOfWeekTransformer;

final class EvenementController extends AbstractController
{
    #[Route('/admin/evenements', name: 'app_admin_evenements')]
    public function index(
            ThemeService $theme_service,
            OneTimeEventRepository $oneTimeEventRepository,
            RecurringEventRepository $recurringEventRepository
        ): Response
    {
        $theme = $theme_service->getTheme();

        $otEvenements = $oneTimeEventRepository->findAll();
        $now = new \DateTimeImmutable();

        $inCommingOtEvenements =  array_filter($otEvenements, function($event) use ($now){
                return $event->getEndDate() >= $now;
            } 
        );
        $historyOtEvenements =  array_filter($otEvenements, function($event) use ($now){
                return $event->getEndDate() < $now;
            } 
        );
        $recurringEvenement = array_filter($recurringEventRepository->findAll(),function($event){
            return $event->getRecurringRule()->isActive() === true;
        });

        
        return $this->render('admin/evenements/index.html.twig', [
            'theme' => $theme,
            'otEvents' => $inCommingOtEvenements,
            'historyEvents' => $historyOtEvenements,
            'reEvents' => $recurringEvenement,
        ]);
    }
    #[Route('/admin/evenements/new', name: 'app_admin_evenements_new', methods: ['GET', 'POST'])]
    public function new(
        ThemeService $theme_service,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {

        $theme = $theme_service->getTheme();
        // $eventDto = new EventDTO();

        $form = $this->createForm(EventType::class,null,[
            'theme' => $theme
        ]);

        $form->handleRequest($request);
        if ($request->isXmlHttpRequest()) {

            return $this->render('admin/evenements/new.html.twig', [
                'theme' => $theme,
                'form' => $form,
            ]);
        }

        if($form->isSubmitted() && $form->isValid()){

            $newEvent = $form->getData();
                            
            $title = $newEvent["titre"];
            $description = $newEvent["description"];
            
            //?-----------gestion image
            $imagePath = '';
            //?----------- gestion Date
            
            $startAt = (new \DateTimeImmutable())->createFromFormat("d/m/Y", $newEvent['startAt']);
            $startHour = (new \DateTimeImmutable())->createFromFormat("H:i", $newEvent['startHour']);
            $endAt = (new \DateTimeImmutable())->createFromFormat("d/m/Y", $newEvent['endAt']);
            $endHour = (new \DateTimeImmutable())->createFromFormat("H:i", $newEvent['endHour']);

            //?----------- gestion Prix

            $isFree = $newEvent["isFree"];
            $price = $isFree ? 0 : intval($newEvent["prix"]);
            $userPrice = $isFree ? 0 : intval($newEvent["userPrix"]) ;
            

            //?----------- gestion eventType

            if ($newEvent["eventType"] === "ponctuel" ) {
                $ote = new OneTimeEvent();
                $ote
                    ->setTitle($title)
                    ->setDescription($description)
                    ->setImage($imagePath)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setStartDate($startAt)
                    ->setStartHour($startHour)
                    ->setEndDate($endAt)
                    ->setEndHour($endHour)
                    ->setIsFree($isFree)
                    ->setPrice($price )
                    ->setUserPrice( $userPrice )
                ;
                $entityManager->persist($ote);
                
            } elseif($newEvent["eventType"] === "recurring" ) {
                $re = new RecurringEvent();
                $rr = new RecurringRule();
                $rr
                    ->setDaysOfWeek($newEvent["recurringRule"]['dayOfWeek'])
                ;



                $re
                    ->setTitle($title)
                    ->setDescription($description)
                    ->setImage($imagePath)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setStartDate($startAt)
                    ->setStartHour($startHour)
                    ->setEndDate($endAt)
                    ->setEndHour($endHour)
                    ->setIsFree($isFree)
                    ->setPrice($price )
                    ->setUserPrice( $userPrice )
                    ->setRecurringRule($rr)
                    ->setIsActiv(true)
                    ->setStatus("")
                ;
                $entityManager->persist($re);
                
            }
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre évènement a bien été créé'
            );


            
            return $this->redirectToRoute('app_admin_evenements', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/evenements/new.html.twig', [
            'theme' => $theme,
            'form' => $form,
        ]);
    }

    #[Route('/admin/evenements/show/{type}/{id}', name: 'app_admin_evenements_show', methods: ['GET', 'POST'])]
    public function show(
        ThemeService $theme_service,
        Request $request,
        EntityManagerInterface $entityManager,
        OneTimeEventRepository $oneTimeEventRepository,
        RecurringEventRepository $recurringEventRepository,
        string $type,
        int $id,
    ): Response
    {

        if($type === "po"){

            $event = $oneTimeEventRepository->findOneBy(["id"=>$id]);
            $data = [

                'titre'       => $event->getTitle(),
                'description' => $event->getDescription(),
                'image'       => $event->getImage(),
                'isFree'      => $event->isFree(),
                'eventType'   => "ponctuel",
                'prix'        => $event->getPrice(),
                'userPrix'    => $event->getUserPrice(),
                'startAt'     => $event->getStartDate()?->format('d/m/Y'),
                'startHour'   => $event->getStartHour()?->format('H:i'),
                'endAt'       => $event->getEndDate()?->format('d/m/Y'),
                'endHour'     => $event->getEndHour()?->format('H:i'),

            ];
        }elseif($type === "re"){
            $event = $recurringEventRepository->findOneBy(["id"=>$id]);
            $data = [

                'titre'         => $event->getTitle(),
                'description'   => $event->getDescription(),
                'image'         => $event->getImage(),
                'isFree'        => $event->isFree(),
                'eventType'     => "recurring",
                'recurringRule' => [
                    "dayOfWeek" => $event->getRecurringRule()->getDaysOfWeek()
                ],
                'prix'          => $event->getPrice(),
                'userPrix'      => $event->getUserPrice(),
                'startAt'       => $event->getStartDate()?->format('d/m/Y'),
                'startHour'     => $event->getStartHour()?->format('H:i'),
                'endAt'         => $event->getEndDate()?->format('d/m/Y'),
                'endHour'       => $event->getEndHour()?->format('H:i'),

            ];
        }
        $theme = $theme_service->getTheme();
        return $this->render('admin/evenements/show.html.twig', [
            'theme' => $theme,
            'event' => $event,
            'type' => $type
        ]);
    }

    #[Route('/admin/evenements/edit/{type}/{id}', name: 'app_admin_evenements_edit', methods: ['GET', 'POST'])]
    public function edit(
        ThemeService $theme_service,
        Request $request,
        EntityManagerInterface $entityManager,
        OneTimeEventRepository $oneTimeEventRepository,
        RecurringEventRepository $recurringEventRepository,
        string $type,
        int $id,

    ): Response
    {

        if($type === "po"){

            $event = $oneTimeEventRepository->findOneBy(["id"=>$id]);
            $data = [

                'titre'       => $event->getTitle(),
                'description' => $event->getDescription(),
                'image'       => $event->getImage(),
                'isFree'      => $event->isFree(),
                'eventType'   => "ponctuel",
                'prix'        => $event->getPrice(),
                'userPrix'    => $event->getUserPrice(),
                'startAt'     => $event->getStartDate()?->format('d/m/Y'),
                'startHour'   => $event->getStartHour()?->format('H:i'),
                'endAt'       => $event->getEndDate()?->format('d/m/Y'),
                'endHour'     => $event->getEndHour()?->format('H:i'),

            ];
        }elseif($type === "re"){
            $event = $recurringEventRepository->findOneBy(["id"=>$id]);
            $data = [

                'titre'         => $event->getTitle(),
                'description'   => $event->getDescription(),
                'image'         => $event->getImage(),
                'isFree'        => $event->isFree(),
                'eventType'     => "recurring",
                'recurringRule' => [
                    "dayOfWeek" => $event->getRecurringRule()->getDaysOfWeek()
                ],
                'prix'          => $event->getPrice(),
                'userPrix'      => $event->getUserPrice(),
                'startAt'       => $event->getStartDate()?->format('d/m/Y'),
                'startHour'     => $event->getStartHour()?->format('H:i'),
                'endAt'         => $event->getEndDate()?->format('d/m/Y'),
                'endHour'       => $event->getEndHour()?->format('H:i'),

            ];
        }
        $theme = $theme_service->getTheme();
        $eventDto = new EventDTO();

        $form = $this->createForm(EventType::class,$data,[
            'theme' => $theme
        ]);

        $form->handleRequest($request);
        if ($request->isXmlHttpRequest()) {

            return $this->render('admin/evenements/edit.html.twig', [
                'theme' => $theme,
                'form' => $form,
            ]);
        }

        if($form->isSubmitted() && $form->isValid()){

            $newEvent = $form->getData();
                            
            $title = $newEvent["titre"];
            $description = $newEvent["description"];
            
            //?-----------gestion image
            $imagePath = '';
            //?----------- gestion Date
            
            $startAt = (new \DateTimeImmutable())->createFromFormat("d/m/Y", $newEvent['startAt']);
            $startHour = (new \DateTimeImmutable())->createFromFormat("H:i", $newEvent['startHour']);
            $endAt = (new \DateTimeImmutable())->createFromFormat("d/m/Y", $newEvent['endAt']);
            $endHour = (new \DateTimeImmutable())->createFromFormat("H:i", $newEvent['endHour']);

            //?----------- gestion Prix

            $isFree = $newEvent["isFree"];
            $price = $isFree ? 0 : intval($newEvent["prix"]);
            $userPrice = $isFree ? 0 : intval($newEvent["userPrix"]) ;
            

            //?----------- gestion eventType

            if ($newEvent["eventType"] === "ponctuel" ) {
                $ote = $event;
                $ote
                    ->setTitle($title)
                    ->setDescription($description)
                    ->setImage($imagePath)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setStartDate($startAt)
                    ->setStartHour($startHour)
                    ->setEndDate($endAt)
                    ->setEndHour($endHour)
                    ->setIsFree($isFree)
                    ->setPrice($price )
                    ->setUserPrice( $userPrice )
                ;

                
            } elseif($newEvent["eventType"] === "recurring" ) {
                $re = $event;
                $rr = $event->getRecurringRule();
                $rr
                    ->setDaysOfWeek($newEvent["recurringRule"]['dayOfWeek'])
                ;

                $re
                    ->setTitle($title)
                    ->setDescription($description)
                    ->setImage($imagePath)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setStartDate($startAt)
                    ->setStartHour($startHour)
                    ->setEndDate($endAt)
                    ->setEndHour($endHour)
                    ->setIsFree($isFree)
                    ->setPrice($price )
                    ->setUserPrice( $userPrice )
                    ->setRecurringRule($rr)
                    ->setIsActiv(true)
                    ->setStatus("")
                ;

                
            }
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre évènement à bien été modifier'
            );
            
            return $this->redirectToRoute('app_admin_evenements', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/evenements/edit.html.twig', [
            'theme' => $theme,
            'form' => $form,
        ]);
    }
}
