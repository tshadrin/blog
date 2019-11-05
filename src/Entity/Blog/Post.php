<?php
declare(strict_types=1);

namespace App\Entity\Blog;

use App\Entity\Hru;
use App\Entity\User;
use App\Form\Blog\PostDTO;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Post
 * @ORM\Entity(repositoryClass="App\Repository\Blog\PostRepository")
 * @ORM\Table(name="posts")
 */
class Post
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;
    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $body;
    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $created;
    /**
     * @var Section
     * @ORM\ManyToOne(targetEntity="App\Entity\Blog\Section")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id", nullable=false)
     */
    private $section;

    /**
     * @var Tag[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Blog\Tag", fetch="EAGER")
     * @ORM\JoinTable(name="posts_tags", joinColumns={
     *     @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     * })
     */
    private $tags;

    /**
     * @var Status
     * @ORM\Column(type="post_status", name="status", length=30, nullable=false)
     */
    private $status;
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     */
    private $author;
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true, length=1000)
     */
    private $teaser;

    /**
     * @var Hru
     * @ORM\ManyToOne(targetEntity="App\Entity\Hru", cascade={"PERSIST"})
     * @ORM\JoinColumn(name="hru_id", referencedColumnName="id", nullable=true)
     */
    private $hru;

    public function __construct(string $title, string $teaser, \DateTimeImmutable $created, string $body, Section $section, iterable $tags, Status $status, User $author)
    {
        $this->title = $title;
        $this->body = $body;
        $this->created = $created;
        $this->section = $section;
        $this->tags = $tags;
        $this->status = $status;
        $this->author = $author;
        $this->teaser = $teaser;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @return Section
     */
    public function getSection(): Section
    {
        return $this->section;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }
    public function getTags(): ?object
    {
        return $this->tags;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getTeaser(): ?string
    {
        return $this->teaser;
    }

    /**
     * @return int
     */
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
        $this->status = $postDTO->status;
        $this->tags = $postDTO->tags;
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

    public function setHru(Hru $hru): void
    {
        $this->hru = $hru;
        $hru->setEntityId($this->id);
    }

    public function getHru(): ?Hru
    {
        return $this->hru;
    }
}