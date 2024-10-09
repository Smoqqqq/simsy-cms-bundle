# Extending SimsyCMS
Simsy CMS has been designed to be easily extendable. You can create custom blocks by following the steps below.

## Creating a custom block
### 1. Create a new entity that extends `Smoq\SimsyCMS\Entity\Block`

```php
<?php

namespace App\Entity;

use App\Form\CustomBlockType;
use Doctrine\ORM\Mapping as ORM;
use Smoq\SimsyCMS\Entity\Block;

#[ORM\Entity]
class CustomBlock extends Block
{
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

### 2. Create a new form class

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

### 3. Create a new template  
In your template, you are provided with the `block` variable, which is an instance of your custom block entity.

```twig
{# templates/block_template/custom_block.html.twig #}
<div class="custom-block">
    {{ block.content }}
</div>
```

### 4. Register your custom block in the configuration file  

```yaml
# config/packages/simsy_cms.yaml
simsy_cms:
    custom_blocks:
        my_custom_block:
            class: 'App\Entity\CustomBlock'                         # The class of your custom block
            name: 'Custom Block'                                    # The name of your custom block. can be a translation key
            description: 'Custom block description'                 # [optional] The description of your custom block. can be a translation key
            template_path: 'block_template/custom_block.html.twig'  # The path to the template of your custom block
            form_class: 'App\Form\CustomBlockType'                  # [optional] The form class of your custom block. If ommited, App\Form\{BlockName}Type will be assumed
            img_src: 'build/images/custom_block.png'                # [optional] The path to the image of your custom block (as in used with the asset twig function)
```

### 5. Tada  
That's it! Your block automatically appears in the block list in the admin panel, and you can now freely use it in your pages.

## Troubleshooting
If you encounter an error related to the Block's discriminatory map, try clearing cache, as it is likely that the discriminator map is not up to date.

```bash
php bin/console cache:clear
```

## Uploading files in custom blocks
File upload already is managed by Simsy. Get automatic upload as well as image and video compression by using the `Smoq\SimsyCMS\Entity\Media` entity and the `Smoq\SimsyCMS\Form\MediaType` form type. Files will be uploaded to the `public/simsy_cms_user_media/` directory.