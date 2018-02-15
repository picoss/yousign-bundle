<?php

/*
 * This file is part of the YesWeHack BugBounty backend
 *
 * (c) Romain Honel <romain.honel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Picoss\YousignBundle\Api;

use Picoss\YousignBundle\Model\Cosigner;
use Picoss\YousignBundle\Model\File;
use Picoss\YousignBundle\Model\Signature;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class AuthenticationApi
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class AuthenticationApi extends BaseApi
{
    /**
     * Check connection to the API
     *
     * @return mixed
     */
    public function connect()
    {
        try {
            return $this->client->connect();
        }
        catch (\SoapFault $e) {
            $this->logger->error(sprintf('Yousign error: %s', $e->getMessage()));

            return false;
        }
    }
}
