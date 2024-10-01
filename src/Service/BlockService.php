<?php

namespace Smoq\SimsyCMS\Service;

use Smoq\SimsyCMS\Entity\DualTextBlock;
use Smoq\SimsyCMS\Entity\SingleTextBlock;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BlockService
{
    private const array AVAILABLE_BLOCKS = [
        SingleTextBlock::class,
        DualTextBlock::class,
    ];

    public function __construct(private readonly ParameterBagInterface $parameterBag)
    {
    }

    public function getAvailableBlocks(): array
    {
        $customBlocks = $this->parameterBag->get('simsy_cms.custom_blocks');

        $blockClasses = array_merge(self::AVAILABLE_BLOCKS, $customBlocks);

        $blocks = [];

        foreach ($blockClasses as $blockClass) {
            $block = new $blockClass();
            $blocks[] = [
                'class' => $blockClass,
                'name' => $block->getName(),
                'description' => $block->getDescription(),
                'imageSrc' => $block->getImageSrc(),
            ];
        }

        return $blocks;
    }
}