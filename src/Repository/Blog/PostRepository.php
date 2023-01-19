<?php

declare(strict_types=1);

namespace App\Repository\Blog;

use App\Entity\Blog\Post;
use App\Repository\SaveAndFlush;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends ServiceEntityRepository
{
    use SaveAndFlush;

    private SectionRepository $sectionRepository;
    private TagRepository $tagRepository;

    public function __construct(
        ManagerRegistry   $registry,
        SectionRepository $sectionRepository,
        TagRepository     $tagRepository
    ) {
        parent::__construct($registry, Post::class);
        $this->sectionRepository = $sectionRepository;
        $this->tagRepository = $tagRepository;
    }

    public function findBySection(string $section): ?array
    {
        $section = $this->sectionRepository->findOneBy(['machineName' => $section]);
        if (is_null($section)) {
            return null;
        }

        return $this->createQueryBuilder('p')
            ->select("p")
            ->innerJoin('p.tags', 't')
            ->where('p.section = :section')
            ->andWhere('p.status = :status')
            ->setParameter(':section', $section->getId())
            ->setParameter(':status', 'publish')
            ->orderBy("p.created", 'DESC')
            ->getQuery()->getResult();
    }

    public function findByTag(string $tag): ?array
    {
        $tag = $this->tagRepository->findOneBy(['name' => $tag]);
        if (is_null($tag)) {
            return null;
        }
        return $this->createQueryBuilder('p')
            ->select("p")
            ->innerJoin('p.tags', 't')
            ->where('t.id = :tag_id')
            ->andWhere('p.status = :status')
            ->setParameter(':tag_id', $tag->getId())
            ->setParameter(':status', 'publish')
            ->orderBy("p.created", 'DESC')
            ->getQuery()->getResult();
    }
}
