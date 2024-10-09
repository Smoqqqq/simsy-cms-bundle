<?php

namespace Smoq\SimsyCMS\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\File;

class ImageCompressionService
{
    public function __construct(private readonly ParameterBagInterface $parameterBag)
    {
    }

    /**
     * compresses an image and converts it to webp.
     *
     * @param string $path path of the image
     *
     * @return File|false new (.webp) file on success, false on failure
     */
    public function compress(string $path): File|false
    {
        $newPath = preg_replace("/\.[0-9a-z]+$/", '--compressed.webp', $path);

        if (!$newPath) {
            throw new \UnexpectedValueException('Invalid image path');
        }

        $imageContent = file_get_contents($path);

        // File doesn't exist ?
        if (!$imageContent) {
            throw new \UnexpectedValueException('Cannot get image content');
        }

        $gdImage = imagecreatefromstring($imageContent);

        // Unrecognised file format or corrupted image
        if (!$gdImage) {
            throw new \UnexpectedValueException('Cannot create GD image');
        }

        if (!imagepalettetotruecolor($gdImage)) {
            throw new \UnexpectedValueException('Cannot convert image to true color');
        }

        $quality = $this->parameterBag->get('simsy_cms.image_compression')['quality'] ?? 80;

        if (!imagewebp($gdImage, $newPath, $quality)) {
            throw new \UnexpectedValueException('Cannot save image');
        }

        return new File($newPath);
    }
}
