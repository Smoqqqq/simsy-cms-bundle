<?php

namespace Smoq\SimsyCMS\Listener;

use DateTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Exception;
use Psr\Log\LoggerInterface;
use Smoq\SimsyCMS\Entity\Media;
use Smoq\SimsyCMS\Service\ImageCompressionService;
use Smoq\SimsyCMS\Service\VideoCompressionService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsEntityListener(event: Events::preRemove, entity: Media::class)]
#[AsEntityListener(event: Events::prePersist, entity: Media::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Media::class)]
class MediaListener
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ImageCompressionService $imageCompressionService,
        private readonly VideoCompressionService $videoCompressionService,
        private readonly ParameterBagInterface $parameterBag,
    )
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
        $uploadedFile = $media->getFile();

        if (is_file($media->getPath())) {
            unlink($media->getPath());
        }

        $filename = uniqid() .'-'. $uploadedFile->getClientOriginalName();

        // The file needs to be moved after the form is submitted and before the entity is saved
        // Otherwise the form validation will fail as the field must be an instance of UploadedFile
        $file = $uploadedFile->move(Media::FILE_PATH_PREFIX, $filename);

        $compressedFile = false;

        if (str_contains($file->getMimeType(), 'image/') && $this->parameterBag->get('simsy_cms.image_compression')['enabled']) {
            $compressedFile = $this->imageCompressionService->compress($file->getPathname());
        } else if (str_contains($file->getMimeType(), 'video/') && $this->parameterBag->get('simsy_cms.video_compression')['enabled']) {
            try {
                $compressedFile = $this->videoCompressionService->compress($file);
            } catch (Exception $e) {
                $this->logger->error("Error while compressing video #{$media->getId()} : ", ['exception' => $e]);
            }
        }

        if ($compressedFile) {
            if (is_file($file->getPathname())) {
                unlink($file->getPathname());
            }

            $file = $compressedFile;
        }

        $file = $file->move(Media::FILE_PATH_PREFIX, $file->getFilename());

        $media->setFile($file)
            ->setSize($file->getSize())
            ->setFilename($file->getFilename())
            ->setUpdatedAt(new DateTime());
    }
}