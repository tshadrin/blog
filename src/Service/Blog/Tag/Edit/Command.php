<?php

declare(strict_types=1);

namespace App\Service\Blog\Tag\Edit;

use App\Entity\Blog\Tag;
use App\Form\Tag\TagDTO;

class Command
{
    public function __construct(
        public Tag $tag,
        public TagDTO $tagDTO
    ) {}
}
