<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
#[ORM\Table(name: '`participations`')]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Relation ManyToOne vers Trip
    #[ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Trip $trip = null;

    // Relation ManyToOne vers User
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?User $user = null;

    // Colonnes simples
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $date_participation = null;

    #[ORM\Column(type: "string", length: 20)]
    private ?string $statut = 'en attente';

    public const STATUTS = ['en attente', 'confirmé', 'annulé'];

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        if (!in_array($statut, self::STATUTS, true)) {
            throw new \InvalidArgumentException("Statut invalide : $statut");
        }
        $this->statut = $statut;
        return $this;
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getDateParticipation(): ?\DateTimeInterface
    {
        return $this->date_participation;
    }

    public function setDateParticipation(?\DateTimeInterface $date_participation): static
    {
        $this->date_participation = $date_participation;
        return $this;
    }

}
