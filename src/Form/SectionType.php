<?php

namespace Smoq\SimsyCMS\Form;

use Smoq\SimsyCMS\Entity\Page;
use Smoq\SimsyCMS\Entity\Section;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SectionType extends AbstractType
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('simsy_cms.section.name.label'),
                'attr' => [
                    'class' => 'simsy-input',
                    'placeholder' => $this->translator->trans('simsy_cms.section.name.placeholder'),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => $this->translator->trans('simsy_cms.section.description.label'),
                'required' => false,
                'attr' => [
                    'class' => 'simsy-input',
                    'placeholder' => $this->translator->trans('simsy_cms.section.description.placeholder'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
        ]);
    }
}
