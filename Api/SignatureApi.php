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

use Doctrine\Common\Collections\ArrayCollection;
use Picoss\YousignBundle\Model\Cosigner;
use Picoss\YousignBundle\Model\Demand;
use Picoss\YousignBundle\Model\File;
use Picoss\YousignBundle\Model\Signature;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class SignatureApi
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class SignatureApi extends BaseApi
{
    /**
     * Signature model class name
     *
     * @var string
     */
    protected $signatureModelClassName = Signature::class;

    /**
     * File model class name
     *
     * @var string
     */
    protected $fileModelClassName = File::class;

    /**
     * Initialize signature process
     *
     * @param Demand $demand
     *
     * @return Signature|mixed
     *
     * @throws \Exception
     */
    public function initCosign(Demand $demand)
    {
        $arguments = [
            'lstCosignedFile' => [],
            'lstCosignerInfos' => [],
        ] + $demand->getOptions();

        foreach ($demand->getFiles() as $id => $infos) {
            /** @var File $file */
            $file = $infos['file'];
            $options = $infos['options'];

            $cosignedFile = $file->toArray() + ['visibleOptions' => []];

            /** @var Cosigner $cosigner */
            foreach ($demand->getCosigners() as $cosigner) {
                $cosignedFile['visibleOptions'][] = $options + ['mail' => $cosigner->getMail()];
            }

            $arguments['lstCosignedFile'][] = $cosignedFile;
        }

        /** @var Cosigner $cosigner */
        foreach ($demand->getCosigners() as $cosigner) {
            $arguments['lstCosignerInfos'][] = $cosigner->toArray();
        }

        try {
            $result = $this->client->initCosign($arguments);

            return $this->castResponseToEntity($result, $this->signatureModelClassName);
        } catch (\Exception $e) {
            throw $e;
        }

        return false;
    }

    /**
     * Get files from a demand or a token
     *
     * @param int    $demandId
     * @param string $token
     * @param string $fileId
     *
     * @return Signature[]|false
     *
     * @throws \Exception
     */
    public function getCosignedFilesFromDemand($demandId, $token = null, $fileId = null)
    {
        try {
            $result = $this->client->getCosignedFilesFromDemand([
                'idDemand' => $demandId,
                'token' => $token,
                'idFile' => $fileId,
            ]);

            return $this->castResponseToEntity($result, $this->fileModelClassName);
        } catch (\Exception $e) {
            throw $e;
        }

        return false;
    }

    /**
     * Get informations for the given demand id or token
     *
     * @param int    $demandId
     * @param string $token
     *
     * @return Signature|false
     *
     * @throws \Exception
     */
    public function getInfosFromCosignatureDemand($demandId, $token = null)
    {
        try {
            $result = $this->client->getInfosFromCosignatureDemand([
                'idDemand' => $demandId,
                'token' => $token,
            ]);

            return $this->castResponseToEntity($result, $this->signatureModelClassName);
        } catch (\Exception $e) {
            throw $e;
        }

        return false;
    }

    /**
     * Search for signature processes
     *
     * @param array $options
     *
     * @return Signature[]|false
     *
     * @throws \Exception
     */
    public function getListCosign(array $options = array())
    {
        $optionsResolver = new OptionsResolver();
        $this->configureListCosignOptions($optionsResolver);
        $options = $optionsResolver->resolve($options);

        try {
            $result = $this->client->getListCosign($options);

            return $this->castResponseToEntity($result, $this->signatureModelClassName);
        } catch (\Exception $e) {
            throw $e;
        }

        return false;
    }

    /**
     * Cancel the signature process of the given demand id
     *
     * @param int $demandId
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function cancelCosignatureDemand($demandId)
    {
        try {
            return $this->client->cancelCosignatureDemand([
                'idDemand' => $demandId,
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

        return false;
    }

    /**
     * Alert signers of the given demand id
     *
     * @param int   $demandId
     * @param array $options
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function alertCosigners($demandId, array $options = [])
    {
        $optionsResolver = new OptionsResolver();
        $this->configureAlertCosignersOptions($optionsResolver);
        $options = $optionsResolver->resolve($options);

        try {
            return $this->client->alertCosigners(['idDemand' => $demandId] + $options);
        } catch (\Exception $e) {
            throw $e;
        }

        return false;
    }

    /**
     * Check if the given pdf is signable
     *
     * @param File $file
     * @throws \Exception
     */
    public function isPDFSignable(File $file)
    {
        try {
            return $this->client->isPDFSignable([
                'pdfFile' => $file->getContent(),
                'pdfPassword' => $file->getPdfPassword(),
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update cosigner informations
     *
     * @param string   $token
     * @param Cosigner $cosigner
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function updateCosigner($token, Cosigner $cosigner)
    {
        try {
            return $this->client->updateCosigner([
                'token' => $token,
                'cosignerInfos' => $cosigner->toArray(),
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

        return false;
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
        $iframeUrl = $this->client->getIframeUrl($token);

        $resolver = new OptionsResolver();
        $this->configureIframeOptions($resolver);
        $options = $resolver->resolve($options);

        unset($options['urlcallbackparams'], $options['urlsuccessparams'], $options['urlcancelparams']);

        return $iframeUrl.(count($options) ? '?'.http_build_query($options) : '');
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configureIframeOptions(OptionsResolver $resolver)
    {
        $router = $this->router;
        $resolver
            ->setDefaults([
                'urltarget' => '_top',
                'urlcallbackparams' => [],
                'urlsuccessparams' => [],
                'urlcancelparams' => [],
                'needconso' => true,
            ])
            ->setAllowedValues('urltarget', ['_top', '_parent', '_blank'])
            ->setDefined([
                'urlcallback',
                'urlsuccess',
                'urlcancel',
                'tpl',
                'reason',
                'custommention',
            ])
            ->setNormalizer('urlcallback', function (Options $options, $value) use($router) {
                return $router->generate($value, $options['urlcallbackparams'], RouterInterface::ABSOLUTE_URL);
            })
            ->setNormalizer('urlsuccess', function (Options $options, $value) use($router) {
                return $router->generate($value, $options['urlsuccessparams'], RouterInterface::ABSOLUTE_URL);
            })
            ->setNormalizer('urlcancel', function (Options $options, $value) use($router) {
                return $router->generate($value, $options['urlcancelparams'], RouterInterface::ABSOLUTE_URL);
            })
            ->setNormalizer('needconso', function (Options $options, $value) {
                return $value ? 1 : 0;
            })
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configureListCosignOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'search' => null,
            'firstResult' => null,
            'count' => 1000,
            'status' => null,
            'dateBegin' => null,
            'dateEnd' => null,
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configureAlertCosignersOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'mailSubject ' => null,
                'mail' => null,
                'language' => 'EN',
            ])
            ->setAllowedValues('language', ['FR', 'EN', 'DE']);
    }
}
