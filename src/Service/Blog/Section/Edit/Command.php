<?php

declare(strict_types=1);

namespace App\Service\Blog\Section\Edit;

use App\Entity\Blog\Section;
use App\Form\Section\SectionDTO;

class Command
{
    public $section;
    public $sectionDTO;
    public function __construct(Section $section, SectionDTO $sectionDTO)
    {
        $this->section = $section;
        $this->sectionDTO = $sectionDTO;
    }
}
