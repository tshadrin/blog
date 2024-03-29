<?php

declare(strict_types=1);

namespace App\Service\Blog\Post\Delete;

use App\Entity\Blog\Post;
use App\Entity\Blog\Status;
use App\Repository\Blog\PostRepository;

class Handler
{
    public function __construct(
        private PostRepository $postRepository
    ) {}

    public function handle(Post $post): void
    {
        $post->setStatus(new Status(Status::REMOVE));
        $this->postRepository->save($post);
        $this->postRepository->flush();
    }
}
