<?php

declare(strict_types=1);

namespace Smoq\SimsyCMS\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Smoq\SimsyCMS\Entity\Page;
use Smoq\SimsyCMS\Entity\Section;
use Smoq\SimsyCMS\Form\SectionType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SectionController extends AbstractController
{
    #[Route('/page/{id}/section/add', name: 'simsy_cms_section_create')]
    public function add(Page $page, Request $request, EntityManagerInterface $em): Response
    {
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section, ['action' => $this->generateUrl('simsy_cms_section_create', ['id' => $page->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page->addSection($section);

            $em->persist($section);
            $em->flush();

            return $this->render('@SimsyCMS/section/_create.html.twig', [
                'form' => $form,
                'section' => $section,
                'page' => $page,
            ]);
        }

        return $this->render('@SimsyCMS/section/_create.html.twig', [
            'form' => $form,
            'page' => $page,
        ]);
    }

    #[Route('page/{pageId}/section/{id}/edit', name: 'simsy_cms_section_edit')]
    public function edit(
        #[MapEntity(mapping: ['pageId' => 'id'])] Page $page,
        Section $section,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $form = $this->createForm(SectionType::class, $section, ['action' => $this->generateUrl('simsy_cms_section_edit', ['pageId' => $page->getId(), 'id' => $section->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('simsy_cms_section_edit', ['pageId' => $page->getId(), 'id' => $section->getId()]);
        }

        return $this->render('@SimsyCMS/section/_show.html.twig', [
            'form' => $form,
            'section' => $section,
            'page' => $page,
        ]);
    }

    #[Route('page/{pageId}/section/{id}/edit-header', name: 'simsy_cms_section_edit_header')]
    public function editHeader(
        #[MapEntity(mapping: ['pageId' => 'id'])] Page $page,
        Section $section,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $form = $this->createForm(SectionType::class, $section, ['action' => $this->generateUrl('simsy_cms_section_edit_header', ['pageId' => $page->getId(), 'id' => $section->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('simsy_cms_section_edit_header', ['pageId' => $page->getId(), 'id' => $section->getId()]);
        }

        return $this->render('@SimsyCMS/section/_header_form.html.twig', [
            'form' => $form,
            'section' => $section,
            'page' => $page,
        ]);
    }

    #[Route('page/{pageId}/section/{id}/delete', name: 'simsy_cms_section_delete')]
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

        return $this->render('@SimsyCMS/section/_delete_from_stream.html.twig', [
            'sectionId' => $sectionId,
        ]);
    }
}
