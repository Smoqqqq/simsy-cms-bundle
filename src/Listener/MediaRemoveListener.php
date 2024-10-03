<?php

namespace Smoq\SimsyCMS\Listener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use Smoq\SimsyCMS\Entity\Media;

#[AsEntityListener(event: Events::preRemove, entity: Media::class)]
class MediaRemoveListener
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function preRemove(Media $media): void
    {
        try {
            unlink($media->getRealPath());
        } catch (\Exception $e) {
            $this->logger->error("Error while removing media: {$media->getId()} ". $e->getMessage());
        }
    }
}