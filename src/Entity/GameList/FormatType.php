<?php
declare(strict_types=1);

namespace App\Entity\GameList;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class FormatType extends StringType
{
    public const NAME = 'gamelist_format';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Format ? $value->getName() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Format($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
}