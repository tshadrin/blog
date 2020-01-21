<?php
declare(strict_types=1);

namespace App\Twig\Widget;

use App\Entity\Blog\Section;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SectionHiddenWidget extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('section_hidden', [$this, 'hidden'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function hidden(Environment $twig, Section $section): string
    {
        return $twig->render('widget/blog/section/hidden.html.twig', [
            'section' => $section
        ]);
    }
}