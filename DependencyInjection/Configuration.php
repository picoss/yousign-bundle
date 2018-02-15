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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Yousign\Environment;

/**
 * Class Configuration
 *
 * @author Romain Honel <romain.honel@gmail.com>
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
                ->enumNode('env')->values(array(Environment::DEMO, Environment::PROD))->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('username')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('password')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('api_key')->cannotBeEmpty()->isRequired()->end()
                ->arrayNode('soap_options')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
