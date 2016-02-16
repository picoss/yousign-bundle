<?php

namespace Picoss\YousignBundle\Yousign;

use Picoss\YousignBundle\Api\SignatureApi;
use Symfony\Component\Routing\RouterInterface;

class YsApi
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

    public function __construct(ClientApi $client, RouterInterface $router)
    {
        $this->client = $client;
        $this->router = $router;
    }

    public function signature()
    {
        return new SignatureApi($this->client, $this->router);
    }
}