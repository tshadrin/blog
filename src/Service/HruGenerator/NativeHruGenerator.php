<?php

declare(strict_types=1);

namespace App\Service\HruGenerator;

use App\Entity\Hru;
use App\Repository\HruRepository;

class NativeHruGenerator implements HruGeneratorInterface
{
    private HruRepository $hruRepository;

    public function __construct(HruRepository $hruRepository)
    {
        $this->hruRepository = $hruRepository;
    }

    public function generate(Options $options): Hru
    {
        $prefix = $this->toLower($options->prefix);
        $value = $this->removeRestricted($options->value);
        $value = $this->replaceSpaceToDash($value);
        $value = $this->toLower($value);
        if ($this->hruRepository->isExists($prefix, $value)) {
            $value = $this->extend($prefix, $value);
        }

        $hru = new Hru($prefix, $value);
        return $hru;
    }

    public function removeRestricted(string $value): string
    {
        return preg_replace('/[^- a-zа-яё\d]/ui', '', $value);
    }

    public function replaceSpaceToDash(string $value): string
    {
        return preg_replace('/ /ui', '-', $value);
    }

    public function toLower(string $value): string
    {
        return mb_strtolower($value);
    }

    public function extend(string $prefix, string $value): string
    {
        $suffix = 0;
        while ($this->hruRepository->isExists($prefix, "{$value}-{$suffix}")) {
            $suffix++;
        }
        return "{$value}-{$suffix}";
    }
}
