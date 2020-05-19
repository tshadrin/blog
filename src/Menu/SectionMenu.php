<?php

declare(strict_types=1);

namespace App\Menu;

use App\Entity\Blog\Section;
use App\Repository\Blog\SectionRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SectionMenu
{
    public function build(
        SectionRepository $sectionRepository,
        FactoryInterface $factory,
        AuthorizationCheckerInterface $authorizationChecker
    ): ItemInterface {
        /** @var Section[] $sections */
        $sections = $sectionRepository->findBy(['enabled' => true]);
        $menu = $factory->createItem('Home', ['route' => 'blog', 'childrenAttributes' => ['class' => 'nav',]]);
        $menu->addChild('Home', ['route' => 'blog',])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        foreach ($sections as $section) {
            if (
                $section->isHidden() === Section::NOT_HIDDEN ||
                ($section->isHidden() !== Section::NOT_HIDDEN && $authorizationChecker->isGranted("ROLE_ADMIN"))
            ) {
                $menu->addChild($section->getName(), ['route' => 'blog.section', 'routeParameters' => ['section' => $section->getMachineName()]])
                    ->setAttribute('class', 'nav-item')
                    ->setLinkAttribute('class', 'nav-link')
                    ->setExtra('translation_domain', false);
            }
        }
        return $menu;
    }

    public function reorderMenuItems(ItemInterface $menu): void
    {
        $menuOrderArray = [];
        $addLast = [];
        $alreadyTaken = [];
        foreach ($menu->getChildren() as $key => $menuItem) {
            if ($menuItem->hasChildren()) {
                $this->reorderMenuItems($menuItem);
            }

            $orderNumber = $menuItem->getExtra('orderNumber');
            if ($orderNumber != null) {
                if (!isset($menuOrderArray[$orderNumber])) {
                    $menuOrderArray[$orderNumber] = $menuItem->getName();
                } else {
                    $alreadyTaken[$orderNumber] = $menuItem->getName();
                // $alreadyTaken[] = array('orderNumber' => $orderNumber, 'name' => $menuItem->getName());
                }
            } else {
                $addLast[] = $menuItem->getName();
            }
        }

        // sort them after first pass
        ksort($menuOrderArray);
        // handle position duplicates
        if (count($alreadyTaken)) {
            foreach ($alreadyTaken as $key => $value) {
            // the ever shifting target
                $keysArray = array_keys($menuOrderArray);
                $position = array_search($key, $keysArray);
                if ($position === false) {
                    continue;
                }

                $menuOrderArray = array_merge(array_slice($menuOrderArray, 0, $position), [$value], array_slice($menuOrderArray, $position));
            }
        }

        // sort them after second pass
        ksort($menuOrderArray);
        // add items without ordernumber to the end
        if (count($addLast)) {
            foreach ($addLast as $key => $value) {
                $menuOrderArray[] = $value;
            }
        }

        if (count($menuOrderArray)) {
            $menu->reorderChildren($menuOrderArray);
        }
    }
}
