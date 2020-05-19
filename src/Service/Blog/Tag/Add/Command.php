<?php

declare(strict_types=1);

namespace App\Service\Blog\Tag\Add;

use App\Form\Tag\TagDTO;

class Command
{
    public TagDTO $tagDTO;

    public function __construct(TagDTO $tagDTO)
    {
        $this->tagDTO = $tagDTO;
    }
}
