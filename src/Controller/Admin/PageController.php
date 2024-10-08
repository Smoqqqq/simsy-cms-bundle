<?php

namespace Smoq\SimsyCMS\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Smoq\SimsyCMS\Entity\Page;
use Smoq\SimsyCMS\Form\PageType;
use Smoq\SimsyCMS\Repository\PageRepository;
use Smoq\SimsyCMS\Service\BlockService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/page', name: 'simsy_cms_admin_page_')]
class PageController extends AbstractController
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    #[Route('/', name: 'search', methods: ['GET'])]
    public function search(PageRepository $pageRepository): Response
    {
        return $this->render('@SimsyCMS/admin/page/search.html.twig', [
            'pages' => $pageRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em, BlockService $blockService): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($page);
            $em->flush();

            $this->addFlash('success', $this->translator->trans('simsy_cms.page.created'));
            return $this->redirectToRoute('simsy_cms_admin_page_edit', ['id' => $page->getId()]);
        }

        return $this->render('@SimsyCMS/admin/page/create.html.twig', [
            'form' => $form,
            'blocks' => $blockService->getAvailableBlocks(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Page $page, Request $request, EntityManagerInterface $em, BlockService $blockService): Response
    {
        return $this->render('@SimsyCMS/admin/page/edit.html.twig', [
            'sections' => $page->getSections(),
            'page' => $page,
            'blockService' => $blockService,
        ]);
    }

    #[Route('/{id}/edit/infos', name: 'edit_infos', methods: ['GET', 'POST'])]
    public function editInfos(Page $page, Request $request, EntityManagerInterface $em, BlockService $blockService): Response
    {
        $form = $this->createForm(PageType::class, $page, ['action' => $this->generateUrl('simsy_cms_admin_page_edit_infos', ['id' => $page->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', $this->translator->trans('simsy_cms.page.updated'));
            return $this->redirectToRoute('simsy_cms_admin_page_edit_infos', ['id' => $page->getId(), 'success' => true]);
        }

        return $this->render('@SimsyCMS/admin/page/_edit_config.html.twig', [
            'form' => $form,
            'sections' => $page->getSections(),
            'page' => $page,
            'blockService' => $blockService,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST',   'DELETE'])]
    public function delete(Page $page, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete_page_' . $page->getId(), $request->request->get('_token'))) {
            $em->remove($page);
            $em->flush();

            $this->addFlash('success', $this->translator->trans('simsy_cms.page.deleted'));
        } else {
            $this->addFlash('error', $this->translator->trans('simsy_cms.security.csrf_error'));
        }

        return $this->redirectToRoute('simsy_cms_page_search');
    }
}