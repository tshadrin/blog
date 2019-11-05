<?php
declare(strict_types=1);

namespace App\Service\HruGenerator;

use App\Entity\Hru;

interface HruGeneratorInterface
{
    public function generate(Options $options) : Hru;
}