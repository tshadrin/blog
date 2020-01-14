<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Blog\Section;
use App\Entity\Blog\Tag;
use App\Entity\GameList\GameItem;
use App\Form\GameList\GameItemDTO;
use App\Form\GameList\GameItemForm;
use App\Repository\Blog\TagRepository;
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

/**
 * Class SectionController
 * @package App\Controller
 * @Route(name="game_list", path="/game-list")
 */
class GameListController extends AbstractController
{
    /**
     * @return RedirectResponse
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
     * @return RedirectResponse
     * @Route("/edit/{game}", name=".edit", methods={"GET","POST"})
     */
    public function edit(GameItem $game, Request $request, Edit\Handler $handler): Response
    {
        $gameDTO = GameItemDTO::createFromGameItem($game);
        $form = $this->createForm(GameItemForm::class, $gameDTO);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $handler->handle(new Edit\Command($game, $gameDTO));
            $this->addFlash('notice', 'Game saved');
            return $this->redirectToRoute("game_list.table");
        }
        return $this->render("gamelist/game-item-add.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @return Response
     * @Route("/table", name=".table", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function table(GameItemRepository $gameItemRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagedGames = $paginator->paginate($gameItemRepository->findAll(), $request->query->getInt('page', 1));
        return $this->render("gamelist/table.html.twig", ['games' => $pagedGames]);
    }
}