<?php

namespace Smoq\SimsyCMS\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Smoq\SimsyCMS\Entity\BlockInterface;
use Smoq\SimsyCMS\Entity\Section;
use Smoq\SimsyCMS\Service\BlockService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/block', name: 'simsy_cms_block_')]
class BlockController extends AbstractController
{
    #[Route('/{sectionId}/select', name: 'select', methods: ['GET'], requirements: ['sectionId' => '\d+'])]
    public function select(BlockService $blockService, string $sectionId): Response
    {
        return $this->render('@SimsyCMS/block/_block_select.html.twig', [
            'blocks' => $blockService->getAvailableBlocks(),
            'sectionId' => $sectionId,
        ]);
    }

    #[Route('/{sectionId}/create/{blockClass}', name: 'create', methods: ['GET', 'POST'], requirements: ['sectionId' => '\d+', 'blockClass' => '[a-zA-Z0-9_]+'])]
    public function create(
        #[MapEntity(mapping: ['sectionId' => 'id'])] Section $section,
        string $blockClass,
        Request $request,
        EntityManagerInterface $em,
        BlockService $blockService
    ): Response
    {
        /** @var BlockInterface $block */
        $block = new $blockClass();
        $form = $this->createForm($block->getFormTypeClass(), $block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $block->setSection($section);
            $em->persist($block);
            $em->flush();

            return $this->redirectToRoute('simsy_cms_section_edit', ['id' => $section->getId()]);
        }

        return $this->render('@SimsyCMS/block/create.html.twig', [
            'form' => $form,
        ]);
    }
}