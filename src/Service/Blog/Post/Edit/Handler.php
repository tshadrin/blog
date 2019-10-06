<?php
declare(strict_types=1);

namespace App\Service\Blog\Post\Edit;


use App\Repository\Blog\PostRepository;

class Handler
{
    /** @var PostRepository  */
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function handle(Command $command): void
    {
        $command->post->updateFieldsByDTO($command->postDTO);
        $this->postRepository->save($command->post);
        $this->postRepository->flush();
    }
}