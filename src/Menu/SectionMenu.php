<?php

declare(strict_types=1);

namespace App\Menu;

use App\Entity\Blog\Section;
use App\Repository\Blog\SectionRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Security;

class SectionMenu
{
    /** @var SectionRepository */
    private SectionRepository $sectionRepository;
    /** @var FactoryInterface */
    private FactoryInterface $factory;
    /** @var Security */
    private Security $security;

    public function __construct(
        SectionRepository $sectionRepository,
        FactoryInterface $factory,
        Security $security
    ) {

        $this->sectionRepository = $sectionRepository;
        $this->factory = $factory;
        $this->security = $security;
    }

    public function build(array $options): ItemInterface {
        /** @var Section[] $sections */
        $sections = $this->sectionRepository->findBy(['enabled' => true]);
        $menu = $this->factory->createItem('Home', ['route' => 'blog', 'childrenAttributes' => ['class' => 'nav',]]);
        $menu->addChild('Home', ['route' => 'blog',])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        foreach ($sections as $section) {
            if (
                $section->isHidden() === Section::NOT_HIDDEN ||
                ($section->isHidden() !== Section::NOT_HIDDEN && $this->security->isGranted("ROLE_ADMIN"))
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
