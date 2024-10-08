<?php

declare(strict_types=1);

namespace Smoq\SimsyCMS\Form;

use Smoq\SimsyCMS\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Contracts\Translation\TranslatorInterface;

class MediaType extends AbstractType
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('file', FileType::class, [
            'attr' => [
                'placeholder' => $this->translator->trans('simsy_cms.file'),
            ],
            'constraints' => $options['file_constraints'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
            'file_constraints' => [],
        ]);
    }
}
