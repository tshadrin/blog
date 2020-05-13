<?php

declare(strict_types=1);

namespace App\Repository;

trait SaveAndFlush
{
    public function save(object $post): void
    {
        $this->getEntityManager()->persist($post);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
