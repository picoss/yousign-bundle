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
use Picoss\YousignBundle\Yousign\Authentication;
use Picoss\YousignBundle\Yousign\Environment;

/**
 * Class AuthenticationTest
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class AuthenticationTest extends TestCase
{
    public function testConstructor()
    {
        $authentication = new Authentication('api_key', 'username', 'password');
        $hashedPassword = sha1(sha1('password').sha1('password'));

        $this->assertSame($hashedPassword, $authentication->getPassword());
    }

    public function testPassword()
    {
        $authentication = new Authentication('api_key');
        $authentication->setPassword('password');

        $hashedPassword = sha1(sha1('password').sha1('password'));

        $this->assertSame($hashedPassword, $authentication->getPassword());
    }

    public function testNotHashedPassword()
    {
        $authentication = new Authentication('api_key');
        $authentication->setPassword('password', false);

        $this->assertSame('password', $authentication->getPassword());
    }
}
