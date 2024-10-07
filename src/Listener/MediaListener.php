<?php

namespace Smoq\SimsyCMS\Listener;

use DateTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use Smoq\SimsyCMS\Entity\Media;
use Symfony\Component\HttpFoundation\File\File;

#[AsEntityListener(event: Events::preRemove, entity: Media::class)]
#[AsEntityListener(event: Events::prePersist, entity: Media::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Media::class)]
class MediaListener
{
    public function __construct(private readonly LoggerInterface $logger, private readonly EntityManagerInterface $em)
    {
    }

    public function preRemove(Media $media): void
    {
        try {
            unlink($media->getPath());
        } catch (\Exception $e) {
            $this->logger->error("Error while removing media: {$media->getId()} ". $e->getMessage());
        }
    }

    public function preUpdate(Media $media): void
    {
        $this->saveFile($media);
    }

    public function prePersist(Media $media): void
    {
        $this->saveFile($media);
    }

    private function saveFile(Media $media): void
    {
        $file = $media->getFile();

        if (is_file($media->getPath())) {
            unlink($media->getPath());
        }

        $filename = uniqid() .'-'. $file->getClientOriginalName();

        $media->setSize($file->getSize());
        $media->setFilename($filename);

        $media->setUpdatedAt(new DateTime());
        $media->setFile($file->move(Media::FILE_PATH_PREFIX, $media->getFileName()));
    }
}