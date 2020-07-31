<?php

declare(strict_types=1);

namespace App\Entity\Blog;

use App\Entity\Hru;
use App\Entity\User;
use App\Form\Blog\PostDTO;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Blog\PostRepository")
 * @ORM\Table(name="posts")
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="string")
     */
    private string $title;
    /**
     * @ORM\Column(type="text")
     */
    private string $body;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $created;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Blog\Section")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id", nullable=false)
     */
    private Section $section;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Blog\Tag", fetch="EAGER")
     * @ORM\JoinTable(name="posts_tags", joinColumns={
     *     @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     * })
     */
    private iterable $tags;
    /**
     * @ORM\Column(type="post_status", name="status", length=30, nullable=false)
     */
    private Status $status;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     */
    private User $author;
    /**
     * @ORM\Column(type="text", nullable=true, length=1000)
     */
    private ?string $teaser;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hru", cascade={"PERSIST"}, fetch="EAGER")
     * @ORM\JoinColumn(name="hru_id", referencedColumnName="id", nullable=true)
     */
    private ?Hru $hru = null;

    public function __construct(
        string $title,
        string $teaser,
        \DateTimeImmutable $created,
        string $body,
        Section $section,
        iterable $tags,
        Status $status,
        User $author
    ) {
        $this->title = $title;
        $this->body = $body;
        $this->created = $created;
        $this->section = $section;
        $this->tags = $tags;
        $this->status = $status;
        $this->author = $author;
        $this->teaser = $teaser;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }

    public function getSection(): Section
    {
        return $this->section;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getTeaser(): ?string
    {
        return $this->teaser;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function updateFieldsByDTO(PostDTO $postDTO): void
    {
        $this->title = $postDTO->title;
        $this->teaser = $postDTO->teaser;
        $this->body = $postDTO->body;
        $this->section = $postDTO->section;
        $this->status = new Status($postDTO->status);
    }

    public function setTags(iterable $tags): void
    {
        $this->tags = $tags;
    }

    public function isPublished(): bool
    {
        return $this->status->isPublished();
    }

    public function isDraft(): bool
    {
        return $this->status->isDraft();
    }

    public function isRemoved(): bool
    {
        return $this->status->isRemoved();
    }

    public function isPrivate(): bool
    {
        return $this->status->isPrivate();
    }

    public function setHru(Hru $hru): void
    {
        if (is_null($this->id)) {
            throw new \DomainException("Id needed for hru. Save post before setting hru.");
        }
        $this->hru = $hru;
        $hru->setEntityId($this->id);
    }

    public function getHru(): ?Hru
    {
        return $this->hru;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }
}
