Yousign Bundle for Symfony2
===========================

[![Latest Stable Version](https://poser.pugx.org/ywh/cvss-bundle/version)](https://packagist.org/packages/picoss/yousign-bundle)
[![Total Downloads](https://poser.pugx.org/ywh/cvss-bundle/downloads)](https://packagist.org/packages/picoss/yousign-bundle)
[![Latest Unstable Version](https://poser.pugx.org/ywh/cvss-bundle/v/unstable)](//packagist.org/packages/picoss/yousign-bundle)
[![License](https://poser.pugx.org/ywh/cvss-bundle/license)](https://packagist.org/packages/picoss/yousign-bundle)

This bundle provides integration for [Yousign](http://developer.yousign.fr/) in your Symfony2 Project.

License: [MIT](LICENSE)

# Installation

## Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
    $ composer require picoss/yousign-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

## 2: Enable the Bundle

> When using Flex, this step is handled automatically.

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php

    // app/AppKernel.php

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...

                new Picoss\YousignBundle\PicossYousignBundle(),
            );

            // ...
        }

        // ...
    }
```

## 3: Configure the bundle

```yaml
# config/packages/picoss_yousign.yml
picoss_yousign:
    env: demo #demo or prod
    api_key: yousign_api_key
    username: yousign_username
    password: yousign_password
```

Note: If you need to pass some options to the saop client, add soap_options config:
```yaml
# config/packages/picoss_yousign.yml
picoss_yousign:
    env: demo #demo or prod
    api_key: yousign_api_key
    username: yousign_username
    password: yousign_password
    soap_options:
        trace: 1
```

Check `$options` argument of the soap client to see available options : http://php.net/manual/en/soapclient.soapclient.php

## 4: Usage

### 4.1: Test API connection

The following example shows how to test the connection to the Yousign API in your controller

```php
<?php

namespace App\Controller;

use Picoss\YousignBundle\Yousign\YousignApi;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    /**
     * @Route("/test", name="test")
     */
    public function index(YousignApi $api)
    {
        $auth = $api->authentication;
        
        $result = $auth->connect() ? 'Connected' : 'Enable to connect';

        $response = new Response();
        $response->setContent(sprintf('<html><body>%s</body></html>', $result));

        return $response;
    }
}
```

### 4.2: Create a signature demand

```php
<?php

namespace App\Controller;

use Picoss\YousignBundle\Model\Cosigner;
use Picoss\YousignBundle\Model\Demand;
use Picoss\YousignBundle\Model\File;
use Picoss\YousignBundle\Yousign\YousignApi;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    /**
     * @Route("/test", name="test")
     */
    public function index(YousignApi $api)
    {
        $signatureApi = $api->signature;

        $file = new File();
        $file
            ->setName('My PDF Filename')
            ->setContent(file_get_contents('/path/to/the/file.pdf'))
        ;

        $cosigner = new Cosigner();
        $cosigner
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setMail('john.doe@email.com')
            ->setAuthenticationMode('mail');

        $visibleOptions = array(
            'visibleSignaturePage' => '5',
            'isVisibleSignature' => true,
            'visibleRectangleSignature' => '10,10,10,10',
        );

        $demand = new Demand();
        $demand
            ->addFile($file, $visibleOptions)
            ->addCosigner($cosigner)
        ;

        $signature = $signatureApi->initCosign($demand);

        $response = new Response();
        $response->setContent(sprintf('<html><body>Signature demand id: %s</body></html>', $signature->getIdDemand()));

        return $response;
    }
}

```

### 4.3: Available methods

#### 4.3.1: Authentication API

| Methods | Description | Return |
| --- | --- | --- |
| connect() | Check API connection | boolean |

#### 4.3.2: Signature API

| Methods | Description | Return |
| --- | --- | --- |
| initCosign() | Initialize a signature demand | Picoss\YousignBundle\Model\Signature |
| getCosignedFilesFromDemand() | Get files from demand id | Picoss\YousignBundle\Model\File[] |
| getInfosFromCosignatureDemand() | Get signature informations | Picoss\YousignBundle\Model\Signature |
| getListCosign() | Search for signatures demand | Picoss\YousignBundle\Model\Signature[] |
| cancelCosignatureDemand() | Cancel a signature process | boolean |
| alertCosigners() | Alter cosigners | boolean |
| isPDFSignable() | Check if the given PDF is signable | boolean |
| updateCosigner() | Update a cosigner informations | boolean |

For more informations, please visite [Yousign API documentation](http://developer.yousign.fr)