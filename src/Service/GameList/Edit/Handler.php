<?php

declare(strict_types=1);

namespace App\Service\GameList\Edit;

use App\Entity\GameList\Format;
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
        dump($command);
        $command->gameItem->setTitle($command->gameItemDTO->title);
        $command->gameItem->setCost($command->gameItemDTO->cost);
        $command->gameItem->setExchangeRate($command->gameItemDTO->exchange_rate);
        $command->gameItem->setNotes($command->gameItemDTO->notes);
        $command->gameItem->setOs(new OS($command->gameItemDTO->os));
        $command->gameItem->setPurchaseDate($command->gameItemDTO->purchase_date);
        $command->gameItem->setFormat(new Format($command->gameItemDTO->format));
        $command->gameItem->setDeleted($command->gameItemDTO->deleted);
        if ($command->gameItemDTO->format === Format::DISC ||
            $command->gameItemDTO->format === Format::DIGITAL ||
            $command->gameItemDTO->format === Format::DLC
        ) {
            $command->gameItem->setOwned($command->gameItemDTO->owned);
        }
        $this->gameItemRepository->save($command->gameItem);
        $this->gameItemRepository->flush();
    }
}
