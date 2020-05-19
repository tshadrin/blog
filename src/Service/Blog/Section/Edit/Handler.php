<?php

declare(strict_types=1);

namespace App\Service\Blog\Section\Edit;

use App\Repository\Blog\SectionRepository;

class Handler
{
    private SectionRepository $sectionRepository;

    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function handle(Command $command): void
    {
        $command->section->setName($command->sectionDTO->name);
        $command->section->setMachineName($command->sectionDTO->machine_name);
        $command->section->setEnabled($command->sectionDTO->enabled);
        $command->section->setHidden($command->sectionDTO->hidden);
        $this->sectionRepository->save($command->section);
        $this->sectionRepository->flush();
    }
}
