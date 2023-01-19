<?php

declare(strict_types=1);

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class AdminMenu
{
    public function __construct(
        private FactoryInterface $factory
    ) {}

    public function build(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('Home', ['route' => 'blog', 'childrenAttributes' => ['class' => 'navbar-nav',]]);
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

        foreach ($menu->getChildren() as $menuItem) {
            if ($menuItem->hasChildren()) {
                $this->reorderMenuItems($menuItem);
            }

            $orderNumber = $menuItem->getExtra('orderNumber');
            if (!is_null($orderNumber)) {
                if (!array_key_exists($orderNumber, $menuOrderArray)) {
                    $menuOrderArray[$orderNumber] = $menuItem->getName();
                } else {
                    $alreadyTaken[$orderNumber] = $menuItem->getName();
                    // $alreadyTaken[] = ['orderNumber' => $orderNumber, 'name' => $menuItem->getName()];
                }
            } else {
                $addLast[] = $menuItem->getName();
            }
        }
        ksort($menuOrderArray);

        // если есть элементы с дублирующимися номерами,
        // то они добавляются согласно номеру
        if (count($alreadyTaken)) {
            foreach ($alreadyTaken as $key => $value) {
                // the ever shifting target
                $keysArray = array_keys($menuOrderArray);
                $position = array_search($key, $keysArray);
                if ($position === false) {
                    continue;
                }

                $menuOrderArray = array_merge(
                    array_slice($menuOrderArray, 0, $position),
                    [$value],
                    array_slice($menuOrderArray, $position)
                );
            }
        }
        ksort($menuOrderArray);

        // в конец добавляются элементы без указания позиции
        if (count($addLast)) {
            foreach ($addLast as $value) {
                $menuOrderArray[] = $value;
            }
        }

        if (count($menuOrderArray)) {
            $menu->reorderChildren($menuOrderArray);
        }
    }
}
