<?php

declare(strict_types=1);

namespace Smoq\SimsyCMS\Form;

use Smoq\SimsyCMS\Entity\SingleImageBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class SingleImageBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', MediaType::class, [
            'label' => false,
            'file_constraints' => [new Image()],
            'attr' => [
                'class' => 'simsy-input',
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SingleImageBlock::class,
        ]);
    }
}
