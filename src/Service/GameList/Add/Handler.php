<?php

declare(strict_types=1);

namespace App\Service\GameList\Add;

use App\Entity\GameList\Format;
use App\Entity\GameList\GameItem;
use App\Entity\GameList\OS;
use App\Repository\GameList\GameItemRepository;

class Handler
{
    private GameItemRepository $gameItemRepository;

    public function __construct(GameItemRepository $gameItemRepository)
    {
        $this->gameItemRepository = $gameItemRepository;
    }

    public function handle(Command $command): void
    {
        $gameItem = new GameItem(
            $command->gameItemDTO->title,
            new OS($command->gameItemDTO->os),
            $command->gameItemDTO->purchase_date,
            $command->gameItemDTO->cost,
            $command->gameItemDTO->notes,
            new Format($command->gameItemDTO->format),
            $command->gameItemDTO->exchange_rate
        );
        if ($command->gameItemDTO->format === Format::DISC ||
            $command->gameItemDTO->format === Format::DIGITAL ||
            $command->gameItemDTO->format === Format::DLC
        ) {
            $gameItem->setOwned($command->gameItemDTO->owned);
        }
        $this->gameItemRepository->save($gameItem);
        $this->gameItemRepository->flush();
    }
}
