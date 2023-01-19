<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\HruRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity(repositoryClass: HruRepository::class)]
#[Table(name: "hru")]
#[UniqueConstraint(name: "prefix_value", columns: ["prefix", "value"])]
class Hru
{
    #[Id]
    #[GeneratedValue(strategy: "AUTO")]
    #[Column(type: "integer")]
    private int $id;

    #[Column(type: "string", length: 300)]
    private string $value;

    #[Column(type: "string", length: 300)]
    private string $prefix;

    #[Column(type: "integer", name: "entity_id", nullable: false)]
    private ?int $entityId =  null;

    public function __construct(string $prefix, string $value)
    {
        $this->value = $value;
        $this->prefix = $prefix;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getEntityId(): int
    {
        return $this->entityId;
    }

    public function setEntityId(int $entityId): void
    {
        $this->entityId = $entityId;
    }
}
