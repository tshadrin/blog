<?php
declare(strict_types=1);

namespace App\Form\Section;

use App\Entity\Blog\Section;

class SectionDTO
{
    public $machine_name;
    public $name;

    public static function createFromSection(Section $section)
    {
        $sectionDTO = new self();
        $sectionDTO->name = $section->getName();
        $sectionDTO->machine_name = $section->getMachineName();
        return $sectionDTO;
    }
}