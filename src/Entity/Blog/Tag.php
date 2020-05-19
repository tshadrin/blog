<?php

declare(strict_types=1);

namespace App\Entity\Blog;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Tag
 * @package App\Entity\Blog
 * @ORM\Entity(repositoryClass="App\Repository\Blog\TagRepository")
 * @ORM\Table("tags")
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
