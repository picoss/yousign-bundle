<?php
/*
 * This file is part of the YesWeHack BugBounty backend
 *
 * (c) Romain Honel <romain.honel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Picoss\YousignBundle\Tests\DependencyInjection;

use Picoss\YousignBundle\DependencyInjection\PicossYousignExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yousign\Authentication;

/**
 * Class PicossYousignExtensionTest
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class PicossYousignExtensionTest extends TestCase
{
    public function testLoadConfig()
    {
        $extension = new PicossYousignExtension();
        $container = new ContainerBuilder();

        $config = [
            'env' => 'demo',
            'username' => 'login',
            'password' => 'pa$$word',
            'api_key' => '123abc',
            'soap_options' => [
                'trace' => true,
            ],
        ];

        $extension->load([$config], $container);

        $this->assertSame('demo', $container->getParameter('picoss_yousign.env'));
        $this->assertSame('login', $container->getParameter('picoss_yousign.username'));
        $this->assertSame('pa$$word', $container->getParameter('picoss_yousign.password'));
        $this->assertSame('123abc', $container->getParameter('picoss_yousign.api_key'));

        $this->assertTrue($container->hasDefinition('picoss_yousign.environment'));
        $this->assertTrue($container->hasDefinition('picoss_yousign.authentication'));
        $this->assertTrue($container->hasDefinition('picoss_yousign.client'));
        $this->assertTrue($container->hasDefinition('picoss_yousign.api'));
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadInvalidConfig()
    {
        $extension = new PicossYousignExtension();
        $container = new ContainerBuilder();

        $extension->load([], $container);
    }
}