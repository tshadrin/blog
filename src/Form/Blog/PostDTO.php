<?php

declare(strict_types=1);

namespace App\Form\Blog;

use App\Entity\Blog\Post;
use App\Entity\Blog\Section;
use App\Entity\Blog\Status;
use App\Entity\Blog\Tag;
use Doctrine\Common\Collections\Collection;

class PostDTO
{
    public string $title;
    public ?string $teaser;
    public string $body;
    public Section $section;
    public Collection $tags;
    public string $status;

    public static function createFromPost(Post $post)
    {
        $postDTO = new self();
        $postDTO->title = $post->getTitle();
        $postDTO->teaser = $post->getTeaser();
        $postDTO->body = $post->getBody();
        $postDTO->section = $post->getSection();
        $postDTO->tags = $post->getTags();
        $postDTO->status = $post->getStatus()->getName();
        return $postDTO;
    }
}
