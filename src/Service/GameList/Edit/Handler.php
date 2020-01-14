<?php
declare(strict_types=1);

namespace App\Service\GameList\Edit;

use App\Entity\GameList\Format;
use App\Entity\GameList\OS;
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
        $command->gameItem->setTitle($command->gameItemDTO->title);
        $command->gameItem->setCost($command->gameItemDTO->cost);
        $command->gameItem->setExchangeRate($command->gameItemDTO->exchange_rate);
        $command->gameItem->setNotes($command->gameItemDTO->notes);
        $command->gameItem->setOs(new OS($command->gameItemDTO->os));
        $command->gameItem->setPurchaseDate($command->gameItemDTO->purchase_date);
        $command->gameItem->setFormat(new Format($command->gameItemDTO->format));
        $this->gameItemRepository->save($command->gameItem);
        $this->gameItemRepository->flush();
    }
}