<?php

/*
 * This file is part of the YesWeHack BugBounty backend
 *
 * (c) Romain Honel <romain.honel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Picoss\YousignBundle\DependencyInjection;

use Picoss\YousignBundle\Yousign\YousignApi;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Yousign\Authentication;

/**
 * Class PicossYousignExtension
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class PicossYousignExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('picoss_yousign.env', $config['env']);
        $container->setParameter('picoss_yousign.api_key', $config['api_key']);
        $container->setParameter('picoss_yousign.username', $config['username']);
        $container->setParameter('picoss_yousign.password', $config['password']);
        $container->setParameter('picoss_yousign.soap_options', $config['soap_options']);

        $container->registerForAutoconfiguration(YousignApi::class);
    }
}
