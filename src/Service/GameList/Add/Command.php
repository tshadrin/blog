<?php

declare(strict_types=1);

namespace App\Service\GameList\Add;

use App\Form\GameList\GameItemDTO;

class Command
{
    public function __construct(
        public GameItemDTO $gameItemDTO
    ) {}
}
