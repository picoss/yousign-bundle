<?php
/*
 * This file is part of the YesWeHack BugBounty backend
 *
 * (c) Romain Honel <romain.honel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Picoss\YousignBundle\Tests\Api;

use PHPUnit\Framework\TestCase;
use Picoss\YousignBundle\Api\AuthenticationApi;
use Picoss\YousignBundle\Yousign\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class AuthenticationApiTest
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class AuthenticationApiTest extends TestCase
{
    private $router;
    private $logger;

    public function setUp()
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testConnect()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->any())
            ->method('__call')
            ->with('connect')
            ->will($this->returnValue(true))
        ;

        $authenticationApi = new AuthenticationApi($client, $this->router, $this->logger);

        $this->assertTrue($authenticationApi->connect());
    }
}