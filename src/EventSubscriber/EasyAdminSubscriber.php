<?php
namespace App\EventSubscriber;

use App\Entity\Promos;
use DateTimeImmutable;
use App\Entity\Activites;
use App\Entity\Excursions;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
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
            BeforeEntityPersistedEvent::class => ['convertDateToDateTimeImmutable'],
            // BeforeEntityUpdatedEvent::class => ['updateEntities'],
        ];
    }




    public function convertDateToDateTimeImmutable(BeforeEntityPersistedEvent $event){
        $start = $event->getEntityInstance()->getStartDate();

        // dd($event->getEntityInstance()->getStartDate());
    }
}