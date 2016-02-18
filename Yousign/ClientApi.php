<?php

namespace Picoss\YousignBundle\Yousign;

use YousignAPI\YsApi as BaseYsApi;

class ClientApi extends BaseYsApi
{
    /**
     * @var bool
     */
    private $enableProxy = false;

    /**
     * @var string
     */
    private $proxyHost;

    /**
     * @var integer
     */
    private $proxyPort;

    /**
     * @var string
     */
    private $proxyUsername;

    /**
     * @var string
     */
    private $proxyPassword;

    public function __construct($environment = YsApi::API_ENV_DEMO, $login = null, $password = null, $apiKey = null, $isEncryptedPassword = false)
    {
        if ($isEncryptedPassword === false) {
            $password = $this->encryptPassword($password);
        }

        $this
            ->setEnvironment($environment)
            ->setLogin($login)
            ->setPassword($password)
            ->setApiKey($apiKey)
        ;
    }

    /**
     * Set SSL configuration
     *
     * @param bool|false $enabled
     * @param null $certClientLocation
     * @param null $caChainClientLocation
     * @param null $privateKeyClientLocation
     * @param null $privateKeyClientPassword
     */
    public function setSSL($enabled = false, $certClientLocation = null, $caChainClientLocation = null, $privateKeyClientLocation = null, $privateKeyClientPassword = null)
    {
        $this->setEnabledSSL($enabled)
            ->setCertClientLocation($certClientLocation)
            ->setCaChainClientLocation($caChainClientLocation)
            ->setPrivateKeyClientLocation($privateKeyClientLocation)
            ->setPrivateKeyClientPassword($privateKeyClientPassword)
        ;
    }

    /**
     * Set proxy configuration
     *
     * @param bool|false $enabled
     * @param null $host
     * @param null $port
     * @param null $username
     * @param null $password
     */
    public function setProxy($enabled = false, $host = null, $port = null, $username = null, $password = null)
    {
        $this->enableProxy = $enabled;
        $this->proxyHost = $host;
        $this->proxyPort = $port;
        $this->proxyUsername = $username;
        $this->proxyPassword = $password;
    }

    /**
     * Initialize SOAP client
     *
     * @param string $urlWsdl
     * @return \nusoap_client
     */
    protected function setClientSoap($urlWsdl)
    {
        $client = parent::setClientSoap($urlWsdl);

        if ($this->enableProxy == true) {
            $client->proxyhost = $this->proxyHost;
            $client->proxyport = $this->proxyPort;
            $client->proxyusername = $this->proxyUsername;
            $client->proxypassword = $this->proxyPassword;
        }

        return $client;
    }
}