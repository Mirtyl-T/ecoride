<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ORM\Table(name: '`reviews`')]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Relation ManyToOne vers Trip
    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trip $trip = null;

    // Relation ManyToOne vers User qui écrit la review
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviewsGiven')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $reviewer = null;

    // Relation ManyToOne vers User qui reçoit la review
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviewsReceived')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $driver = null;

    // Colonnes simples
    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $note = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    private ?bool $valide = false;

    // Constructor
    public function __construct()
    {
    }

    // Getters et setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(?Trip $trip): static
    {
        $this->trip = $trip;
        return $this;
    }

    public function getReviewer(): ?User
    {
        return $this->reviewer;
    }

    public function setReviewer(?User $reviewer): static
    {
        $this->reviewer = $reviewer;
        return $this;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): static
    {
        $this->driver = $driver;
        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): static
    {
        $this->note = $note;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): static
    {
        $this->valide = $valide;
        return $this;
    }
}
