<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ServiceCard
{
    public ?string $titre = null;
    public array $items = [];
    public ?string $img;
    public ?string $alt;
}
