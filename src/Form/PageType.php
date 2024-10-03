<?php

namespace Smoq\SimsyCMS\Form;

use Smoq\SimsyCMS\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PageType extends AbstractType
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('simsy_cms.page.name.label'),
                'attr' => [
                    'class' => 'simsy-input',
                    'placeholder' => $this->translator->trans('simsy_cms.page.name.placeholder'),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => $this->translator->trans('simsy_cms.page.description.label'),
                'required' => false,
                'attr' => [
                    'class' => 'simsy-input',
                    'placeholder' => $this->translator->trans('simsy_cms.page.description.placeholder'),
                ],
            ])
            ->add('url', TextType::class, [
                'label' => $this->translator->trans('simsy_cms.page.url.label'),
                'required' => false,
                'attr' => [
                    'class' => 'simsy-input',
                    'placeholder' => $this->translator->trans('simsy_cms.page.url.placeholder'),
                ],
            ])
            ->add('seoTitle', TextType::class, [
                'label' => $this->translator->trans('simsy_cms.page.seo.title.label'),
                'attr' => [
                    'placeholder' => $this->translator->trans('simsy_cms.page.seo.title.placeholder'),
                    'class' => 'simsy-input',
                ],
                'required' => false,
            ])
            ->add('seoDescription', TextType::class, [
                'label' => $this->translator->trans('simsy_cms.page.seo.description.label'),
                'attr' => [
                    'placeholder' => $this->translator->trans('simsy_cms.page.seo.description.placeholder'),
                    'class' => 'simsy-input'
                ],
                'required' => false,
            ])
            ->add('seoKeywords', TextType::class, [
                'label' => $this->translator->trans('simsy_cms.page.seo.keywords.label'),
                'required' => false,
                'attr' => [
                    'class' => 'simsy-input',
                    'placeholder' => $this->translator->trans('simsy_cms.page.seo.keywords.placeholder'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
