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

    public function __construct(string $machineName, string $name, bool $enabled)
    {
        $this->machineName = $machineName;
        $this->name = $name;
        $this->enabled = $enabled;
    }

    /**
     * @return string
     */
    public function getMachineName(): string
    {
        return $this->machineName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $machineName
     */
    public function setMachineName(string $machineName): void
    {
        $this->machineName = $machineName;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}