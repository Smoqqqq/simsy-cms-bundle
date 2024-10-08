<?php

declare(strict_types=1);

namespace Smoq\SimsyCMS\Form;

use Smoq\SimsyCMS\Entity\DualImageBlock;
use Smoq\SimsyCMS\Entity\SingleImageBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class DualImageBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('leftImage', MediaType::class, [
            'label' => false,
            'file_constraints' => [new Image()],
            'attr' => [
                'class' => 'simsy-input',
            ]
        ])
            ->add('rightImage', MediaType::class, [
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
            'data_class' => DualImageBlock::class,
        ]);
    }
}
