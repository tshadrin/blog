<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Blog\Post;
use App\Entity\Blog\Status;
use App\Form\Blog\PostDTO;
use App\Form\Blog\PostForm;
use App\Repository\Blog\PostRepository;
use App\Repository\UserRepository;
use App\Service\Blog\Post\Add;
use App\Service\Blog\Post\Edit;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\SodiumPasswordEncoder;
use Symfony\Component\Security\Core\Tests\Encoder\PlaintextPasswordEncoderTest;

/**
 * Class BlogController
 * @Route("/blog", name="blog")
 */
class BlogController extends AbstractController
{
    /**
     * Last blog records
     * @return Response
     * @Route(path="", name="", methods={"GET"})
     */
    public function list(PostRepository $postRepository, PaginatorInterface $paginator, Request $request, Session $session, UserRepository $userRepository): Response
    {
        $pagedPosts = $paginator->paginate($postRepository->findBy(['status' => Status::PUBLISH],['created' => 'DESC']), $request->query->getInt('page', 1));
        return $this->render("blog/blog.html.twig", ['posts' => $pagedPosts]);
    }

    /**
     * @return Response
     * @Route("/add", name=".add", methods={"GET","POST"})
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
     * @return Response
     * @Route("/edit/{post}", name=".edit", methods={"GET","POST"})
     * IsGranted("ROLE_ADMIN")
     */
    public function edit(Post $post, Request $request, Edit\Handler $handler): Response
    {
        $postDTO = PostDTO::createFromPost($post);
        $form = $this->createForm(PostForm::class, $postDTO);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $handler->handle(new Edit\Command($post, $postDTO));
            $this->addFlash('notice', 'Post saved');
            return $this->redirectToRoute("blog.show", ['post' => $post->getId()]);
        }
        return $this->render("blog/post-add.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @param string $section
     * @return Response
     * @Route("/{section}", name=".section", methods={"GET"}, requirements={"section":"[a-z]+"})
     */
    public function section(string $section, PaginatorInterface $paginator, PostRepository $postRepository, Request $request): Response
    {
        $pagedPosts = $paginator->paginate($postRepository->findBySection($section), $request->query->getInt('page', 1));
        return $this->render("blog/blog.html.twig", ['posts' => $pagedPosts]);
    }
    /**
     * @param string $tag
     * @return Response
     * @Route("/tag/{tag}", name=".tag", methods={"GET"}, requirements={"tag":"^[ёЁA-zА-я0-9 -]+"})
     */
    public function tag(string $tag, PaginatorInterface $paginator, PostRepository $postRepository, Request $request): Response
    {
        $pagedPosts = $paginator->paginate($postRepository->findByTag($tag), $request->query->getInt('page', 1));
        return $this->render("blog/blog.html.twig", ['posts' => $pagedPosts]);
    }

    /**
     * @param Post $post
     * @return Response
     * @Route("/show/{post}", name=".show", methods={"GET"}, requirements={"post": "\d+"})
     */
    public function showPost(Post $post): Response
    {
        if($post->isPublished() || $this->isGranted("ROLE_ADMIN")) {
            return $this->render('blog/post/show.html.twig', ['post' => $post]);
        }
        $this->addFlash('error', "Post not found.");
        return $this->redirectToRoute('blog');

    }

    /**
     * @param Post $post
     * @return Response
     * @Route("/list/table", name=".table", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function table(PostRepository $postRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagedPosts = $paginator->paginate($postRepository->findBy([], ['created' => "DESC"]), $request->query->getInt('page', 1));
        return $this->render("blog/table.html.twig", ['posts' => $pagedPosts]);
    }
}
