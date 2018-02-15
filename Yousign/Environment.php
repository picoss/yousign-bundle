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

use Yousign\Environment as BaseEnvironment;

/**
 * Class Environment
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class Environment extends BaseEnvironment
{
    /**
     * @var string
     */
    protected $iframeEnvironment;

    /**
     * @var array
     */
    private $mappingIframeHosts = array(
        self::DEMO => 'https://demo.yousign.fr',
        self::PROD => 'https://yousign.fr',
    );

    /**
     * {@inheritdoc}
     */
    public function __construct($environment = null)
    {
        parent::__construct($environment);

        $this->iframeEnvironment = $environment;
    }

    /**
     * @return string
     */
    public function getIframeHost()
    {
        return $this->mappingIframeHosts[$this->iframeEnvironment];
    }
}