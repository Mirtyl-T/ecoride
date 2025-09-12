<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $username = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(columnDefinition: "ENUM('utilisateur','chauffeur','passager','employe','admin')")]
    private ?string $role = 'utilisateur';

    #[ORM\Column(options: ["default" => 20])]
    private ?int $credits = 20;

    #[ORM\Column(options: ["default" => true])]
    private ?bool $actif = true;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeInterface $date_creation = null;

    // Relations
    #[ORM\OneToMany(mappedBy: "user", targetEntity: Vehicule::class)]
    private Collection $vehicules;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: Participation::class)]
    private Collection $participations;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: Preference::class)]
    private Collection $preferences;

    #[ORM\OneToMany(mappedBy: "reviewer", targetEntity: Review::class)]
    private Collection $reviewsGiven;

    #[ORM\OneToMany(mappedBy: "driver", targetEntity: Review::class)]
    private Collection $reviewsReceived;

    #[ORM\OneToMany(mappedBy: "driver", targetEntity: Trip::class)]
    private Collection $trips;

    public function __construct()
    {
        $this->vehicules = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->preferences = new ArrayCollection();
        $this->reviewsGiven = new ArrayCollection();
        $this->reviewsReceived = new ArrayCollection();
        $this->trips = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
