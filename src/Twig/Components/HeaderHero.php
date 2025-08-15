<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class HeaderHero
{
    public ?string $title;

    public ?string $para1;

    public ?string $para2;

    public ?string $cta ;

    public ?string $ctalink ;

    public ?string $img;

    public ?string $alt;

    public ?bool   $bento = false;

    public ?array  $contact ;
    
    public ?array  $periodeAdhesion ;

    public mixed   $map;

    public mixed   $event;

    public ?string   $typeEvent;

}
