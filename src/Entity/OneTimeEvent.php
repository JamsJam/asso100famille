<?php

namespace App\Entity;

use App\Entity\Abstract\EventAbstract;
use App\Repository\OneTimeEventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OneTimeEventRepository::class)]
class OneTimeEvent extends EventAbstract
{
    
}
