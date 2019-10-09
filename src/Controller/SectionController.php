<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Blog\Section;
use App\Form\Section\SectionDTO;
use App\Form\Section\SectionForm;
use App\Repository\Blog\SectionRepository;
use App\Service\Blog\Section\Add;
use App\Service\Blog\Section\Edit;
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
 * @Route(name="section", path="/section")
 */
class SectionController extends AbstractController
{
    /**
     * @return RedirectResponse
     * @Route("/add", name=".add", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request, Add\Handler $handler): Response
    {
        $form = $this->createForm(SectionForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle(new Add\Command($form->getData()));
            $this->addFlash('notice', 'Secton saved');
            return $this->redirectToRoute("section.table");
        }
        return $this->render("blog/section/section-add.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @return RedirectResponse
     * @Route("/edit/{section}", name=".edit", methods={"GET","POST"})
     */
    public function edit(Section $section, Request $request, Edit\Handler $handler): Response
    {
        $sectionDTO = SectionDTO::createFromSection($section);
        $form = $this->createForm(SectionForm::class, $sectionDTO);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $handler->handle(new Edit\Command($section, $sectionDTO));
            $this->addFlash('notice', 'Section saved');
            return $this->redirectToRoute("section.table", ['post' => $section->getId()]);
        }
        return $this->render("blog/section/section-add.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @return Response
     * @Route("/list/table", name=".table", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function table(SectionRepository $sectionRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagedSections = $paginator->paginate($sectionRepository->findAll(), $request->query->getInt('page', 1));
        return $this->render("blog/section/table.html.twig", ['sections' => $pagedSections]);
    }
}