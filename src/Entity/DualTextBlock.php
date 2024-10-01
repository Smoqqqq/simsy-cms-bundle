<?php

namespace Smoq\SimsyCMS\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class DualTextBlock extends Block
{
    #[ORM\Column(type: 'text')]
    private string $leftContent;

    #[ORM\Column(type: 'text')]
    private string $rightContent;

    public function getLeftContent(): string
    {
        return $this->leftContent;
    }

    public function setLeftContent(string $leftContent): self
    {
        $this->leftContent = $leftContent;

        return $this;
    }

    public function getRightContent(): string
    {
        return $this->rightContent;
    }

    public function setRightContent(string $rightContent): self
    {
        $this->rightContent = $rightContent;

        return $this;
    }
}