<?php
declare(strict_types=1);

namespace App\Service\Blog\Post\Edit;

use App\Entity\Blog\Post;
use App\Form\Blog\PostDTO;

class Command
{
    public $post;
    public $postDTO;

    public function __construct(Post $post, PostDTO $postDTO)
    {
        $this->post = $post;
        $this->postDTO = $postDTO;
    }
}