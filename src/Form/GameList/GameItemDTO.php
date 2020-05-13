<?php

declare(strict_types=1);

namespace App\Form\GameList;

use App\Entity\GameList\GameItem;

class GameItemDTO
{
    public $title;
    public $os;
    public $purchase_date;
    public $cost;
    public $notes;
    public $format;
    public $exchange_rate;
    public static function createFromGameItem(GameItem $gameItem): self
    {
        $gameItemDTO = new self();
        $gameItemDTO->title = $gameItem->getTitle();
        $gameItemDTO->os = $gameItem->getOs();
        $gameItemDTO->purchase_date = $gameItem->getPurchaseDate();
        $gameItemDTO->cost = $gameItem->getCost();
        $gameItemDTO->notes = $gameItem->getNotes();
        $gameItemDTO->format = $gameItem->getFormat();
        $gameItemDTO->exchange_rate = $gameItem->getExchangeRate();
        return $gameItemDTO;
    }
}
