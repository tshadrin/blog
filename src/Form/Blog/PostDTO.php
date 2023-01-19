<?php

declare(strict_types=1);

namespace App\Form\Blog;

use App\Entity\Blog\Post;
use App\Entity\Blog\Section;
use App\Entity\Blog\Tag;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints;
use Webmozart\Assert\Assert;

class PostDTO
{
    public string $title = '';
    public ?string $teaser = '';
    public string $body = '';
    /** @var Section */
    public $section;
    public string $status = '';
    /**
     * @Constraints\NotBlank()
     */
    public $tags2 = '';

    public static function createFromPost(Post $post)
    {
        $postDTO = new self();
        $postDTO->title = $post->getTitle();
        $postDTO->teaser = $post->getTeaser();
        $postDTO->body = $post->getBody();
        $postDTO->section = $post->getSection();
        $postDTO->status = $post->getStatus()->getName();
        $postDTO->tags2 = $postDTO->convertToSelectize($post->getTags());

        return $postDTO;
    }

    private function convertToSelectize(Collection $tags): string
    {
        Assert::allIsInstanceOf($tags, Tag::class);
        $tags2 = '';
        /** @var Tag $tag */
        foreach ($tags as $tag) {
            $tags2 .= "{$tag->getId()}^";
        }
        $tags2 = mb_substr($tags2, 0, -1);

        return $tags2;
    }
}
