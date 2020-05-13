<?php

declare(strict_types=1);

namespace App\Service\Blog\Section\Add;

use App\Form\Section\SectionDTO;

class Command
{
    /** @var SectionDTO */
    public $sectionDTO;

    public function __construct(SectionDTO $sectionDTO)
    {
        $this->sectionDTO = $sectionDTO;
    }
}
