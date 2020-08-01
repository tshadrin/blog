<?php

declare(strict_types=1);

namespace App\Entity\GameList;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameList\GameItemRepository")
 * @ORM\Table(name="gamelist")
 */
class GameItem
{
    private const DEFAULT_EXCHANGE_RATE = 1.00;
    public const DEFAULT_DELETED_VALUE = false;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="string", length=300)
     */
    private string $title;
    /**
     * @ORM\Column(type="gamelist_os", name="os", length=30, nullable=false)
     */
    private OS $os;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $purchaseDate;
    /**
     * @ORM\Column(type="float")
     */
    private float $cost;
    /**
     * @ORM\Column(type="float")
     */
    private float $exchangeRate;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $notes;
    /**
     * @ORM\Column(type="gamelist_format", name="format", length=30, nullable=false)
     */
    private Format $format;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $deleted = self::DEFAULT_DELETED_VALUE;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $owned;

    public function getCost(): float
    {
        return $this->cost;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getOs(): OS
    {
        return $this->os;
    }
    public function getPurchaseDate(): \DateTimeImmutable
    {
        return $this->purchaseDate;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getNotes(): ?string
    {
        return $this->notes;
    }
    public function getFormat(): ?Format
    {
        return $this->format;
    }

    public function getExchangeRate(): ?float
    {
        return $this->exchangeRate;
    }

    public function __construct(
        string $title,
        OS $os,
        \DateTimeImmutable $purchaseDate,
        float $cost,
        ?string $notes,
        Format $format,
        float $exchangeRate = self::DEFAULT_EXCHANGE_RATE
    ) {
        $this->title = $title;
        $this->os = $os;
        $this->purchaseDate = $purchaseDate;
        $this->cost = $cost;
        $this->notes = $notes;
        $this->format = $format;
        $this->exchangeRate = $exchangeRate;
    }

    /**
     * @param float $cost
     */
    public function setCost(float $cost): void
    {
        $this->cost = $cost;
    }

    /**
     * @param string $notes
     */
    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

    public function setOs(OS $os): void
    {
        $this->os = $os;
    }

    public function setPurchaseDate(\DateTimeImmutable $purchaseDate): void
    {
        $this->purchaseDate = $purchaseDate;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setFormat(Format $format): void
    {
        $this->format = $format;
    }

    public function setExchangeRate(float $exchangeRate): void
    {
        $this->exchangeRate = $exchangeRate;
    }

    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }

    public function setOwned(?bool $owned): void
    {
        $this->owned = $owned;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function isOwned(): ?bool
    {
        return $this->owned;
    }
}
