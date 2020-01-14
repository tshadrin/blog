<?php
declare(strict_types=1);

namespace App\Repository\GameList;

use App\Entity\GameList\GameItem;
use App\Repository\SaveAndFlush;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GameItemRepository extends ServiceEntityRepository
{
    use SaveAndFlush;

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
}