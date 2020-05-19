<?php

declare(strict_types=1);

namespace App\Menu;

use App\Repository\Blog\SectionRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class AdminMenu
{
    public function build(
        SectionRepository $sectionRepository,
        FactoryInterface $factory
    ): ItemInterface {
        $menu = $factory->createItem('Home', ['route' => 'blog', 'childrenAttributes' => ['class' => 'navbar-nav',]]);
        $menu->addChild('Sections', ['route' => 'section.table'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        $menu->addChild('Tags', ['route' => 'tag.table'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        $menu->addChild('Posts', ['route' => 'blog.table'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        $menu->addChild('Games', ['route' => 'game_list.table'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        $menu['Posts']->addChild('Post add', ['route' => 'blog.add'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
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
