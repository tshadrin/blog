<?php

declare(strict_types=1);

namespace App\Service\GameList\Add;

use App\Form\GameList\GameItemDTO;

class Command
{
    public $gameItemDTO;
    public function __construct(GameItemDTO $gameItemDTO)
    {
        $this->gameItemDTO = $gameItemDTO;
    }
}
