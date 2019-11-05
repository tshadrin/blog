<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HruRepository")
 * @ORM\Table(name="hru", uniqueConstraints={@UniqueConstraint(name="prefix_value", columns={"prefix", "value"})})
 */
class Hru
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string", length=300)
     */
    private $value;

    /**
     * @var string
     * @ORM\Column(type="string", length=300)
     */
    private $prefix;
    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false, name="entity_id")
     */
    private $entityId;

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

    public function __construct(string $prefix, string $value)
    {
        $this->value = $value;
        $this->prefix = $prefix;
    }

    public function setEntityId(int $entityId): void
    {
        $this->entityId = $entityId;
    }
}