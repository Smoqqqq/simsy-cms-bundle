<?php

namespace Smoq\SimsyCMS\Entity;

use Doctrine\ORM\Event\PreRemoveEventArgs;
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
    private string $path;

    #[ORM\Column(length: 255)]
    private int $size;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $updatedAt;

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
        if ($this->getFile() instanceof File) {
            unlink($this->path);
        }

        $this->file = $file;
        $this->updatedAt = new \DateTime();

        $filename = uniqid() .'-'. $file->getClientOriginalName();
        $path = self::FILE_PATH_PREFIX . $filename;

        $this->setSize($file->getSize());
        $file->move(self::FILE_PATH_PREFIX, $filename);
        $this->setPath($path);

        return $this;
    }

    public function getPath(): string
    {
        return '/'.$this->path;
    }

    public function getRealPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

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

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}