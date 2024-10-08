<?php

namespace Smoq\SimsyCMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smoq\SimsyCMS\Contracts\BlockInterface;

#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: "discriminatory", type: "string")]
/**
 * Base entity for all blocks.
 * ORM\DiscriminatorMap is dynamically set in the BlockDiscriminatoryMapListener.
 */
abstract class Block implements BlockInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\ManyToOne(inversedBy: 'blocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Section $section = null;

    #[ORM\Column]
    private int $position;

    public function getId(): int
    {
        return $this->id;
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

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    protected function getClass(): string
    {
        return static::class;
    }
}