<?php

declare(strict_types=1);

namespace App\Service\GameList\Add;

use App\Entity\GameList\GameItem;
use App\Repository\GameList\GameItemRepository;

class Handler
{
    /** @var GameItemRepository */
    private $gameItemRepository;
    public function __construct(GameItemRepository $gameItemRepository)
    {
        $this->gameItemRepository = $gameItemRepository;
    }

    public function handle(Command $command): void
    {
        $gameItem = new GameItem(
            $command->gameItemDTO->title,
            $command->gameItemDTO->os,
            $command->gameItemDTO->purchase_date,
            $command->gameItemDTO->cost,
            $command->gameItemDTO->notes,
            $command->gameItemDTO->format,
            $command->gameItemDTO->exchange_rate
        );
        $this->gameItemRepository->save($gameItem);
        $this->gameItemRepository->flush();
    }
}
