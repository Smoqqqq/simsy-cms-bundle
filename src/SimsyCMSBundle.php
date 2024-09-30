<?php

declare(strict_types=1);

/*
 * DatabaseDumpBundle
 * developped by Paul Le Flem <contact@paul-le-flem.fr>
 */

namespace Smoq\SimsyCMS;

use Smoq\SimsyCMS\DependencyInjection\SimsyCMSExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SimsyCMSBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        new SimsyCMSExtension();
    }

    public function getContainerExtension(): ExtensionInterface
    {
        return new SimsyCMSExtension();
    }
}
