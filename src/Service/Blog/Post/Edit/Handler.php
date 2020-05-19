<?php

declare(strict_types=1);

namespace App\Service\Blog\Post\Edit;

use App\Entity\Blog\Post;
use App\Form\Blog\PostDTO;
use App\Repository\Blog\PostRepository;
use App\Repository\HruRepository;
use App\Service\HruGenerator\HruGeneratorInterface;
use App\Service\HruGenerator\Options;

class Handler
{
    private PostRepository $postRepository;
    private HruGeneratorInterface $hruGenerator;
    private HruRepository $hruRepository;

    public function __construct(
        PostRepository $postRepository,
        HruGeneratorInterface $hruGenerator,
        HruRepository $hruRepository
    ) {
        $this->postRepository = $postRepository;
        $this->hruGenerator = $hruGenerator;
        $this->hruRepository = $hruRepository;
    }

    public function handle(Command $command): void
    {
        if ($this->isTitleChanged($command->post, $command->postDTO) || $this->isSectionChanged($command->post, $command->postDTO)) {
            $hru = $this->hruGenerator->generate(new Options(
                $command->postDTO->section->getName(), //prefix
                $command->postDTO->title, //value
                $command->post->getId() //entityId
            ));
            $command->post->setHru($hru);
        }

        $command->post->updateFieldsByDTO($command->postDTO);
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
