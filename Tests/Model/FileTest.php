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
use Picoss\YousignBundle\Model\File;
use Picoss\YousignBundle\Yousign\Client;
use Picoss\YousignBundle\Yousign\Environment;
use Psr\Log\NullLogger;
use Yousign\Authentication;

/**
 * Class FileTest
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class FileTest extends TestCase
{
    public function testProperties()
    {
        $file = new File();
        $file
            ->setName('name')
            ->setContent('foo')
            ->setPdfPassword('pass')
        ;

        $this->assertSame('name', $file->getName());
        $this->assertSame('foo', $file->getContent());
        $this->assertSame('pass', $file->getPdfPassword());
    }

    public function testToArray()
    {
        $file = new File();
        $file
            ->setName('name')
            ->setContent('foo')
            ->setPdfPassword('pass')
        ;

        $expected = [
            'name' => 'name',
            'content' => 'foo',
            'pdfPassword' => 'pass',
        ];

        $this->assertEquals($expected, $file->toArray());
    }
}
