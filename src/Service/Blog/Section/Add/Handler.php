<?php

declare(strict_types=1);

namespace App\Service\Blog\Section\Add;

use App\Entity\Blog\Section;
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
        $section = new Section(
            $command->sectionDTO->machine_name,
            $command->sectionDTO->name,
            Section::ENABLED,
            Section::NOT_HIDDEN
        );
        $this->sectionRepository->save($section);
        $this->sectionRepository->flush();
    }
}
