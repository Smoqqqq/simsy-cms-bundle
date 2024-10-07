<?php

namespace Smoq\SimsyCMS\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
class Media
{
    public const string FILE_PATH_PREFIX = 'simsy_cms_user_media/';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    private ?File $file = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\Column(length: 255)]
    private ?int $size = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTime $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File|UploadedFile $file): self
    {
        $this->file = $file;

        // Have to update at least one mapped field to trigger the lifecycle events (preUpdate / prePersist)
        $this->setUpdatedAt(new DateTime());

        return $this;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getPath(): string
    {
        return self::FILE_PATH_PREFIX.$this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}