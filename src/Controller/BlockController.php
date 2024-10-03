<?php

namespace Smoq\SimsyCMS\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Smoq\SimsyCMS\Entity\Block;
use Smoq\SimsyCMS\Entity\BlockInterface;
use Smoq\SimsyCMS\Entity\Section;
use Smoq\SimsyCMS\Repository\BlockRepository;
use Smoq\SimsyCMS\Service\BlockService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/block', name: 'simsy_cms_block_')]
class BlockController extends AbstractController
{
    #[Route('/{sectionId}/select', name: 'select', methods: ['GET'], requirements: ['sectionId' => '\d+'])]
    public function select(BlockService $blockService, string $sectionId, Request $request): Response
    {
        $blockId = $request->query->getInt('blockId', 0);

        return $this->render('@SimsyCMS/block/_block_select.html.twig', [
            'blocks' => $blockService->getAvailableBlocks(),
            'sectionId' => $sectionId,
            'blockId' => $blockId,
        ]);
    }

    #[Route('/{sectionId}/create/{blockClass}', name: 'create', methods: ['GET', 'POST'], requirements: ['sectionId' => '\d+', 'blockClass' => '[a-zA-Z0-9_\\\]+'])]
    public function create(
        #[MapEntity(mapping: ['sectionId' => 'id'])] Section $section,
        string $blockClass,
        Request $request,
        EntityManagerInterface $em,
        BlockService $blockService,
        BlockRepository $blockRepository
    ): Response
    {
        $blockId = $request->query->getInt('blockId', 0);

        /** @var BlockInterface $block */
        $block = new $blockClass();
        $form = $this->createForm($blockService->getBlockConfiguration($block)['form_class'], $block, ['action' => $this->generateUrl('simsy_cms_block_create', ['sectionId' => $section->getId(), 'blockClass' => $blockClass, 'blockId' => $blockId])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $section->addBlock($block);

            $em->persist($block);
            $em->flush();

            $oldBlock = $blockRepository->find($blockId);

            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            return $this->render('@SimsyCMS/block/_append.html.twig', [
                'oldBlock' => $oldBlock,
                'newBlock' => $block,
                'oldBlockConfig' => $oldBlock ? $blockService->getBlockConfiguration($oldBlock) : null,
                'blockConfig' => $blockService->getBlockConfiguration($block),
            ]);
        }

        return $this->render('@SimsyCMS/block/create.html.twig', [
            'form' => $form,
            'section' => $section,
            'blockId' => $blockId,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['sectionId' => '\d+', 'blockClass' => '[a-zA-Z0-9_\\\]+'])]
    public function edit(
        Block $block,
        Request $request,
        EntityManagerInterface $em,
        BlockService $blockService,
    ): Response
    {
        $form = $this->createForm($blockService->getBlockConfiguration($block)['form_class'], $block, ['action' => $this->generateUrl('simsy_cms_block_edit', ['id' => $block->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            return $this->redirectToRoute('simsy_cms_block_show', ['id' => $block->getId()]);
        }

        return $this->render('@SimsyCMS/block/edit.html.twig', [
            'form' => $form,
            'block' => $block,
        ]);
    }

    #[Route('/{id}/show', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Block $block, BlockService $blockService): Response
    {
        return $this->render('@SimsyCMS/block/_show.html.twig', [
            'block' => $block,
            'blockConfig' => $blockService->getBlockConfiguration($block),
        ]);
    }

    #[Route('/{id}/delete', name: 'delete')]
    public function delete(Block $block, EntityManagerInterface $em, Request $request): Response
    {
        $blockId = $block->getId();
        $sectionId = $block->getSection()->getId();

        $em->remove($block);
        $em->flush();

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('@SimsyCMS/block/_delete.html.twig', [
            'blockId' => $blockId,
            'sectionId' => $sectionId,
        ]);
    }
}