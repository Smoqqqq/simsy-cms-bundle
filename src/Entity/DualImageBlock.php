<?php

namespace Smoq\SimsyCMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
class DualImageBlock extends Block
{
    #[ORM\ManyToOne(targetEntity: Media::class, cascade: ['persist', 'remove'])]
    private Media $leftImage;

    #[ORM\ManyToOne(targetEntity: Media::class, cascade: ['persist', 'remove'])]
    private Media $rightImage;

    public function getLeftImage(): Media
    {
        return $this->leftImage;
    }

    public function setLeftImage(Media $leftImage): self
    {
        $this->leftImage = $leftImage;

        return $this;
    }

    public function getRightImage(): Media
    {
        return $this->rightImage;
    }

    public function setRightImage(Media $rightImage): self
    {
        $this->rightImage = $rightImage;

        return $this;
    }
}