<?php

namespace Smoq\SimsyCMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicException;

#[ORM\MappedSuperclass]
abstract class Block
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    protected string $name = 'Block missing configuration';
    protected string $description = 'If you see this, that means this block has not been configured properly.';
    protected ?string $imageSrc = null;

    #[ORM\ManyToOne(inversedBy: 'blocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Section $section = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): static
    {
        $this->section = $section;

        return $this;
    }

    public function getFormTypeClass(): string
    {
        $guessedClassName = str_replace('\Entity\\', '\Form\\', static::class) . 'Type';

        if (class_exists($guessedClassName)) {
            return $guessedClassName;
        }

        $shortClassName = (new \ReflectionClass($this))->getShortName();
        throw new LogicException('You must override the getFormTypeClass method of '.static::class.' to return your custom block form class, or create one in "'.$guessedClassName.'Type".');
    }

    public function getImageSrc(): ?string
    {
        return $this->imageSrc;
    }

    protected function getClass(): string
    {
        return static::class;
    }
}