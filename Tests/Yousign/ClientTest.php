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
use Picoss\YousignBundle\Yousign\Client;
use Picoss\YousignBundle\Yousign\Environment;
use Psr\Log\NullLogger;
use Yousign\Authentication;

/**
 * Class ClientTest
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class ClientTest extends TestCase
{
    public function testClientFactory()
    {
        $authentication = new Authentication('123');
        $environment = new Environment('demo');
        $logger = new NullLogger();

        $client = Client::create($authentication, $environment, $logger);

        $this->assertSame('https://demo.yousign.fr/public/ext/cosignature/123', $client->getIframeUrl('123'));
    }
}
