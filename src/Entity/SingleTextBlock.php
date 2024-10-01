<?php

namespace Smoq\SimsyCMS\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class SingleTextBlock extends Block
{
    protected string $name = 'Single text block';
    protected string $description = 'A block with a single text';
    protected ?string $imageSrc = 'bundles/simsycms/images/blocks/single_text_block.png';

    #[ORM\Column(type: 'text')]
    private string $content;

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}