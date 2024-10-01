<?php

namespace Smoq\SimsyCMS\Entity;

interface BlockInterface
{
    public function getId(): int;

    public function getName(): string;

    public function getDescription(): ?string;

    public function getSection(): ?Section;

    public function setSection(?Section $section): static;

    public function getFormTypeClass(): string;

    public function getImageSrc(): ?string;
}