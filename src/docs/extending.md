## Creating a custom block
1. Create a new entity that extends `Smoq\SimsyCMS\Entity\Block`

```php
<?php

namespace App\Entity;

use App\Form\CustomBlockType;
use Doctrine\ORM\Mapping as ORM;
use Smoq\SimsyCMS\Entity\Block;

class CustomBlock extends Block
{
    protected string $name = 'My custom block';
    protected string $description = 'A description to help the user understand what this block does';
    
    // Optionnally define a path to an image that will be displayed in the block list (will be displayed using twig's asset() function)
    protected ?string $imageSrc = 'build/images/custom-block.png';

    #[ORM\Column(length: 255)]
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
```

2. Create a new form type

```php
<?php

namespace App\Form;

use App\Entity\CustomBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, [
                'label' => 'Block content',
                'attr' => [
                    'class' => 'simsy-input'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomBlock::class,
        ]);
    }
}

```

3. Define the form type class in your entity

```php
class CustomBlock extends Block
{
    // The rest of your entity

    public function getFormTypeClass(): string
    {
        return CustomBlockType::class;
    }
}
```

4. Register your custom block in the configuration file

```yaml
# config/packages/simsy_cms.yaml
simsy_cms:
    custom_blocks:
        - 'App\Entity\MyCustomBlock'
```