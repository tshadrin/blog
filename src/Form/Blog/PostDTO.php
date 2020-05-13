<?php

declare(strict_types=1);

namespace App\Form\Blog;

use App\Entity\Blog\Post;
use App\Entity\Blog\Section;
use App\Entity\Blog\Status;
use App\Entity\Blog\Tag;

class PostDTO
{
    /** @var string */
    public $title;
    /** @var string */
    public $teaser;
    /** @var string */
    public $body;
    /** @var Section */
    public $section;
    /** @var Tag[] */
    public $tags;
    /** @var Status */
    public $status;

    public static function createFromPost(Post $post)
    {
        $postDTO = new self();
        $postDTO->title = $post->getTitle();
        $postDTO->teaser = $post->getTeaser();
        $postDTO->body = $post->getBody();
        $postDTO->section = $post->getSection();
        $postDTO->tags = $post->getTags();
        $postDTO->status = $post->getStatus();
        return $postDTO;
    }
}
