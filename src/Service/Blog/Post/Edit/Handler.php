<?php
declare(strict_types=1);

namespace App\Service\Blog\Post\Edit;

use App\Repository\Blog\PostRepository;
use App\Repository\HruRepository;
use App\Service\HruGenerator\HruGeneratorInterface;
use App\Service\HruGenerator\Options;

class Handler
{
    /** @var PostRepository  */
    private $postRepository;
    /** @var HruGeneratorInterface */
    private $hruGenerator;
    /** @var HruRepository */
    private $hruRepository;

    public function __construct(PostRepository $postRepository,
                                HruGeneratorInterface $hruGenerator,
                                HruRepository $hruRepository)
    {
        $this->postRepository = $postRepository;
        $this->hruGenerator = $hruGenerator;
        $this->hruRepository = $hruRepository;
    }

    public function handle(Command $command): void
    {
        if ($this->isTitleChanged($command->post->getTitle(), $command->postDTO->title)) {
            $hru = $this->hruGenerator->generate(
                new Options(
                    $command->post->getSection()->getName(),  //prefix
                    $command->postDTO->title,                 //value
                    $command->post->getId()                   //entityId
                )
            );
            $command->post->setHru($hru);
        }

        $command->post->updateFieldsByDTO($command->postDTO);
        $this->postRepository->save($command->post);
        $this->postRepository->flush();
    }

    private function isTitleChanged(string $old, string $new): bool
    {
        return $old !== $new;
    }
}