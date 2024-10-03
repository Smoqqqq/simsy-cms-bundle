<?php

declare(strict_types=1);

namespace Smoq\SimsyCMS\Form;

use Smoq\SimsyCMS\Entity\SingleImageBlock;
use Smoq\SimsyCMS\Entity\SingleVideoBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Contracts\Translation\TranslatorInterface;

class SingleVideoBlockType extends AbstractType
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $authorizedExtensions = [
            'video/mp4',
            'video/x-msvideo',
            'video/mpeg',
            'video/quicktime',
            'video/x-ms-wmv',
            'video/webm',
            'video/ogg',
            'video/3gpp',
            'video/3gpp2',
            'video/x-flv',
            'video/x-matroska',
            'video/x-m4v',
            'video/h264',
            'video/x-f4v',
        ];

        $builder->add('file', MediaType::class, [
            'label' => false,
            'constraints' => [new File(
                extensions: $authorizedExtensions,
            )],
            'attr' => [
                'class' => 'simsy-input',
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SingleVideoBlock::class,
        ]);
    }
}
