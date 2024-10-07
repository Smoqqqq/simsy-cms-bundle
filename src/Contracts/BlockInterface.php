<?php

namespace Smoq\SimsyCMS\Contracts;

use Smoq\SimsyCMS\Entity\Section;

interface BlockInterface
{
    public function getId(): int;

    public function getSection(): ?Section;

    public function setSection(?Section $section): static;
}