<?php

declare(strict_types=1);

namespace App\Service\Blog\Tag\Edit;

use App\Entity\Blog\Tag;
use App\Form\Tag\TagDTO;

class Command
{
    public Tag $tag;
    public TagDTO $tagDTO;

    public function __construct(Tag $tag, TagDTO $tagDTO)
    {
        $this->tag = $tag;
        $this->tagDTO = $tagDTO;
    }
}
