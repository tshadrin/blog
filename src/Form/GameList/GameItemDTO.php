<?php

declare(strict_types=1);

namespace App\Form\GameList;

use App\Entity\GameList\GameItem;

class GameItemDTO
{
    public string $title = '';
    public string $os = '';
    /** @var \DateTimeImmutable */
    public $purchase_date;
    public float $cost = 0;
    public ?string $notes = null;
    public string $format = '';
    public ?float $exchange_rate = null;
    public ?bool $owned = null;

    public static function createFromGameItem(GameItem $gameItem): self
    {
        $gameItemDTO = new self();
        $gameItemDTO->title = $gameItem->getTitle();
        $gameItemDTO->os = $gameItem->getOs()->getName();
        $gameItemDTO->purchase_date = $gameItem->getPurchaseDate();
        $gameItemDTO->cost = $gameItem->getCost();
        $gameItemDTO->notes = $gameItem->getNotes();
        $gameItemDTO->format = $gameItem->getFormat()->getName();
        $gameItemDTO->exchange_rate = $gameItem->getExchangeRate();
        $gameItemDTO->owned = $gameItem->isOwned();
        return $gameItemDTO;
    }
}
