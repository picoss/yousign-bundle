<?php
/*
 * This file is part of the YesWeHack BugBounty backend
 *
 * (c) Romain Honel <romain.honel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Picoss\YousignBundle\Tests\Yousign;

use PHPUnit\Framework\TestCase;
use Picoss\YousignBundle\Yousign\Environment;

/**
 * Class EnvironmentTest
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class EnvironmentTest extends TestCase
{
    public function testDemo()
    {
        $environment = new Environment('demo');

        $this->assertSame('https://apidemo.yousign.fr:8181', $environment->getHost());
        $this->assertSame('https://demo.yousign.fr', $environment->getIframeHost());
    }

    public function testProd()
    {
        $environment = new Environment('prod');

        $this->assertSame('https://api.yousign.fr:8181', $environment->getHost());
        $this->assertSame('https://yousign.fr', $environment->getIframeHost());
    }

    /**
     * @expectedException Yousign\Exception\EnvironmentException
     */
    public function testInvalidEnv()
    {
        new Environment('foo');
    }
}
