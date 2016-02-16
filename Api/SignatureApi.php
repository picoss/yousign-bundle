<?php

namespace Picoss\YousignBundle\Api;

use Picoss\YousignBundle\Model\Cosigner;
use Picoss\YousignBundle\Model\File;
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
            $file->getIdFile() => $visibleOptions,
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
            dump($result);
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
     * @param null $target
     * @param null $callbackRoute
     * @param null $successRoute
     * @param null $cancelRoute
     * @param null $tpl
     * @return string
     */
    public function getIframeUrl($token, $target = null, $callbackRoute = null, $successRoute = null, $cancelRoute = null, $tpl = null)
    {
        $iframeUrl = $this->root->getIframeUrl($token);

        $queryString = array();
        if (null !== $target) {
            $queryString['urltarget'] = $target;
        }
        if (null !== $callbackRoute) {
            $queryString['urlcallback'] = $this->router->generate($callbackRoute, array(), RouterInterface::ABSOLUTE_URL);
        }
        if (null !== $successRoute) {
            $queryString['urlsuccess'] = $this->router->generate($successRoute, array(), RouterInterface::ABSOLUTE_URL);
        }
        if (null !== $cancelRoute) {
            $queryString['urlcancel'] = $this->router->generate($cancelRoute, array(), RouterInterface::ABSOLUTE_URL);
        }
        if (null !== $tpl) {
            $queryString['tpl'] = $tpl;
        }

        return $iframeUrl . (count($queryString) ? '?' . http_build_query($queryString) : '');
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