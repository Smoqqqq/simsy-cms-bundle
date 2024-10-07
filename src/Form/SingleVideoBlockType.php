<?php

declare(strict_types=1);

namespace Smoq\SimsyCMS\Form;

use Smoq\SimsyCMS\Entity\SingleVideoBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Valid;

class SingleVideoBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('file', MediaType::class, [
            'label' => false,
            'file_constraints' => [
                new File(
                    maxSize: '16M',
                    mimeTypes: [
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
                        'video/x-matroska', // mkv
                        'video/x-m4v',
                        'video/h264',
                        'video/x-f4v',
                    ],
                ),
            ],
            'attr' => [
                'class' => 'simsy-input',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SingleVideoBlock::class,
        ]);
    }
}
