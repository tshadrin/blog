<?php

declare(strict_types=1);

namespace App\Entity\GameList;

use Webmozart\Assert\Assert;

class OS
{
    public const XBOX_ONE = 'Xbox One';
    public const PLAYSTATION_4 = 'Playstation 4';
    public const PC = 'PC';
    public const SWITCH = 'Switch';
    private $name;
    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::XBOX_ONE,
            self::PLAYSTATION_4,
            self::PC,
            self::SWITCH,
        ]);
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
        return [
            self::XBOX_ONE => self::XBOX_ONE,
            self::PLAYSTATION_4 => self::PLAYSTATION_4,
            self::PC => self::PC,
            self::SWITCH => self::SWITCH,
        ];
    }

    public function isXboxOne(): bool
    {
        return $this->name === self::XBOX_ONE;
    }
    public function isPlaystation4(): bool
    {
        return $this->name === self::PLAYSTATION_4;
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
