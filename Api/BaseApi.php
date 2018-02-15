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
use Picoss\YousignBundle\Model\ModelBase;
use Picoss\YousignBundle\Yousign\Client;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class BaseApi
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
abstract class BaseApi
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * BaseApi constructor.
     *
     * @param Client          $client
     * @param RouterInterface $router
     */
    public function __construct(Client $client, RouterInterface $router, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->router = $router;
        $this->logger = $logger;
    }

    /**
     * Cast Yousign API respose to a given entity
     *
     * @param string $response
     * @param string $entityClassName
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function castResponseToEntity($response, $entityClassName)
    {
        if (is_array($response)) {
            if ($this->isAssociativeArray($response)) {
                $response = json_decode(json_encode($response, false));
            }
            else {
                $list = new ArrayCollection();
                foreach ($response as $responseObject) {
                    $list->add($this->castResponseToEntity($responseObject, $entityClassName));
                }

                return $list;
            }
        }

        if (!class_exists($entityClassName)) {
            throw new \Exception(sprintf('Class "%s" does not exists.', $entityClassName));
        }

        /** @var ModelBase $entity */
        $entity = new $entityClassName();

        $responseReflection = new \ReflectionObject($response);
        $entityReflection = new \ReflectionClass($entityClassName);
        $responseProperties = $responseReflection->getProperties();

        $subObjects = $entity->getSubObjects();

        foreach ($responseProperties as $responseProperty) {

            $responseProperty->setAccessible(true);

            $name = $responseProperty->getName();
            $value = $responseProperty->getValue($response);

            if ($entityReflection->hasProperty($name)) {

                $entityProperty = $entityReflection->getProperty($name);
                $entityProperty->setAccessible(true);

                // is sub object?
                if (isset($subObjects[$name])) {
                    if (is_null($value)) {
                        $object = null;
                    } else {
                        $object = $this->castResponseToEntity($value, $subObjects[$name]);
                    }

                    $entityProperty->setValue($entity, $object);
                } else {
                    $entityProperty->setValue($entity, $value);
                }
            }
        }

        return $entity;
    }

    /**
     * Is the given array an associative array
     *
     * @param array $array
     *
     * @return bool
     */
    private function isAssociativeArray($array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}