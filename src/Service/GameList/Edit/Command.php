<?php

declare(strict_types=1);

namespace App\Service\GameList\Edit;

use App\Entity\GameList\GameItem;
use App\Form\GameList\GameItemDTO;

class Command
{
    public function __construct(
        public GameItem $gameItem,
        public GameItemDTO $gameItemDTO
    ) {}
}
