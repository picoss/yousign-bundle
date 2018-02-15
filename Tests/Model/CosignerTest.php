<?php
/*
 * This file is part of the YesWeHack BugBounty backend
 *
 * (c) Romain Honel <romain.honel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Picoss\YousignBundle\Tests\Model;

use PHPUnit\Framework\TestCase;
use Picoss\YousignBundle\Model\Cosigner;
use Picoss\YousignBundle\Yousign\Client;
use Picoss\YousignBundle\Yousign\Environment;
use Psr\Log\NullLogger;
use Yousign\Authentication;

/**
 * Class CosignerTest
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class CosignerTest extends TestCase
{
    public function testProperties()
    {
        $cosigner = new Cosigner();
        $cosigner
            ->setFirstName('firstname')
            ->setLastName('lastname')
            ->setMail('email@email.com')
            ->setPhone('123')
            ->setProofLevel(Cosigner::LEVEL_LOW)
            ->setAuthenticationMode(Cosigner::MODE_MAIL)
        ;

        $this->assertSame('firstname', $cosigner->getFirstName());
        $this->assertSame('lastname', $cosigner->getLastName());
        $this->assertSame('email@email.com', $cosigner->getMail());
        $this->assertSame('123', $cosigner->getPhone());
        $this->assertSame('LOW', $cosigner->getProofLevel());
        $this->assertSame('mail', $cosigner->getAuthenticationMode());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidProofLevel()
    {
        $cosigner = new Cosigner();
        $cosigner->setProofLevel('invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidAuthenticationMode()
    {
        $cosigner = new Cosigner();
        $cosigner->setAuthenticationMode('invalid');
    }

    public function testToArray()
    {
        $cosigner = new Cosigner();
        $cosigner
            ->setFirstName('firstname')
            ->setLastName('lastname')
            ->setMail('email@email.com')
            ->setPhone('123')
            ->setProofLevel(Cosigner::LEVEL_LOW)
            ->setAuthenticationMode(Cosigner::MODE_MAIL)
        ;

        $expected = [
            'firstName' => 'firstname',
            'lastName' => 'lastname',
            'mail' => 'email@email.com',
            'phone' => '123',
            'proofLevel' => 'LOW',
            'authenticationMode' => 'mail',
        ];

        $this->assertEquals($expected, $cosigner->toArray());
    }
}
