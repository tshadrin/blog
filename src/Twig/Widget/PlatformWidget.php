<?php
declare(strict_types=1);

namespace App\Twig\Widget;

use App\Entity\GameList\GameItem;
use App\Entity\GameList\OS;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PlatformWidget extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('game_platform', [$this, 'platform'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function platform(Environment $twig, OS $platform): string
    {
        return $twig->render('widget/game-list/platform.html.twig', [
            'platform' => $platform
        ]);
    }
}