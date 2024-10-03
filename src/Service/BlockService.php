<?php

namespace Smoq\SimsyCMS\Service;

use Smoq\SimsyCMS\Entity\BlockInterface;
use Smoq\SimsyCMS\Entity\DualTextBlock;
use Smoq\SimsyCMS\Entity\SingleImageBlock;
use Smoq\SimsyCMS\Entity\SingleTextBlock;
use Smoq\SimsyCMS\Entity\SingleVideoBlock;
use Smoq\SimsyCMS\Form\DualTextBlockType;
use Smoq\SimsyCMS\Form\SingleImageBlockType;
use Smoq\SimsyCMS\Form\SingleTextBlockType;
use Smoq\SimsyCMS\Form\SingleVideoBlockType;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlockService
{
    public function __construct(private readonly ParameterBagInterface $parameterBag, private readonly TranslatorInterface $translator, private readonly TagAwareCacheInterface $cache)
    {
    }

    public function getBlocks(): array
    {
        return [
            SingleTextBlock::class => [
                'class' => SingleTextBlock::class,
                'name' => $this->translator->trans('simsy_cms.block.single_text.name'),
                'description' => $this->translator->trans('simsy_cms.block.single_text.description'),
                'image_src' => 'bundles/simsycms/images/blocks/single_text_block.png',
                'template_path' => '@SimsyCMS/block_template/single_text_block.html.twig',
                'form_class' => SingleTextBlockType::class,
            ],
            DualTextBlock::class => [
                'class' => DualTextBlock::class,
                'name' => $this->translator->trans('simsy_cms.block.dual_text.name'),
                'description' => $this->translator->trans('simsy_cms.block.dual_text.description'),
                'image_src' => 'bundles/simsycms/images/blocks/dual_text_block.png',
                'template_path' => '@SimsyCMS/block_template/dual_text_block.html.twig',
                'form_class' => DualTextBlockType::class,
            ],
            SingleImageBlock::class => [
                'class' => SingleImageBlock::class,
                'name' => $this->translator->trans('simsy_cms.block.single_image.name'),
                'description' => $this->translator->trans('simsy_cms.block.single_image.description'),
                'image_src' => 'bundles/simsycms/images/blocks/single_image_block.png',
                'template_path' => '@SimsyCMS/block_template/single_image_block.html.twig',
                'form_class' => SingleImageBlockType::class,
            ],
            SingleVideoBlock::class => [
                'class' => SingleVideoBlock::class,
                'name' => $this->translator->trans('simsy_cms.block.single_video.name'),
                'description' => $this->translator->trans('simsy_cms.block.single_video.description'),
                'image_src' => 'bundles/simsycms/images/blocks/single_image_block.png',
                'template_path' => '@SimsyCMS/block_template/single_image_block.html.twig',
                'form_class' => SingleVideoBlockType::class,
            ],
        ];
    }

    public function getAvailableBlocks(): array
    {
        return $this->cache->get('simsy_cms.available_blocks', function (ItemInterface $item) {
            $customBlocks = $this->parameterBag->get('simsy_cms.custom_blocks');
            $blockClasses = array_merge($this->getBlocks(), $customBlocks);

            $blocks = [];

            foreach ($blockClasses as $key => $block) {
                $translatedName = $this->translator->trans($block['name']);
                $translatedDescription = $this->translator->trans($block['description']);

                if (!$block['form_class']) {
                    $formClass = str_replace('\\Entity\\', '\\Form\\', $block['class']) . 'Type';

                    if (!class_exists($formClass)) {
                        throw new \RuntimeException("Assumed form class \"{$block['form_class']}\" for block \"{$block['class']}\" does not exist. Create it or configure simsy_cms.{$key}.form_class");
                    }
                } else {
                    if (!class_exists($block['form_class'])) {
                        throw new \RuntimeException("Specified form class \"{$block['form_class']}\" for block \"{$block['class']}\" does not exist");
                    }

                    $formClass = $block['form_class'];
                }

                if (!$formClass) {
                    throw new \RuntimeException("No form class found for block \"{$key}\"");
                }

                $blocks[$block['class']] = [
                    'class' => $block['class'],
                    'name' => $translatedName,
                    'description' => $translatedDescription,
                    'image_src' => $block['image_src'] ?? null,
                    'form_class' => $formClass,
                    'template_path' => $block['template_path'],
                ];;
            }

            return $blocks;
        });
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