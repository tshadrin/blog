<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\GameList\Format;
use App\Entity\GameList\GameItem;
use App\Entity\GameList\OS;
use App\Form\GameList\GameItemDTO;
use App\Form\GameList\GameItemForm;
use App\Repository\GameList\GameItemRepository;
use App\Service\GameList\Add;
use App\Service\GameList\Edit;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

/**
 * @Route(name="game_list", path="/game-list")
 */
class GameListController extends AbstractController
{
    /**
     * @Route("/add", name=".add", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request, Add\Handler $handler): Response
    {
        $form = $this->createForm(GameItemForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle(new Add\Command($form->getData()));
            $this->addFlash('notice', 'Tag saved');
            return $this->redirectToRoute("game_list.table");
        }
        return $this->render("gamelist/game-item-add.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{game}", name=".edit", methods={"GET","POST"})
     */
    public function edit(GameItem $game, Request $request, Edit\Handler $handler): Response
    {
        $gameDTO = GameItemDTO::createFromGameItem($game);
        $form = $this->createForm(GameItemForm::class, $gameDTO);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle(new Edit\Command($game, $gameDTO));
            $this->addFlash('notice', 'Game saved');
            return $this->redirectToRoute("game_list.table");
        }
        return $this->render("gamelist/game-item-add.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/table", name=".table", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function table(GameItemRepository $gameItemRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagedGames = $paginator->paginate($gameItemRepository->findAll(), $request->query->getInt('page', 1));
        return $this->render("gamelist/table.html.twig", ['games' => $pagedGames]);
    }

    /**
     * @Route("/table-platform/{platform}", name=".table-platform", methods={"GET"}, defaults={"platform"=GameItemRepository::CONSOLES_PLATFROMS})
     * @IsGranted("ROLE_ADMIN")
     */
    public function gamesTableByPlatform(
        string $platform,
        GameItemRepository $gameItemRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        try {
            $platforms = OS::getConstants();
            array_push($platforms, GameItemRepository::CONSOLES_PLATFROMS);
            Assert::oneOf($platform, $platforms, 'Платформа должна быть одной из: %2$s');
            $pagedGames = $paginator->paginate($gameItemRepository->findByPlatform($platform), $request->query->getInt('page', 1));
            return $this->render("gamelist/table-platform.html.twig", ['games' => $pagedGames]);
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('game_list.table');
        }
    }

    /**
     * @Route("/delete/{gameItem}", name=".delete", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(GameItem $gameItem, Request $request, GameItemRepository $gameItemRepository): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            $this->addFlash('error', "Invalid csrf token");
            return $this->redirectToRoute('game_list.table');
        }
        $gameItemRepository->delete($gameItem);
        return $this->redirectToRoute('game_list.table');
    }

    /**
     * @Route("/statistics", name=".statistics", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function statistics(GameItemRepository $gameItemRepository): Response
    {
        $sum = 0;
        $endDate = (new \DateTimeImmutable())->setTime(0, 0, 0)->modify('first day of next month');
        for (
            $i = 0; $i < 24;
            $i++
        ) {
            $gamelist = $gameItemRepository->findByDateRange($endDate->modify('-1 month'), $endDate);
            $endDate = $endDate->modify('-1 month');
            /** @var GameItem $game */
            foreach ($gamelist as $game) {
                if (
                    $game->getFormat()->getName() === Format::DISC ||
                    $game->getFormat()->getName() === Format::DIGITAL ||
                    $game->getFormat()->getName() === Format::DLC
                ) {
                    echo "{$game->getTitle()} - {$game->getPurchaseDate()->format("d-m-Y")} - {$game->getCost()}<br>";
                    $sum += $game->getCost();
                }
            }
            echo "<div style=\"color: red;\">Всего за {$endDate->modify('-1 month')->format("M-Y")} месяц на игры: {$sum}</div><br>";
            $sum = 0;
        }
        exit;
    }
}
