<?php

declare(strict_types=1);

namespace App\Service\Blog\Post\Add;

use App\Entity\Blog\Post;
use App\Entity\Blog\Status;
use App\Entity\Blog\Tag;
use App\Entity\User;
use App\Repository\Blog\PostRepository;
use App\Repository\Blog\TagRepository;
use App\Repository\HruRepository;
use App\Repository\UserRepository;
use App\Service\HruGenerator\HruGeneratorInterface;
use App\Service\HruGenerator\Options;
use Symfony\Component\Security\Core\Security;

class Handler
{
    private PostRepository $postRepository;
    private User $user;
    private UserRepository $userRepository;
    private HruGeneratorInterface $hruGenerator;
    private HruRepository $hruRepository;
    private TagRepository $tagRepository;

    public function __construct(
        PostRepository $postRepository,
        Security $security,
        UserRepository $userRepository,
        HruGeneratorInterface $hruGenerator,
        HruRepository $hruRepository,
        TagRepository $tagRepository
    ) {
        $this->postRepository = $postRepository;
        /** @var User $this->user */
        $this->user = $security->getUser();
        $this->userRepository = $userRepository;
        $this->hruGenerator = $hruGenerator;
        $this->hruRepository = $hruRepository;
        $this->tagRepository = $tagRepository;
    }

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
            $this->userRepository->find($this->user->getId())
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
