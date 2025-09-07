<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Vehicule;
use App\Entity\Participation;
use App\Entity\Preference;
use App\Entity\Review;
use App\Entity\Trip;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    // Relations
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Vehicule::class)]
    private Collection $vehicules;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Participation::class)]
    private Collection $participations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Preference::class)]
    private Collection $preferences;

    #[ORM\OneToMany(mappedBy: 'reviewer', targetEntity: Review::class)]
    private Collection $reviewsGiven;

    #[ORM\OneToMany(mappedBy: 'driver', targetEntity: Review::class)]
    private Collection $reviewsReceived;

    #[ORM\OneToMany(mappedBy: 'driver', targetEntity: Trip::class)]
    private Collection $trips;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $username = null;

    #[ORM\Column(options: ["default" => 20])]
    private ?int $credits = 20;

    #[ORM\Column(options: ["default" => true])]
    private ?bool $actif = true;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    public const roles = [
        'ROLE_USER',
        'ROLE_CHAUFFEUR',
        'ROLE_PASSAGER',
        'ROLE_EMPLOYE',
        'ROLE_ADMIN'

    ];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'utilisateur';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        foreach ($roles as $role) {
            if (!in_array($role, self::roles, true)) {
                throw new \InvalidArgumentException("Role invalide : $role");
            }
        }
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function __construct()
    {
        $this->vehicules = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->preferences = new ArrayCollection();
        $this->reviewsGiven = new ArrayCollection();
        $this->reviewsReceived = new ArrayCollection();
        $this->trips = new ArrayCollection();
    }

    // Getters et setters pour les champs de sécurité
    public function eraseCredentials(): void {}

    // Getters pour les relations
    public function getVehicules(): Collection
    {
        return $this->vehicules;
    }

    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function getPreferences(): Collection
    {
        return $this->preferences;
    }

    public function getReviewsGiven(): Collection
    {
        return $this->reviewsGiven;
    }

    public function getReviewsReceived(): Collection
    {
        return $this->reviewsReceived;
    }

    public function getTrips(): Collection
    {
        return $this->trips;
    }

    // Getters et setters pour les champs supplémentaires
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
        $roles[] = 'UTILISATEUR';
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getCredits(): ?int
    {
        return $this->credits;
    }

    public function setCredits(?int $credits): static
    {
        $this->credits = $credits;
        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(?\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
