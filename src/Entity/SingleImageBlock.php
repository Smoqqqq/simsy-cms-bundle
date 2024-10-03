<?php

namespace Smoq\SimsyCMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
class SingleImageBlock extends Block
{
    #[ORM\ManyToOne(targetEntity: Media::class, cascade: ['persist', 'remove'])]
    private Media $file;

    public function getFile(): Media
    {
        return $this->file;
    }

    public function setFile(Media $file): self
    {
        $this->file = $file;

        return $this;
    }
}