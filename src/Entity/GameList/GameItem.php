<?php

declare(strict_types=1);

namespace App\Entity\GameList;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Post
 * @ORM\Entity(repositoryClass="App\Repository\GameList\GameItemRepository")
 * @ORM\Table(name="gamelist")
 */
class GameItem
{
    private const DEFAULT_EXCHANGE_RATE = 1.00;
    public const DEFAULT_DELETED_VALUE = false;
/**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
/**
     * @var string
     * @ORM\Column(type="string", length=300)
     */
    private $title;
/**
     * @var OS
     * @ORM\Column(type="gamelist_os", name="os", length=30, nullable=false)
     */
    private $os;
/**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $purchaseDate;
/**
     * @var float
     * @ORM\Column(type="float")
     */
    private $cost;
/**
     * @var float
     * @ORM\Column(type="float")
     */
    private $exchangeRate;
/**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;
/**
     * @var Format
     * @ORM\Column(type="gamelist_format", name="format", length=30, nullable=false)
     */
    private $format;
/**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $deleted;
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
        string $os,
        \DateTimeImmutable $purchaseDate,
        float $cost,
        ?string $notes,
        string $format,
        float $exchangeRate = self::DEFAULT_EXCHANGE_RATE,
        bool $deleted = self::DEFAULT_DELETED_VALUE
    ) {
        $this->title = $title;
        $this->os = $os;
        $this->purchaseDate = $purchaseDate;
        $this->cost = $cost;
        $this->notes = $notes;
        $this->format = $format;
        $this->exchangeRate = $exchangeRate;
        $this->deleted = $deleted;
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

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }
}
