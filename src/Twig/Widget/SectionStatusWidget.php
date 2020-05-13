<?php

declare(strict_types=1);

namespace App\Twig\Widget;

use App\Entity\Blog\Section;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SectionStatusWidget extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('section_status', [$this, 'status'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function status(Environment $twig, Section $section): string
    {
        return $twig->render('widget/blog/section/status.html.twig', [
            'section' => $section
        ]);
    }
}
