<?php

namespace Smoq\SimsyCMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class SingleVideoBlock extends Block
{
    #[ORM\ManyToOne(targetEntity: Media::class, cascade: ['persist', 'remove'])]
    private Media $file;

    #[ORM\Column]
    private bool $autoplay = false;

    #[ORM\Column(name: '`loop`')]
    private bool $loop = false;

    #[ORM\Column]
    private bool $controls = false;

    #[ORM\Column]
    private bool $muted = false;

    public function getFile(): Media
    {
        return $this->file;
    }

    public function setFile(Media $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function autoplay(): bool
    {
        return $this->autoplay;
    }

    public function setAutoplay(bool $autoplay): self
    {
        $this->autoplay = $autoplay;

        return $this;
    }

    public function loop(): bool
    {
        return $this->loop;
    }

    public function setLoop(bool $loop): self
    {
        $this->loop = $loop;

        return $this;
    }

    public function controls(): bool
    {
        return $this->controls;
    }

    public function setControls(bool $controls): self
    {
        $this->controls = $controls;

        return $this;
    }

    public function muted(): bool
    {
        return $this->muted;
    }

    public function setMuted(bool $muted): self
    {
        $this->muted = $muted;

        return $this;
    }

    public function getVideoAttributes(): array
    {
        return [
            'autoplay' => $this->autoplay,
            'loop' => $this->loop,
            'controls' => $this->controls,
            'muted' => $this->muted,
        ];
    }
}