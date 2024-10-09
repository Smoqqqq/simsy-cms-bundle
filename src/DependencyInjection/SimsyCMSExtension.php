<?php

declare(strict_types=1);

/*
 * DatabaseDumpBundle
 * developped by Paul Le Flem <contact@paul-le-flem.fr>
 */

namespace Smoq\SimsyCMS\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SimsyCMSExtension extends Extension implements ExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (isset($config['custom_blocks'])) {
            $container->setParameter('simsy_cms', $config['custom_blocks']);
        }
        if (isset($config['video_compression'])) {
            $container->setParameter('simsy_cms.video_compression', $config['video_compression']);
        }
        if (isset($config['image_compression'])) {
            $container->setParameter('simsy_cms.image_compression', $config['image_compression']);
        }
    }
}
