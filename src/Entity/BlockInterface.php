<?php

namespace Smoq\SimsyCMS\Entity;

interface BlockInterface
{
    public function getId(): int;

    public function getSection(): ?Section;

    public function setSection(?Section $section): static;
}