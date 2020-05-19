<?php

declare(strict_types=1);

namespace App\Form\Tag;

use App\Entity\Blog\Tag;

class TagDTO
{
    public string $name;
    public static function createFromTag(Tag $tag)
    {
        $tagDTO = new self();
        $tagDTO->name = $tag->getName();
        return $tagDTO;
    }
}
