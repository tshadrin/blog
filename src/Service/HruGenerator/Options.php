<?php

declare(strict_types=1);

namespace App\Service\HruGenerator;

class Options
{
    public function __construct(
        public string $prefix,
        public string $value,
        public int $entityId
    ) {}
}
