<?php

declare(strict_types=1);

namespace App\Service\HruGenerator;

class Options
{
    /** @var string */
    public $prefix;
/** @var string */
    public $value;
/** @var int */
    public $entityId;
    public function __construct(string $prefix, string $value, int $entityId)
    {
        $this->prefix = $prefix;
        $this->value = $value;
        $this->entityId = $entityId;
    }
}
