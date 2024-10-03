<?php

namespace Smoq\SimsyCMS\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use ReflectionClass;
use Smoq\SimsyCMS\Entity\Block;
use Smoq\SimsyCMS\Service\BlockService;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class BlockDiscriminatoryMapListener
{
    public function __construct(private readonly BlockService $blockService)
    {
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if ($classMetadata->name === Block::class) {
            $classMetadata->setDiscriminatorMap($this->getDiscriminatoryMap());
        }
    }

    private function getDiscriminatoryMap(): array
    {
        $cache = new FilesystemAdapter();

        return $cache->get('simsy_cms_discriminatory_map', function () {
            $discriminatoryMap = [];
            $blocks = $this->blockService->getAvailableBlocks();

            foreach ($blocks as $block) {
                $reflectionClass = new ReflectionClass($block['class']);

                $classExtendsBlock = is_subclass_of($reflectionClass->getName(), Block::class);

                if ($classExtendsBlock) {
                    $discriminatoryMap[$reflectionClass->getShortName()] = $reflectionClass->getName();
                }
            }

            return $discriminatoryMap;
        });
    }
}