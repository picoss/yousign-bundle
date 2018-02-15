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
use Picoss\YousignBundle\Api\AuthenticationApi;
use Picoss\YousignBundle\Api\SignatureApi;
use Picoss\YousignBundle\Yousign\Client;
use Picoss\YousignBundle\Yousign\YousignApi;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class YousignApiTest
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class YousignApiTest extends TestCase
{
    public function testClientFactory()
    {
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $router = $this->createMock(RouterInterface::class);

        $yousignApi = new YousignApi($client, $router);

        $this->assertTrue($yousignApi->authentication instanceof AuthenticationApi);
        $this->assertTrue($yousignApi->signature instanceof SignatureApi);
    }
}
