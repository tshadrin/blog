<?php

declare(strict_types=1);

namespace App\Form\Section;

use App\Entity\Blog\Section;

class SectionDTO
{
    public string $machine_name;
    public string $name;
    public bool $enabled;
    public bool $hidden;
    public static function createFromSection(Section $section)
    {
        $sectionDTO = new self();
        $sectionDTO->name = $section->getName();
        $sectionDTO->machine_name = $section->getMachineName();
        $sectionDTO->enabled = $section->isEnabled();
        $sectionDTO->hidden = $section->isHidden();
        return $sectionDTO;
    }
}
