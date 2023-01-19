<?php

declare(strict_types=1);

namespace App\Repository\Blog;

use App\Entity\Blog\Tag;
use App\Repository\SaveAndFlush;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TagRepository extends ServiceEntityRepository
{
    use SaveAndFlush;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findInById(array $ids): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.id IN (:ids)')
            ->setParameter(':ids', $ids)
            ->getQuery()->getResult();
    }

    public function getTagsFromSelectable(string $selectable): array
    {
        $tagIds = explode('^', $selectable);
        $tags = $this->findInById($tagIds);
        foreach ($tagIds as $id) {
            if (mb_strpos($id, 'new') === 0) {
                [,$tag] = explode('new', $id);
                $tag = new Tag($tag);
                $this->save($tag);
                $tags[] = $tag;
            }
        }

        return $tags;
    }
}
