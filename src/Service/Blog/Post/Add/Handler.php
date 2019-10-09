<?php
declare(strict_types=1);

namespace App\Service\Blog\Post\Add;

use App\Entity\Blog\Post;
use App\Entity\Blog\Status;
use App\Repository\Blog\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Handler
{
    /** @var PostRepository  */
    private $postRepository;
    /** @var TokenStorageInterface  */
    private $tokenStorage;
    /** @var UserRepository  */
    private $userRepository;

    public function __construct(PostRepository $postRepository, TokenStorageInterface $tokenStorage, UserRepository $userRepository)
    {
        $this->postRepository = $postRepository;
        $this->tokenStorage = $tokenStorage;
        $this->userRepository = $userRepository;
    }

    public function handle(Command $command): void
    {
        $post = new Post(
            $command->postDTO->title,
            $command->postDTO->teaser,
            new \DateTimeImmutable(),
            $command->postDTO->body,
            $command->postDTO->section,
            $command->postDTO->tags,
            new Status($command->postDTO->status),
            $this->userRepository->find($this->tokenStorage->getToken()->getUser()->getId())
        );
        $this->postRepository->save($post);
        $this->postRepository->flush();
    }
}