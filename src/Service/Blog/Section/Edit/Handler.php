<?php
declare(strict_types=1);

namespace App\Service\Blog\Section\Edit;

use App\Repository\Blog\SectionRepository;

class Handler
{
    /** @var SectionRepository  */
    private $sectionRepository;

    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function handle(Command $command): void
    {
        $command->section->setName($command->sectionDTO->name);
        $command->section->setMachineName($command->section->machine_name);
        $this->sectionRepository->save($command->section);
        $this->sectionRepository->flush();
    }
}