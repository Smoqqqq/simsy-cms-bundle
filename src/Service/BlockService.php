<?php

namespace Smoq\SimsyCMS\Service;

use Smoq\SimsyCMS\Entity\BlockInterface;
use Smoq\SimsyCMS\Entity\DualTextBlock;
use Smoq\SimsyCMS\Entity\SingleTextBlock;
use Smoq\SimsyCMS\Form\DualTextBlockType;
use Smoq\SimsyCMS\Form\SingleTextBlockType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BlockService
{
    private const array AVAILABLE_BLOCKS = [
        SingleTextBlock::class => [
            'class' => SingleTextBlock::class,
            'name' => 'Single text block',
            'description' => 'A block with a single text field',
            'image_src' => 'bundles/simsycms/images/blocks/single_text_block.png',
            'template_path' => '@SimsyCMS/block_template/single_text_block.html.twig',
            'form_class' => SingleTextBlockType::class,
        ],
        DualTextBlock::class => [
            'class' => DualTextBlock::class,
            'name' => 'Dual text block',
            'description' => 'A block with two text fields, side by side',
            '' => 'bundles/simsycms/images/blocks/dual_text_block.png',
            'template_path' => '@SimsyCMS/block_template/dual_text_block.html.twig',
            'form_class' => DualTextBlockType::class,
        ],
    ];

    public function __construct(private readonly ParameterBagInterface $parameterBag)
    {
    }

    public function getAvailableBlocks(): array
    {
        $customBlocks = $this->parameterBag->get('simsy_cms.custom_blocks');

        $blockClasses = array_merge(self::AVAILABLE_BLOCKS, $customBlocks);

        $blocks = [];

        foreach ($blockClasses as $block) {
            if (!$block['form_class']) {
                throw new \RuntimeException('Form class not defined for block ' . $block['class']);
            }

            $blocks[$block['class']] = [
                'class' => $block['class'],
                'name' => $block['name'],
                'description' => $block['description'] ?? null,
                'image_src' => $block['image_src'] ?? null,
                'form_class' => $block['form_class'],
                'template_path' => $block['template_path'],
            ];;
        }

        return $blocks;
    }

    public function getBlockConfiguration(BlockInterface $block): array
    {
        $config = $this->getAvailableBlocks()[$block::class] ?? null;

        if ($config) {
            return $config;
        }

        throw new \RuntimeException('Block configuration not found for block ' . $block::class);
    }

    public function getTemplatePath(BlockInterface $block): string
    {
        return $this->getBlockConfiguration($block)['template_path'];
    }
}