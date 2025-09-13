<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class Log
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: 'int', nullable: true)]
    private ?int $userId = null;

    #[MongoDB\Field(type: 'string')]
    private string $action;

    #[MongoDB\Field(type: 'string', nullable: true)]
    private ?string $ipAddress = null;

    #[MongoDB\Field(type: 'date')]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?string { return $this->id; }

    public function getUserId(): ?int { return $this->userId; }
    public function setUserId(?int $userId): self { $this->userId = $userId; return $this; }

    public function getAction(): string { return $this->action; }
    public function setAction(string $action): self { $this->action = $action; return $this; }

    public function getIpAddress(): ?string { return $this->ipAddress; }
    public function setIpAddress(?string $ip): self { $this->ipAddress = $ip; return $this; }

    public function getCreatedAt(): \DateTime { return $this->createdAt; }
    public function setCreatedAt(\DateTime $createdAt): self { $this->createdAt = $createdAt; return $this; }
}
