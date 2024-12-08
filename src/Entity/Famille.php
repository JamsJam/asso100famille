<?php

namespace App\Entity;

use App\Repository\FamilleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FamilleRepository::class)]
class Famille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $membre = null;

    #[ORM\Column]
    private ?int $nbAdultes = null;

    #[ORM\Column]
    private ?int $nbMineurs = null;

    #[ORM\Column]
    private ?int $nbHommes = null;

    #[ORM\Column]
    private ?int $nbFemmes = null;

    #[ORM\Column]
    private ?int $nbGarcons = null;

    #[ORM\Column]
    private ?int $nbFilles = null;

    #[ORM\ManyToOne(inversedBy: 'familles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FamilleType $type = null;

    #[ORM\OneToOne(inversedBy: 'famille', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMembre(): ?int
    {
        return $this->membre;
    }

    public function setMembre(int $membre): static
    {
        $this->membre = $membre;

        return $this;
    }

    public function getNbAdultes(): ?int
    {
        return $this->nbAdultes;
    }

    public function setNbAdultes(int $nbAdultes): static
    {
        $this->nbAdultes = $nbAdultes;

        return $this;
    }

    public function getNbMineurs(): ?int
    {
        return $this->nbMineurs;
    }

    public function setNbMineurs(int $nbMineurs): static
    {
        $this->nbMineurs = $nbMineurs;

        return $this;
    }

    public function getNbHommes(): ?int
    {
        return $this->nbHommes;
    }

    public function setNbHommes(int $nbHommes): static
    {
        $this->nbHommes = $nbHommes;

        return $this;
    }

    public function getNbFemmes(): ?int
    {
        return $this->nbFemmes;
    }

    public function setNbFemmes(int $nbFemmes): static
    {
        $this->nbFemmes = $nbFemmes;

        return $this;
    }

    public function getNbGarcons(): ?int
    {
        return $this->nbGarcons;
    }

    public function setNbGarcons(int $nbGarcons): static
    {
        $this->nbGarcons = $nbGarcons;

        return $this;
    }

    public function getNbFilles(): ?int
    {
        return $this->nbFilles;
    }

    public function setNbFilles(int $nbFilles): static
    {
        $this->nbFilles = $nbFilles;

        return $this;
    }

    public function getType(): ?FamilleType
    {
        return $this->type;
    }

    public function setType(?FamilleType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

}
