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
use Picoss\YousignBundle\Api\SignatureApi;
use Picoss\YousignBundle\Model\Cosigner;
use Picoss\YousignBundle\Model\Demand;
use Picoss\YousignBundle\Model\File;
use Picoss\YousignBundle\Yousign\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class SignatureApiTest
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class SignatureApiTest extends TestCase
{
    private $router;
    private $logger;

    public function setUp()
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testInitCosign()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->any())
            ->method('__call')
            ->with('initCosign')
            ->will($this->returnValue([
                'idDemand' => 123,
                'fileInfos' => [
                    'idFile' => 456,
                    'fileName' => 'file_name'
                ],
                'tokens' => [
                    'token' => '1a2b3c',
                    'mail' => 'email@email.com',
                    'phone' => '+33123456789',
                ],
            ]))
        ;

        $file = $this->getFile();
        $cosigner = $this->getCosigner();
        $visibleOptions = $this->getVisibleOptions();

        $demand = new Demand();
        $demand
            ->addFile($file, $visibleOptions)
            ->addCosigner($cosigner);

        $signatureApi = new SignatureApi($client, $this->router, $this->logger);
        $signature = $signatureApi->initCosign($demand);

        $this->assertSame(123, $signature->getIdDemand());
        $this->assertSame(456, $signature->getFileInfos()->getIdFile());
        $this->assertSame('file_name', $signature->getFileInfos()->getFileName());

        $this->assertSame('1a2b3c', $signature->getTokens()->getToken());
        $this->assertSame('email@email.com', $signature->getTokens()->getMail());
        $this->assertSame('+33123456789', $signature->getTokens()->getPhone());
    }

    public function testGetCosignedFilesFromDemand()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->any())
            ->method('__call')
            ->with('getCosignedFilesFromDemand')
            ->will($this->returnValue([[
                'file' => 'file_content_1',
                'fileName' => 'file_name_1',
            ], [
                'file' => 'file_content_2',
                'fileName' => 'file_name_2',
            ]]))
        ;

        $signatureApi = new SignatureApi($client, $this->router, $this->logger);
        $cosignedFiles = $signatureApi->getCosignedFilesFromDemand('123');

        $this->assertCount(2, $cosignedFiles);
        $this->assertSame('file_content_1', $cosignedFiles->first()->getFile());
        $this->assertSame('file_name_1', $cosignedFiles->first()->getFileName());
    }

    public function testGetInfosFromCosignatureDemand()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->any())
            ->method('__call')
            ->with('getInfosFromCosignatureDemand')
            ->will($this->returnValue([
                'title' => 'Signature title',
                'dateCreation' => '2014-01-01T00:00:00+02:00',
                'status' => 'COSIGNATURE_EVENT_REQUEST_PENDING',
                'fileInfos' => [
                    'idFile' => 'file_1',
                    'fileName' => 'file_name_1',
                    'cosignersWithStatus' => [[
                        'id' => 123,
                        'status' => 'COSIGNATURE_FILE_SIGNED',
                        'signatureDate' => '2014-01-01T00:00:00+02:00',
                    ], [
                        'id' => 124,
                        'status' => 'COSIGNATURE_FILE_SIGNED',
                        'signatureDate' => '2014-01-01T00:00:00+02:00',
                    ]],
                ],
                'cosignerInfos' => [[
                    'id' => 'cosigner_1',
                    'firstName' => 'firstname_1',
                    'lastName' => 'lastname_1',
                    'mail' => 'email1@email.com',
                    'phone' => '+33123',
                    'proofLevel' => 'LOW',
                    'isCosignerCalling' => true,
                    'token' => '1a2b3c',
                ], [
                    'id' => 'cosigner_2',
                    'firstName' => 'firstname_2',
                    'lastName' => 'lastname_2',
                    'mail' => 'email2@email.com',
                    'phone' => '+33123456',
                    'proofLevel' => 'LOW',
                    'isCosignerCalling' => true,
                    'token' => '1a2b3c4d',
                    'authenticationmode' => 'sms',
                ]],
                'initiator' => [
                    'name' => 'Initiator name',
                    'email' => 'initiator@email.com',
                ],
            ]))
        ;

        $signatureApi = new SignatureApi($client, $this->router, $this->logger);
        $signature = $signatureApi->getInfosFromCosignatureDemand('123');

        $this->assertSame('Signature title', $signature->getTitle());
        $this->assertSame('COSIGNATURE_EVENT_REQUEST_PENDING', $signature->getStatus());
        $this->assertSame('2014-01-01T00:00:00+02:00', $signature->getDateCreation());

        $this->assertSame('file_1', $signature->getFileInfos()->getIdFile());
        $this->assertSame('file_name_1', $signature->getFileInfos()->getFileName());

        $this->assertCount(2, $signature->getFileInfos()->getCosignersWithStatus());
        $fileInfosCosigners = $signature->getFileInfos()->getCosignersWithStatus()->first();
        $this->assertSame(123, $fileInfosCosigners->getId());
        $this->assertSame('COSIGNATURE_FILE_SIGNED', $fileInfosCosigners->getStatus());
        $this->assertSame('2014-01-01T00:00:00+02:00', $fileInfosCosigners->getSignatureDate());

        $this->assertCount(2, $signature->getCosignerInfos());
        $cosigner = $signature->getCosignerInfos()->first();
        $this->assertSame('cosigner_1', $cosigner->getId());
        $this->assertSame('firstname_1', $cosigner->getFirstName());
        $this->assertSame('lastname_1', $cosigner->getLastName());
        $this->assertSame('email1@email.com', $cosigner->getMail());
        $this->assertSame('+33123', $cosigner->getPhone());
        $this->assertSame('LOW', $cosigner->getProofLevel());
        $this->assertTrue($cosigner->getIsCosignerCalling());
        $this->assertSame('1a2b3c', $cosigner->getToken());

        $this->assertSame('Initiator name', $signature->getInitiator()->getName());
        $this->assertSame('initiator@email.com', $signature->getInitiator()->getEmail());
    }

    public function testGetListCosign()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->any())
            ->method('__call')
            ->with('getListCosign')
            ->will($this->returnValue([[
                'title' => 'Signature title',
            ], [
                'title' => 'Signature title 2',
            ], [
                'title' => 'Signature title 3',
            ], [
                'title' => 'Signature title 4',
            ]]))
        ;

        $signatureApi = new SignatureApi($client, $this->router, $this->logger);
        $result = $signatureApi->getListCosign([]);

        $this->assertCount(4, $result);
    }

    public function testCancelCosignatureDemand()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->any())
            ->method('__call')
            ->with('cancelCosignatureDemand')
            ->will($this->returnValue(true))
        ;

        $signatureApi = new SignatureApi($client, $this->router, $this->logger);
        $this->assertTrue($signatureApi->cancelCosignatureDemand('123'));
    }

    public function testAlertCosigners()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->any())
            ->method('__call')
            ->with('alertCosigners')
            ->will($this->returnValue(true))
        ;

        $signatureApi = new SignatureApi($client, $this->router, $this->logger);
        $this->assertTrue($signatureApi->alertCosigners('123'));
    }

    public function testIsPDFSignable()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->any())
            ->method('__call')
            ->with('isPDFSignable')
            ->will($this->returnValue(true))
        ;

        $file = $this->getFile();

        $signatureApi = new SignatureApi($client, $this->router, $this->logger);
        $this->assertTrue($signatureApi->isPDFSignable($file));
    }

    public function testUpdateCosigner()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->any())
            ->method('__call')
            ->with('updateCosigner')
            ->will($this->returnValue(true))
        ;

        $cosigner = $this->getCosigner();

        $signatureApi = new SignatureApi($client, $this->router, $this->logger);
        $this->assertTrue($signatureApi->updateCosigner('123', $cosigner));
    }

    private function getFile()
    {
        $file = new File();
        $file
            ->setName('name')
            ->setContent('content');

        return $file;
    }

    private function getCosigner()
    {
        $cosigner = new Cosigner();
        $cosigner
            ->setFirstName('firstname')
            ->setLastName('lastname')
            ->setMail('email@email.com')
            ->setAuthenticationMode('mail');

        return $cosigner;
    }

    private function getVisibleOptions()
    {
        return [
            'visibleSignaturePage' => 1,
            'isVisibleSignature' => true,
            'visibleRectangleSignature' => '10,10,10,10',
        ];
    }
}