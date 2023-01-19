<?php

declare(strict_types=1);

namespace App\Service\Blog\Post\Add;

use App\Form\Blog\PostDTO;

class Command
{
    public function __construct(
        public PostDTO $postDTO
    ) {}
}
