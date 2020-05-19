<?php

declare(strict_types=1);

namespace App\Service\GameList\Edit;

use App\Entity\GameList\GameItem;
use App\Form\GameList\GameItemDTO;

class Command
{
    public GameItem $gameItem;
    public GameItemDTO $gameItemDTO;

    public function __construct(GameItem $gameItem, GameItemDTO $gameItemDTO)
    {
        $this->gameItem = $gameItem;
        $this->gameItemDTO = $gameItemDTO;
    }
}
