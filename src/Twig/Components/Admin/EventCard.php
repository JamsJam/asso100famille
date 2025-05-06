<?php

namespace App\Twig\Components\Admin;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class EventCard
{
    public ?string $idNum = null;
    // public ?int $type = null;
    public ?string $title = null ;
    public ?string $description = null ;
    public \DateTimeImmutable $date ;
    public \DateTimeImmutable $startHour ;
    public \DateTimeImmutable $endHour ;
    public ?string $price = null ;
    public ?string $priceUser = null ;
    public bool $isFree ;
    public ?string $image = null ;
    public mixed $recurringRule = null ;
    // public ?string $status = null;
    

    // public function mount(){
    //     $this->getStatus();
    // }




    public function getStatus(){
        $startEvent = $this->date->setTime(
            (int) $this->startHour->format('G'),
            (int) $this->startHour->format('i')
        );
        $endEvent = $this->date->setTime(
            (int) $this->endHour->format('G'),
            (int) $this->endHour->format('i')
        );

        $today= new \DateTimeImmutable('now', new \DateTimeZone('America/Guadeloupe'));
        

        $status = match (true) {
            $today < $startEvent => 'incoming' ,
            ($today >= $startEvent && $today < $endEvent)  => 'inProgress',
            ( $today > $endEvent)  => 'past',
        };
        
        return $status;
    }



    

}
