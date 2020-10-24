<?php

declare(strict_types=1);

namespace App\Repository\GameList;

use App\Entity\GameList\Format;
use App\Entity\GameList\GameItem;
use App\Entity\GameList\OS;
use App\Repository\SaveAndFlush;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GameItemRepository extends ServiceEntityRepository
{
    use SaveAndFlush;

    public const CONSOLES_PLATFROMS = 'Consoles';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameItem::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('g')
            ->select("g")
            ->orderBy('g.purchaseDate', "desc")
            ->getQuery()->getResult();
    }
    public function findByPlatform(string $platform)
    {
        $query = $this->createQueryBuilder('g')
            ->select("g")
            ->where('g.format in(:digital,:disc)')
            ->setParameter(':digital', Format::DIGITAL)
            ->setParameter(':disc', Format::DISC)
            ->andWhere('g.deleted = :deleted')
            ->setParameter(':deleted', GameItem::DEFAULT_DELETED_VALUE);
        if ($platform !== self::CONSOLES_PLATFROMS) {
            $query->andWhere('g.os = :platform')
                ->setParameter(':platform', $platform);
        }
        if ($platform === self::CONSOLES_PLATFROMS) {
            $query->andWhere('g.os IN(:xbox, :ps4)')
                ->setParameter(':xbox', OS::XBOX_ONE)
                ->setParameter(':ps4', OS::PLAYSTATION_4);
        }
        return $query->orderBy('g.title', "asc")
            ->getQuery()->getResult();
    }

    public function delete(GameItem $gameItem): void
    {
        $gameItem->setDeleted(true);
        $this->save($gameItem);
        $this->flush();
    }

    public function findByDateRange(\DateTimeImmutable $dateFrom, \DateTimeImmutable $dateTo)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.purchaseDate >= :dateFrom')
            ->andWhere('g.purchaseDate <= :dateTo')->setParameter(':dateFrom', $dateFrom)
            ->setParameter(':dateTo', $dateTo)->orderBy("g.purchaseDate", 'asc')->getQuery()->getResult();
    }

    public function getAllNotDeleted(): array
    {
        if (!$games = $this->findBy(['deleted' => false,])) {
            throw new \DomainException('Games not found');
        }
        return $games;
    }
}
