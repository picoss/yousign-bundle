<?php

namespace Picoss\YousignBundle\Api;

use Picoss\YousignBundle\Model\Cosigner;
use Picoss\YousignBundle\Model\File;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class SignatureApi extends BaseApi
{

    /**
     * Signature model class name
     *
     * @var string
     */
    protected $signatureModelClassName = 'Picoss\\YousignBundle\\Model\\Signature';

    /**
     * File model class name
     *
     * @var string
     */
    protected $fileModelClassName = 'Picoss\\YousignBundle\\Model\\File';

    /**
     * Create a single signature demand for a single user on a single file
     *
     * @param File $file
     * @param Cosigner $cosigner
     * @param array $visibleOptions
     * @param null $message
     * @param array $options
     * @return bool|mixed
     */
    public function createSingle(File $file, Cosigner $cosigner, array $visibleOptions = array(), $message = null, array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->configureCreateOptions($resolver);
        $options = $resolver->resolve($options);

        $visibleResolver = new OptionsResolver();
        $this->configureVisibleOptions($visibleResolver);
        $visibleOptions = $visibleResolver->resolve($visibleOptions);

        $visibleOptions['mail'] = $cosigner->getMail();

        $aFile = array($file->toArray());
        $aCosigner = array($cosigner->toArray());
        $aVisible = array(
            $file->getIdFile() => array($visibleOptions),
        );

        try {
            $result = $this->root->initCoSign($aFile, $aCosigner, $aVisible, $message, $options);
            return $this->castResponseToEntity($result, $this->signatureModelClassName);
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * Find signatures demand
     *
     * @param array $options
     * @return bool|mixed
     */
    public function find(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->configureFindOptions($resolver);

        $options = $resolver->resolve($options);

        try {
            $result = $this->root->getListCosign($options);
            return $this->castResponseToEntity($result, $this->signatureModelClassName);
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * Get signature informations for a given demand ID
     *
     * @param $idDemand
     * @return bool|mixed
     */
    public function getByIdDemand($idDemand)
    {
        try {
            $result = $this->root->getCosignInfoFromIdDemand($idDemand);
            return $this->castResponseToEntity($result, $this->signatureModelClassName);
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * Get signature informations for a given token
     *
     * @param $token
     * @return bool|mixed
     */
    public function getByToken($token)
    {
        try {
            $result = $this->root->getCosignInfoFromToken($token);
            return $this->castResponseToEntity($result, $this->signatureModelClassName);
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * Get file for a given demand ID and file ID
     *
     * @param $idDemand
     * @param $idFile
     * @return bool|mixed
     */
    public function getFileByIdDemand($idDemand, $idFile)
    {
        try {
            $result = $this->root->getCosignedFileFromIdDemand($idDemand, $idFile);
            return $this->castResponseToEntity($result, $this->fileModelClassName);
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * Get file for a given demand ID and token
     *
     * @param $token
     * @param $idFile
     * @return bool|mixed
     */
    public function getFileByToken($token, $idFile)
    {
        try {
            $result = $this->root->getCosignedFileFromToken($token, $idFile);
            return $this->castResponseToEntity($result, $this->fileModelClassName);
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * Cancel a demand
     *
     * @param $idDemand
     * @return bool
     */
    public function cancel($idDemand)
    {
        return $this->root->deleteCosignDemand($idDemand);
    }

    /**
     * Send an email to cigners who haven't signed document(s) yet
     *
     * @param $idDemand
     * @return bool
     */
    public function alertCosigners($idDemand)
    {
        return $this->root->alertCosigners($idDemand);
    }

    /**
     * Get iframe url to display in an application
     *
     * @param $token
     * @param array $options
     * @return string
     */
    public function getIframeUrl($token, array $options = array())
    {
        $iframeUrl = $this->root->getIframeUrl($token);

        $resolver = new OptionsResolver();
        $this->configureIframeOptions($resolver);
        $options = $resolver->resolve($options);

        unset($options['urlcallbackparams'], $options['urlsuccessparams'], $options['urlcancelparams']);

        return $iframeUrl . (count($options) ? '?' . http_build_query($options) : '');
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configureIframeOptions(OptionsResolver $resolver)
    {
        $router = $this->router;
        $resolver
            ->setDefaults(array(
                'urltarget' => '_top',
                'urlcallbackparams' => array(),
                'urlsuccessparams' => array(),
                'urlcancelparams' => array(),
            ))
            ->setAllowedValues(array(
                'urltarget' => array('_top', '_parent', '_blank')
            ))
            ->setDefined(array(
                'urlcallback', 'urlsuccess', 'urlcancel', 'tpl',
            ))
            ->setNormalizer('urlcallback', function (Options $options, $value) use($router) {
                return $router->generate($value, $options['urlcallbackparams'], RouterInterface::ABSOLUTE_URL);
            })
            ->setNormalizer('urlsuccess', function (Options $options, $value) use($router) {
                return $router->generate($value, $options['urlsuccessparams'], RouterInterface::ABSOLUTE_URL);
            })
            ->setNormalizer('urlcancel', function (Options $options, $value) use($router) {
                return $router->generate($value, $options['urlcancelparams'], RouterInterface::ABSOLUTE_URL);
            })
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configureVisibleOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'visibleSignaturePage' => 1,
                'isVisibleSignature' => true,
                'visibleRectangleSignature' => '0,0,100,50'
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configureCreateOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'mode' => 'IFRAME',
                'archive' => false,
                'language' => 'EN',
            ))
            ->setDefined(array(
                'message', 'title', 'initMailSubject', 'initMail', 'endMailSubject', 'endMail', 'language'
            ))
            ->setAllowedValues(array(
                'language' => array('FR', 'EN', 'DE')
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configureFindOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(
            array(
                'search',
                'firstResult',
                'count',
                'status',
                'dateBegin',
                'dateEnd'
            )
        );
    }
}
