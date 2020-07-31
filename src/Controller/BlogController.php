<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Blog\Post;
use App\Entity\Blog\Section;
use App\Entity\Blog\Status;
use App\Entity\Hru;
use App\Form\Blog\PostDTO;
use App\Form\Blog\PostForm;
use App\Repository\Blog\PostRepository;
use App\Repository\Blog\SectionRepository;
use App\Service\Blog\Post\Add;
use App\Service\Blog\Post\Edit;
use App\Service\Blog\Post\Delete;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/", name="blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route(path="", name="", methods={"GET"})
     */
    public function list(
        PostRepository $postRepository,
        SectionRepository $sectionRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $section = $sectionRepository->findOneBy(['machineName' => 'own']);
        $posts = $postRepository
            ->createQueryBuilder('p')
            ->join('App:Blog\Section', 's', 'WITH', 'p.section = s.id')
            ->where('p.status = :status')
            ->setParameter(':status', Status::PUBLISH)
            ->andWhere('s.hidden = :hidden')
            ->setParameter(':hidden', Section::NOT_HIDDEN)
            ->orderBy('p.created', 'DESC')
            ->getQuery()
            ->getResult();
        $pagedPosts = $paginator->paginate($posts, $request->query->getInt('page', 1));
        return $this->render("blog/blog.html.twig", ['posts' => $pagedPosts]);
    }

    /**
     * @Route("/blog/add", name=".add", methods={"GET","POST"})
     * IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request, Add\Handler $handler): Response
    {
        $form = $this->createForm(PostForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle(new Add\Command($form->getData()));
            $this->addFlash('notice', 'Post saved');
            return $this->redirectToRoute("blog");
        }
        return $this->render("blog/post-add.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/blog/edit/{post}", name=".edit", methods={"GET","POST"})
     * IsGranted("ROLE_ADMIN")
     */
    public function edit(Post $post, Request $request, Edit\Handler $handler): Response
    {
        $postDTO = PostDTO::createFromPost($post);
        $form = $this->createForm(PostForm::class, $postDTO);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle(new Edit\Command($post, $postDTO));
            $this->addFlash('notice', 'Post saved');
            return $this->redirectToRoute("blog.show.from.hru", ['prefix' => $post->getHru()->getPrefix(), 'value' => $post->getHru()->getValue()]);
        }
        return $this->render("blog/post-add.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/blog/{section}", name=".section", methods={"GET"}, requirements={"section":"[a-z]+"})
     */
    public function section(string $section, PaginatorInterface $paginator, PostRepository $postRepository, Request $request): Response
    {
        $pagedPosts = $paginator->paginate($postRepository->findBySection($section), $request->query->getInt('page', 1));
        return $this->render("blog/blog.html.twig", ['posts' => $pagedPosts]);
    }

    /**
     * @Route("/blog/tag/{tag}", name=".tag", methods={"GET"}, requirements={"tag":"^[ёЁA-zА-я0-9 -]+"}, options={"utf8": true})
     */
    public function tag(string $tag, PaginatorInterface $paginator, PostRepository $postRepository, Request $request): Response
    {
        $pagedPosts = $paginator->paginate($postRepository->findByTag($tag), $request->query->getInt('page', 1));
        return $this->render("blog/blog.html.twig", ['posts' => $pagedPosts]);
    }

    /**
     * @Route("/blog/show/{post}", name=".show", methods={"GET"}, requirements={"post": "\d+"})
     */
    public function showPost(Post $post): Response
    {
        if (!is_null($hru = $post->getHru())) {
            return $this->redirectToRoute('blog.show.from.hru', [
                'prefix' => $hru->getPrefix(),
                'value' => $hru->getValue()
            ], Response::HTTP_MOVED_PERMANENTLY);
        }
        if ($post->isPublished() || $this->isGranted("ROLE_ADMIN")) {
            return $this->render('blog/post/show.html.twig', ['post' => $post]);
        }
        $this->addFlash('error', "Post not found.");
        return $this->redirectToRoute('blog');
    }

    /**
     * @Route("/blog/list/table", name=".table", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function table(PostRepository $postRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagedPosts = $paginator->paginate($postRepository->findBy([], ['created' => "DESC"]), $request->query->getInt('page', 1));
        return $this->render("blog/table.html.twig", ['posts' => $pagedPosts]);
    }

    /**
     * @Route("/blog/delete/{post}", name=".delete", methods={"POST"})
     * IsGranted("ROLE_ADMIN")
     */
    public function delete(Post $post, Request $request, Delete\Handler $handler, TranslatorInterface $translator): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            $this->addFlash("error", $translator->trans("Invalid token"));
        } else {
            $handler->handle($post);
            $this->addFlash('notice', 'Post deleted');
        }
        return $this->redirectToRoute("blog");
    }

    /**
     * @Route("/blog/{prefix}/{value}", name=".show.from.hru", methods={"GET"})
     * @Entity("hru", expr="repository.findOneBy({'prefix': prefix, 'value': value})")
     * @Entity("post", expr="repository.findOneBy({'hru': hru})")
     */
    public function showFromHru(Hru $hru, Post $post): Response
    {
        if ($post->isPublished() || $this->isGranted("ROLE_ADMIN")) {
            return $this->render('blog/post/show.html.twig', ['post' => $post]);
        }
        $this->addFlash('error', "Post not found.");
        return $this->redirectToRoute('blog');
    }
}
