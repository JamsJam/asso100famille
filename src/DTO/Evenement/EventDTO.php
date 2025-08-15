<?php

namespace App\DTO\Evenement;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EventDTO
{
     #[Assert\NotBlank(message: 'Veuillez choisir un type d\'événement.')]
    #[Assert\Choice(choices: ['one_time', 'recurring'], message: 'Type invalide.')]
    private ?string $type;

    #[Assert\NotBlank(message: 'Le titre est obligatoire.')]
    private ?string $title;

    #[Assert\NotBlank(message: 'La description est obligatoire.')]
    private ?string $description;

    #[Assert\NotNull(message: 'Veuillez ajouter une image.')]
    private ?UploadedFile $image = null;

    #[Assert\NotNull(message: 'Veuillez préciser si l\'événement est gratuit ou non.')]
    private bool $isFree;

    #[Assert\NotBlank(message: 'Veuillez saisir une date de début.')]
    private ?string $startDate;

    #[Assert\NotBlank(message: 'Veuillez saisir une heure de début.')]
    private ?string $startHour;

    #[Assert\NotBlank(message: 'Veuillez saisir une date de fin.')]
    private \DateTimeImmutable $endDate;

    #[Assert\NotBlank(message: 'Veuillez saisir une heure de fin.')]
    private ?string $endHour;

    #[Assert\NotNull]
    private float $prix;

    #[Assert\NotNull]
    private float $userPrix;

    // Exemple : ['dayOfWeek' => 'Wednesday']
    private ?array $recurringRule = null;




    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of image
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of isFree
     */ 
    public function getIsFree()
    {
        return $this->isFree;
    }

    /**
     * Set the value of isFree
     *
     * @return  self
     */ 
    public function setIsFree($isFree)
    {
        $this->isFree = $isFree;

        return $this;
    }

    /**
     * Get the value of startDate
     */ 
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set the value of startDate
     *
     * @return  self
     */ 
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get the value of startHour
     */ 
    public function getStartHour()
    {
        return $this->startHour;
    }

    /**
     * Set the value of startHour
     *
     * @return  self
     */ 
    public function setStartHour($startHour)
    {
        $this->startHour = $startHour;

        return $this;
    }

    /**
     * Get the value of endDate
     */ 
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set the value of endDate
     *
     * @return  self
     */ 
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get the value of endHour
     */ 
    public function getEndHour()
    {
        return $this->endHour;
    }

    /**
     * Set the value of endHour
     *
     * @return  self
     */ 
    public function setEndHour($endHour)
    {
        $this->endHour = $endHour;

        return $this;
    }

    /**
     * Get the value of prix
     */ 
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set the value of prix
     *
     * @return  self
     */ 
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get the value of userPrix
     */ 
    public function getUserPrix()
    {
        return $this->userPrix;
    }

    /**
     * Set the value of userPrix
     *
     * @return  self
     */ 
    public function setUserPrix($userPrix)
    {
        $this->userPrix = $userPrix;

        return $this;
    }

    /**
     * Get the value of recurringRule
     */ 
    public function getRecurringRule()
    {
        return $this->recurringRule;
    }

    /**
     * Set the value of recurringRule
     *
     * @return  self
     */ 
    public function setRecurringRule($recurringRule)
    {
        $this->recurringRule = $recurringRule;

        return $this;
    }


}
