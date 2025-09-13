<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class Historique
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: 'int')]
    private int $covoiturageId;

    #[MongoDB\Field(type: 'int')]
    private int $userId;

    #[MongoDB\Field(type: 'string')]
    private string $role; // passager ou chauffeur

    #[MongoDB\Field(type: 'string')]
    private string $action; // réservé, annulé, terminé...

    #[MongoDB\Field(type: 'date')]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?string { return $this->id; }

    public function getCovoiturageId(): int { return $this->covoiturageId; }
    public function setCovoiturageId(int $id): self { $this->covoiturageId = $id; return $this; }

    public function getUserId(): int { return $this->userId; }
    public function setUserId(int $id): self { $this->userId = $id; return $this; }

    public function getRole(): string { return $this->role; }
    public function setRole(string $role): self { $this->role = $role; return $this; }

    public function getAction(): string { return $this->action; }
    public function setAction(string $action): self { $this->action = $action; return $this; }

    public function getCreatedAt(): \DateTime { return $this->createdAt; }
    public function setCreatedAt(\DateTime $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
