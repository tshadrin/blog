<?php

declare(strict_types=1);

namespace App\Service\Blog\Post\Add;

use App\Entity\Blog\Post;
use App\Entity\Blog\Status;
use App\Repository\Blog\PostRepository;
use App\Repository\Blog\TagRepository;
use App\Repository\UserRepository;
use App\Service\HruGenerator\HruGeneratorInterface;
use App\Service\HruGenerator\Options;
use Symfony\Component\Security\Core\Security;

class Handler
{
    public function __construct(
        private PostRepository        $postRepository,
        private Security              $security,
        private UserRepository        $userRepository,
        private HruGeneratorInterface $hruGenerator,
        private TagRepository         $tagRepository
    ) {}

    public function handle(Command $command): void
    {

        $post = new Post(
            $command->postDTO->title,
            $command->postDTO->teaser,
            new \DateTimeImmutable(),
            $command->postDTO->body,
            $command->postDTO->section,
            $this->tagRepository->getTagsFromSelectable($command->postDTO->tags2),
            new Status($command->postDTO->status),
            $this->userRepository->find($this->security->getUser()->getId())
        );
        $this->postRepository->save($post);
        $this->postRepository->flush();
        $hru = $this->hruGenerator->generate(new Options(
            prefix: $post->getSection()->getName(),
            value: $post->getTitle(),
            entityId: $post->getId()
        ));
        $post->setHru($hru);
        $this->postRepository->flush();
    }
}
