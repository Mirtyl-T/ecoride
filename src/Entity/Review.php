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

    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trip $trip = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviewsGiven')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $reviewer = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviewsReceived')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $driver = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $note = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    private ?bool $valide = false;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    private bool $isIncident = false;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $incidentDescription = null;

    #[ORM\Column(length: 20)]
    private string $etat = 'en_attente';

    public function isIncident(): bool
    {
        return $this->isIncident;
    }

    public function setIsIncident(bool $isIncident): static
    {
        $this->isIncident = $isIncident;
        return $this;
    }

    public function getIncidentDescription(): ?string
    {
        return $this->incidentDescription;
    }

    public function setIncidentDescription(?string $incidentDescription): static
    {
        $this->incidentDescription = $incidentDescription;
        return $this;
    }



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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }
}
