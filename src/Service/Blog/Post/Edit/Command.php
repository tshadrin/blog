<?php

declare(strict_types=1);

namespace App\Service\Blog\Post\Edit;

use App\Entity\Blog\Post;
use App\Form\Blog\PostDTO;

class Command
{
    public function __construct(
        public Post $post,
        public PostDTO $postDTO
    ) {}
}
