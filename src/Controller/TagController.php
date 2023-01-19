<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Blog\Tag;
use App\Form\Tag\TagDTO;
use App\Form\Tag\TagForm;
use App\Repository\Blog\TagRepository;
use App\Service\Blog\Tag\Add;
use App\Service\Blog\Tag\Edit;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route("/tag", name: "tag")]
class TagController extends AbstractController
{
    #[Route("/add", name: ".add", methods: ["GET", "POST"])]
    #[IsGranted("ROLE_ADMIN")]
    public function add(Request $request, Add\Handler $handler): Response
    {
        $form = $this->createForm(TagForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle(new Add\Command($form->getData()));
            $this->addFlash('notice', 'Tag saved');
            return $this->redirectToRoute("tag.table");
        }
        return $this->render("blog/tag/tag-add.html.twig", ['form' => $form->createView()]);
    }

    #[Route("/edit/{tag}", name: ".edit", methods: ["GET", "POST"])]
    public function edit(Tag $tag, Request $request, Edit\Handler $handler): Response
    {
        $tagDTO = TagDTO::createFromTag($tag);
        $form = $this->createForm(TagForm::class, $tagDTO);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle(new Edit\Command($tag, $tagDTO));
            $this->addFlash('notice', 'Tag saved');
            return $this->redirectToRoute("tag.table", ['post' => $tag->getId()]);
        }
        return $this->render("blog/tag/tag-add.html.twig", ['form' => $form->createView()]);
    }

    #[Route("/list/table", name: ".table", methods: ["GET"])]
    #[IsGranted("ROLE_ADMIN")]
    public function table(TagRepository $tagRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagedTags = $paginator->paginate($tagRepository->findAll(), $request->query->getInt('page', 1));
        return $this->render("blog/tag/table.html.twig", ['tags' => $pagedTags]);
    }

    #[Route("/all/json", name: ".json", methods: ["GET"])]
    #[IsGranted("ROLE_USER")]
    public function jsonTags(TagRepository $tagRepository): JsonResponse
    {
        $tags = $tagRepository->findAll();
        return $this->json(['tags' => $tags,]);
    }
}
