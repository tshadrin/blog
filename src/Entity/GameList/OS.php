<?php

declare(strict_types=1);

namespace App\Entity\GameList;

use Webmozart\Assert\Assert;

class OS
{
    public const XBOX_ONE = 'Xbox One';
    public const XBOX_SERIES = 'Xbox Series';
    public const PLAYSTATION_4 = 'Playstation 4';
    public const PLAYSTATION_5 = 'Playstation 5';
    public const PC = 'PC';
    public const SWITCH = 'Switch';

    public const ALL_PLATFORMS = [
        self::XBOX_ONE => self::XBOX_ONE,
        self::XBOX_SERIES => self::XBOX_SERIES,
        self::PLAYSTATION_4 => self::PLAYSTATION_4,
        self::PLAYSTATION_5 => self::PLAYSTATION_5,
        self::PC => self::PC,
        self::SWITCH => self::SWITCH,
    ];

    private string $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, self::ALL_PLATFORMS);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public static function getConstants(): array
    {
        return self::ALL_PLATFORMS;
    }

    public function isXboxOne(): bool
    {
        return $this->name === self::XBOX_ONE;
    }

    public function isXboxSeries(): bool
    {
        return $this->name === self::XBOX_SERIES;
    }

    public function isPlaystation4(): bool
    {
        return $this->name === self::PLAYSTATION_4;
    }

    public function isPlaystation5(): bool
    {
        return $this->name === self::PLAYSTATION_5;
    }

    public function isPC(): bool
    {
        return $this->name === self::PC;
    }

    public function isSwitch(): bool
    {
        return $this->name === self::SWITCH;
    }

    public static function getConstantsForRegexp(): string
    {
        return
            self::XBOX_ONE;
    }
}
