<?php
declare(strict_types=1);

namespace App\Entity\Blog;

use Webmozart\Assert\Assert;

class Status
{
    public const DRAFT = 'draft';
    public const REMOVE = 'remove';
    public const PUBLISH = 'publish';
    public const PRIVATE = 'private';

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::DRAFT,
            self::REMOVE,
            self::PUBLISH,
            self::PRIVATE,
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
            self::PUBLISH => self::PUBLISH,
            self::DRAFT => self::DRAFT,
            self::REMOVE => self::REMOVE,
            self::PRIVATE => self::PRIVATE,
        ];
    }

    public function isPublished(): bool
    {
        return $this->getName() === self::PUBLISH;
    }

    public function isDraft(): bool
    {
        return $this->getName() === self::DRAFT;
    }

    public function isRemoved(): bool
    {
        return $this->getName() === self::REMOVE;
    }
    public function isPrivate(): bool
    {
        return $this->getName() == self::PRIVATE;
    }
}