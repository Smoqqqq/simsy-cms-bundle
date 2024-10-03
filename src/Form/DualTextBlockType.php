<?php

namespace Smoq\SimsyCMS\Form;

use Smoq\SimsyCMS\Entity\DualTextBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DualTextBlockType extends AbstractType
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('leftContent', TextType::class, [
                'label' => $this->translator->trans('simsy_cms.block.left_content'),
                'attr' => [
                    'class' => 'simsy-input',
                ],
            ])
            ->add('rightContent', TextType::class, [
                'label' => $this->translator->trans('simsy_cms.block.right_content'),
                'attr' => [
                    'class' => 'simsy-input',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DualTextBlock::class,
        ]);
    }
}
