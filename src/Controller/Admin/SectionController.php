<?php

declare(strict_types=1);

namespace Smoq\SimsyCMS\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Smoq\SimsyCMS\Entity\Block;
use Smoq\SimsyCMS\Entity\Page;
use Smoq\SimsyCMS\Entity\Section;
use Smoq\SimsyCMS\Form\SectionType;
use Smoq\SimsyCMS\Repository\BlockRepository;
use Smoq\SimsyCMS\Repository\SectionRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'simsy_cms_admin_section_')]
class SectionController extends AbstractController
{
    #[Route('/page/{id}/section/add', name: 'create')]
    public function add(Page $page, Request $request, EntityManagerInterface $em): Response
    {
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section, ['action' => $this->generateUrl('simsy_cms_admin_section_create', ['id' => $page->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page->addSection($section);
            $section->setPosition($page->getSections()->count());

            $em->persist($section);
            $em->flush();

            return $this->render('@SimsyCMS/admin/section/_create.html.twig', [
                'form' => $form,
                'section' => $section,
                'page' => $page,
            ]);
        }

        return $this->render('@SimsyCMS/admin/section/_create.html.twig', [
            'form' => $form,
            'page' => $page,
        ]);
    }

    #[Route('page/{pageId}/section/{id}/edit', name: 'edit')]
    public function edit(
        #[MapEntity(mapping: ['pageId' => 'id'])] Page $page,
        Section $section,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $form = $this->createForm(SectionType::class, $section, ['action' => $this->generateUrl('simsy_cms_admin_section_edit', ['pageId' => $page->getId(), 'id' => $section->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('simsy_cms_admin_section_edit', ['pageId' => $page->getId(), 'id' => $section->getId()]);
        }

        return $this->render('@SimsyCMS/admin/section/_show.html.twig', [
            'form' => $form,
            'section' => $section,
            'page' => $page,
        ]);
    }

    #[Route('page/{pageId}/section/{id}/edit-header', name: 'edit_header')]
    public function editHeader(
        #[MapEntity(mapping: ['pageId' => 'id'])] Page $page,
        Section $section,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $form = $this->createForm(SectionType::class, $section, ['action' => $this->generateUrl('simsy_cms_admin_section_edit_header', ['pageId' => $page->getId(), 'id' => $section->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('simsy_cms_admin_section_edit_header', ['pageId' => $page->getId(), 'id' => $section->getId()]);
        }

        return $this->render('@SimsyCMS/admin/section/_header_form.html.twig', [
            'form' => $form,
            'section' => $section,
            'page' => $page,
        ]);
    }

    #[Route('page/{pageId}/section/{id}/delete', name: 'delete')]
    public function delete(
        #[MapEntity(mapping: ['pageId' => 'id'])] Page $page,
        Section $section,
        EntityManagerInterface $em,
        Request $request
    ): Response
    {
        $sectionId = $section->getId();
        if ($this->isCsrfTokenValid('delete_section_' . $section->getId(), $request->request->get('_token'))) {
            $page->removeSection($section);

            $em->remove($section);
            $em->flush();
        }

        return $this->render('@SimsyCMS/admin/section/_delete_from_stream.html.twig', [
            'sectionId' => $sectionId,
        ]);
    }

    #[Route('page/section/order', name: 'order')]
    public function order(
        Request $request,
        EntityManagerInterface $em,
        BlockRepository $blockRepository,
        SectionRepository $sectionRepository,
        LoggerInterface $logger
    ): Response
    {
        $blocks = $request->getPayload()->all()['blocks'];

        foreach ($blocks as $position => $data) {
            $block = $blockRepository->find($data['id']);
            $section = $sectionRepository->find($data['sectionId']);

            if ($block && $section) {
                $block->setPosition($position)
                    ->setSection($section);
            } else {
                $logger->error('Block or section not found', ['block_id' => $data['id'], 'section_id' => $data['sectionId']]);
            }
        }

        $em->flush();

        return new Response();
    }
}
