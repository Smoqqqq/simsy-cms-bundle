<?php

namespace Smoq\SimsyCMS\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Smoq\SimsyCMS\Contracts\BlockInterface;
use Smoq\SimsyCMS\Entity\Block;
use Smoq\SimsyCMS\Entity\Section;
use Smoq\SimsyCMS\Repository\BlockRepository;
use Smoq\SimsyCMS\Service\BlockService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/block', name: 'simsy_cms_admin_block_')]
class BlockController extends AbstractController
{
    #[Route('/{sectionId}/select', name: 'select', methods: ['GET'], requirements: ['sectionId' => '\d+'])]
    public function select(BlockService $blockService, string $sectionId, Request $request): Response
    {
        $nextBlockId = $request->query->getInt('nextBlockId', 0);

        return $this->render('@SimsyCMS/admin/block/_block_select.html.twig', [
            'blocks' => $blockService->getAvailableBlocks(),
            'sectionId' => $sectionId,
            'nextBlockId' => $nextBlockId,
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
        $nextBlockId = $request->query->getInt('nextBlockId', 0);
        $nextBlock = $blockRepository->find($nextBlockId);

        /** @var BlockInterface $block */
        $block = new $blockClass();

        $blockConfig = $blockService->getBlockConfiguration($block);

        $block->setPosition($nextBlock ? $nextBlock->getPosition() : 0);
        $form = $this->createForm($blockConfig['form_class'], $block, ['action' => $this->generateUrl('simsy_cms_admin_block_create', ['sectionId' => $section->getId(), 'blockClass' => $blockClass, 'nextBlockId' => $nextBlockId])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $section->addBlock($block);

            foreach ($blockRepository->findFollowingBlocks($block) as $followingBlock) {
                $followingBlock->setPosition($followingBlock->getPosition() + 1);
            }

            $em->persist($block);
            $em->flush();

            $oldBlock = $blockRepository->find($nextBlockId);

            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            return $this->render('@SimsyCMS/admin/block/_append.html.twig', [
                'oldBlock' => $oldBlock,
                'newBlock' => $block,
                'oldBlockConfig' => $oldBlock ? $blockService->getBlockConfiguration($oldBlock) : null,
                'blockConfig' => $blockConfig,
            ]);
        }

        return $this->render('@SimsyCMS/admin/block/create.html.twig', [
            'form' => $form,
            'section' => $section,
            'nextBlockId' => $nextBlockId,
            'blockConfig' => $blockConfig,
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
        $blockConfig = $blockService->getBlockConfiguration($block);
        $form = $this->createForm($blockConfig['form_class'], $block, ['action' => $this->generateUrl('simsy_cms_admin_block_edit', ['id' => $block->getId()]), 'attr' => ['class' => 'block-form']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

            return $this->redirectToRoute('simsy_cms_admin_block_show', ['id' => $block->getId()]);
        }

        return $this->render('@SimsyCMS/admin/block/edit.html.twig', [
            'form' => $form,
            'block' => $block,
            'blockConfig' => $blockConfig,
        ]);
    }

    #[Route('/{id}/show', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Block $block, BlockService $blockService): Response
    {
        return $this->render('@SimsyCMS/admin/block/_show.html.twig', [
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

        return $this->render('@SimsyCMS/admin/block/_delete.html.twig', [
            'blockId' => $blockId,
            'sectionId' => $sectionId,
        ]);
    }
}