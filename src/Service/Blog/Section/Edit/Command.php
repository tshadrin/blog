<?php

declare(strict_types=1);

namespace App\Service\Blog\Section\Edit;

use App\Entity\Blog\Section;
use App\Form\Section\SectionDTO;

class Command
{
    public function __construct(
        public Section $section,
        public SectionDTO $sectionDTO
    ) {}
}
