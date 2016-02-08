<?php

namespace Picoss\YousignBundle\Yousign;

use YousignAPI\YsApi as BaseYsApi;

class YsApi extends BaseYsApi
{

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

    public function setSSL($enabled = false, $certClientLocation = null, $caChainClientLocation = null, $privateKeyClientLocation = null, $privateKeyClientPassword = null)
    {
        $this
            ->setEnabledSSL($enabled)
            ->setCertClientLocation($certClientLocation)
            ->setCaChainClientLocation($caChainClientLocation)
            ->setPrivateKeyClientLocation($privateKeyClientLocation)
            ->setPrivateKeyClientPassword($privateKeyClientPassword)
        ;
    }

}