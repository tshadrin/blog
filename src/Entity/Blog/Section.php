<?php

declare(strict_types=1);

namespace App\Entity\Blog;

use App\Repository\Blog\SectionRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: SectionRepository::class)]
#[Table(name: "sections")]
class Section
{
    public const ENABLED = true;
    public const NOT_HIDDEN = false;


    #[Id]
    #[GeneratedValue(strategy: "AUTO")]
    #[Column(type: "integer")]
    private int $id;

    #[Column(type: "string", name: "machine_name", length: 50)]
    private string $machineName;

    #[Column(type: "string", length: 50)]
    private string $name;

    #[Column(type: "boolean")]
    private bool $enabled;

    #[Column(type: "boolean")]
    private bool $hidden;

    public function __construct(string $machineName, string $name, bool $enabled, bool $hidden)
    {
        $this->machineName = $machineName;
        $this->name = $name;
        $this->enabled = $enabled;
        $this->hidden = $hidden;
    }

    public function getMachineName(): string
    {
        return $this->machineName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setMachineName(string $machineName): void
    {
        $this->machineName = $machineName;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
    public function isHidden(): bool
    {
        return $this->hidden;
    }
    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }
}
