<?php
declare(strict_types=1);

namespace App\Entity\GameList;

use Webmozart\Assert\Assert;

class Format
{
    public const DIGITAL = 'digital';
    public const DISC = 'disc';
    public const SUBSCRIPTION = 'subscription';
    public const OTHER = 'other';
    public const ACCESSORY = 'accessory';
    public const DLC = 'dlc';

    private $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::DIGITAL,
            self::DISC,
            self::SUBSCRIPTION,
            self::OTHER,
            self::ACCESSORY,
            self::DLC,
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
            self::DIGITAL => self::DIGITAL,
            self::DISC => self::DISC,
            self::SUBSCRIPTION => self::SUBSCRIPTION,
            self::OTHER => self::OTHER,
            self::ACCESSORY => self::ACCESSORY,
            self::DLC => self::DLC,
        ];
    }

    public function isDigital(): bool
    {
        return $this->name === self::DIGITAL;
    }

    public function isDisc(): bool
    {
        return $this->name === self::DISC;
    }
    public function isSubscription(): bool
    {
        return $this->name === self::SUBSCRIPTION;
    }
    public function isOther(): bool
    {
        return $this->name === self::OTHER;
    }
    public function isAccessory(): bool
    {
        return $this->name === self::ACCESSORY;
    }
    public function isDlc(): bool
    {
        return $this->name === self::DLC;
    }
}