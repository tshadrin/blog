<?php

declare(strict_types=1);

namespace App\Service\Blog\Tag\Edit;

use App\Repository\Blog\TagRepository;

class Handler
{
    public function __construct(
        private TagRepository $tagRepository
    ) {}

    public function handle(Command $command): void
    {
        $command->tag->setName($command->tagDTO->name);
        $this->tagRepository->save($command->tag);
        $this->tagRepository->flush();
    }
}
