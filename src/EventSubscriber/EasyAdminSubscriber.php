<?php
namespace App\EventSubscriber;


use DateTimeImmutable;
use App\Entity\OneTimeEvent;
use App\Entity\RecurringEvent;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private  $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => [
                ['convertDateToDateTimeImmutable'],
                ['setCreationDate']
            ],
            // BeforeEntityUpdatedEvent::class => ['updateEntities'],
            // BeforeCrudActionEvent::class      => ['addReservationInfoToEvent'] 
        ];
    }




    public function convertDateToDateTimeImmutable(BeforeEntityPersistedEvent $event) :void
    {
        $start = $event->getEntityInstance()->getStartDate();

        // dd($event->getEntityInstance()->getStartDate());
    }


    public function setCreationDate(BeforeEntityPersistedEvent $event): void
    {
        // dd(new \DateTimeImmutable('now'),$event);
        $entity = $event->getEntityInstance();
        // dd($entity);

        if (!$entity instanceof RecurringEvent && !$entity instanceof OneTimeEvent) {
            return;
        }

        if($entity->getCreatedAt() === null){
            $entity->setCreatedAt(new \DateTimeImmutable('now')) ;
        // dd($event->getEntityInstance()->getStartDate());
        }
    }
}