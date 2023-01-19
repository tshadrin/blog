<?php

declare(strict_types=1);

namespace App\Service\Blog\Post\Edit;

use App\Entity\Blog\Post;
use App\Form\Blog\PostDTO;
use App\Repository\Blog\PostRepository;
use App\Repository\Blog\TagRepository;
use App\Service\HruGenerator\HruGeneratorInterface;
use App\Service\HruGenerator\Options;

class Handler
{
    public function __construct(
        private PostRepository        $postRepository,
        private HruGeneratorInterface $hruGenerator,
        private TagRepository         $tagRepository
    ) {}

    public function handle(Command $command): void
    {
        if ($this->isTitleChanged($command->post, $command->postDTO) || $this->isSectionChanged($command->post, $command->postDTO)) {
            $hru = $this->hruGenerator->generate(new Options(
                prefix: $command->postDTO->section->getName(),
                value: $command->postDTO->title,
                entityId: $command->post->getId()
            ));
            $command->post->setHru($hru);
        }
        $command->post->updateFieldsByDTO($command->postDTO);
        $command->post->setTags($this->tagRepository->getTagsFromSelectable($command->postDTO->tags2));
        $this->postRepository->save($command->post);
        $this->postRepository->flush();
    }

    private function isTitleChanged(Post $post, PostDTO $postDTO): bool
    {
        return $post->getTitle() !== $postDTO->title;
    }

    private function isSectionChanged(Post $post, PostDTO $postDTO): bool
    {
        return $post->getSection()->getId() !== $postDTO->section->getId();
    }
}
