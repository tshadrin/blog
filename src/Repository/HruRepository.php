<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Hru;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class HruRepository extends ServiceEntityRepository
{
    use SaveAndFlush;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hru::class);
    }

    public function isExists(string $prefix, string $value): bool
    {
        if(is_null($this->findOneBy(['prefix' => $prefix, 'value' => $value]))) {
            return false;
        }
        return true;
    }
}