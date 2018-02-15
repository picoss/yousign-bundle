<?php
/*
 * This file is part of the YesWeHack BugBounty backend
 *
 * (c) Romain Honel <romain.honel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Picoss\YousignBundle\Yousign;

use Psr\Log\LoggerInterface;
use Yousign\Authentication;
use Yousign\Client as BaseClient;
use Yousign\Services;

/**
 * Class Client
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class Client extends BaseClient
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $iframeHost;

    /**
     * Client constructor.
     *
     * @param Authentication $authentication
     * @param Environment    $environment
     * @param array          $options
     */
    public function __construct(Authentication $authentication, Environment $environment, LoggerInterface $logger, array $options = [])
    {
        $this->logger = $logger;

        foreach(Services::listing() as $service) {
            try {
                $wsdl = sprintf('%s/%s/%s?wsdl', $environment->getHost(), $service, $service);
                $soapClient = new \SoapClient($wsdl, $options);

                $header = new \SoapHeader('http://www.yousign.com', 'Auth', (object)(array)$authentication);
                $soapClient->__setSoapHeaders($header);

                $this->addSoapClient($service, $soapClient);
            } catch (\SoapFault $e) {
                $this->logger->error($e->getMessage());
            }
        }

        $this->iframeHost = $environment->getIframeHost();
    }

    /**
     * Create client
     *
     * @param Authentication $authentication
     * @param Environment    $environment
     * @param array          $options
     *
     * @return Client
     */
    static public function create(Authentication $authentication, Environment $environment, LoggerInterface $logger, array $options = [])
    {
        $client = new self($authentication, $environment, $logger, $options);

        return $client;
    }

    /**
     * {@inheritdoc}
     */
    public function __call($name, $arguments)
    {
        $this->logger->info(sprintf('Call to "%s"', $name), ['arguments' => $arguments]);

        return parent::__call($name, $arguments);
    }

    /**
     * Get demand signature iframe url
     *
     * @param string $token
     *
     * @return string
     */
    public function getIframeUrl($token)
    {
        return $this->iframeHost.'/public/ext/cosignature/'.$token;
    }
}