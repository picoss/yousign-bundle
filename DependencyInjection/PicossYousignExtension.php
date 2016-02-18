<?php

namespace Picoss\YousignBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
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

        $container->setParameter('picoss_yousign.environment', $config['environment']);
        $container->setParameter('picoss_yousign.login', $config['login']);
        $container->setParameter('picoss_yousign.password', $config['password']);
        $container->setParameter('picoss_yousign.api_key', $config['api_key']);
        $container->setParameter('picoss_yousign.is_encrypted_password', $config['is_encrypted_password']);

        if ($config['ssl']['enabled'] == true) {
            $definition = $container->getDefinition('picoss_yousign.client_api');
            $definition->addMethodCall('setSSL', array(
                $config['ssl']['enabled'],
                $config['ssl']['cert_client_location'],
                $config['ssl']['ca_chain_client_location'],
                $config['ssl']['private_key_client_location'],
                $config['ssl']['private_key_client_password'],
            ));
        }

        if ($config['proxy']['enabled'] == true) {
            $definition = $container->getDefinition('picoss_yousign.client_api');
            $definition->addMethodCall('setProxy', array(
                $config['proxy']['enabled'],
                $config['proxy']['host'],
                $config['proxy']['port'],
                $config['proxy']['username'],
                $config['proxy']['password'],
            ));
        }
    }
}
