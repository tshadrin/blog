<?php
declare(strict_types=1);

namespace App\Entity\Blog;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Section
 * @ORM\Entity(repositoryClass="App\Repository\Blog\SectionRepository")
 * @ORM\Table(name="sections")
 */
class Section
{
    public const ENABLED = true;
    public const NOT_HIDDEN = false;
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="machine_name", length=50)
     */
    private $machineName;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    private $name;
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $enabled;
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $hidden;

    public function __construct(string $machineName, string $name, bool $enabled, bool $hidden)
    {
        $this->machineName = $machineName;
        $this->name = $name;
        $this->enabled = $enabled;
        $this->hidden = $hidden;
    }

    /**
     * @return string
     */
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