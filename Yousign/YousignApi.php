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

use Picoss\YousignBundle\Api\AuthenticationApi;
use Picoss\YousignBundle\Api\SignatureApi;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class YsApi
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class YousignApi
{
    /**
     * Yousign client api
     *
     * @var ClientApi
     */
    protected $client;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var SignatureApi
     */
    public $signature;

    /**
     * @var AuthenticationApi
     */
    public $authentication;

    /**
     * YousignApi constructor.
     *
     * @param Client          $client
     * @param RouterInterface $router
     */
    public function __construct(Client $client, RouterInterface $router)
    {
        $this->client = $client;
        $this->router = $router;
        $this->logger = new NullLogger();

        $this->signature = new SignatureApi($this->client, $this->router, $this->logger);
        $this->authentication = new AuthenticationApi($this->client, $this->router, $this->logger);
    }

    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}