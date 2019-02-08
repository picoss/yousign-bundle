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

/**
 * Class Environment
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class Environment
{
    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $iframeUrl;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $apiUrl = 'https://apidemo.yousign.fr:8181', string $iframeUrl = 'https://demo.yousign.fr')
    {
        $this->apiUrl = $apiUrl;
        $this->iframeUrl = $iframeUrl;
    }

    /**
     * @return string
     */
    public function getIframeHost()
    {
        return $this->iframeUrl;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->apiUrl;
    }
}
