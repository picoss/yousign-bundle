<?php

namespace Picoss\YousignBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('picoss_yousign');

        $rootNode
            ->children()
                ->enumNode('environment')->values(array('demo', 'prod'))->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('login')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('password')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('api_key')->cannotBeEmpty()->isRequired()->end()
                ->booleanNode('is_encrypted_password')->defaultFalse()->end()
                ->arrayNode('ssl')
                    ->canBeEnabled()->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->scalarNode('cert_client_location')->cannotBeEmpty()->isRequired()->end()
                        ->scalarNode('ca_chain_client_location')->cannotBeEmpty()->isRequired()->end()
                        ->scalarNode('private_key_client_location')->cannotBeEmpty()->isRequired()->end()
                        ->scalarNode('private_key_client_password')->cannotBeEmpty()->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('proxy')
                    ->canBeEnabled()->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->scalarNode('host')->cannotBeEmpty()->isRequired()->end()
                        ->scalarNode('port')->cannotBeEmpty()->isRequired()->end()
                        ->scalarNode('username')->defaultNull()->end()
                        ->scalarNode('password')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
