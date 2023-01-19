<?php

declare(strict_types=1);

namespace App\Service\Blog\Tag\Add;

use App\Form\Tag\TagDTO;

class Command
{
    public function __construct(
        public TagDTO $tagDTO
    ) {}
}
