<?php

declare(strict_types=1);

namespace App\Service\Blog\Tag\Add;

use App\Entity\Blog\Tag;
use App\Repository\Blog\TagRepository;

class Handler
{
    /** @var TagRepository  */
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function handle(Command $command): void
    {
        $tag = new Tag($command->tagDTO->name);
        $this->tagRepository->save($tag);
        $this->tagRepository->flush();
    }
}
