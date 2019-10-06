<?php
declare(strict_types=1);

namespace App\Form\Blog;


use App\Entity\Blog\Post;

class PostDTO
{
    public $title;
    public $teaser;
    public $body;
    public $section;
    public $tags;
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