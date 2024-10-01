<?php

namespace Smoq\SimsyCMS\Form;

use Smoq\SimsyCMS\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Page Name',
                'attr' => [
                    'class' => 'simsy-input',
                    'placeholder' => 'Home page',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Page Description',
                'required' => false,
                'attr' => [
                    'class' => 'simsy-input',
                    'placeholder' => 'This is the home page',
                ],
            ])
            ->add('url', TextType::class, [
                'label' => 'Page URL (starting with /)',
                'attr' => [
                    'class' => 'simsy-input',
                    'placeholder' => 'home',
                ],
            ])
            ->add('seoTitle', TextType::class, [
                'label' => 'SEO Title',
                'attr' => [
                    'placeholder' => 'Welcome to my incredible website',
                    'class' => 'simsy-input',
                ],
                'required' => false,
            ])
            ->add('seoDescription', TextType::class, [
                'label' => 'SEO Description',
                'attr' => [
                    'placeholder' => 'Discover the best website in the world, using Simsy CMS',
                    'class' => 'simsy-input'
                ],
                'required' => false,
            ])
            ->add('seoKeywords', TextType::class, [
                'label' => 'SEO Keywords',
                'required' => false,
                'attr' => [
                    'class' => 'simsy-input',
                    'placeholder' => 'website, cms, symfony',
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
