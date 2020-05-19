<?php

declare(strict_types=1);

namespace App\Service\HruGenerator;

class Options
{
    public string $prefix;
    public string $value;
    public int $entityId;

    public function __construct(string $prefix, string $value, int $entityId)
    {
        $this->prefix = $prefix;
        $this->value = $value;
        $this->entityId = $entityId;
    }
}
