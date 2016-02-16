<?php

namespace Picoss\YousignBundle\Api;

use Doctrine\Common\Collections\ArrayCollection;
use Picoss\YousignBundle\Yousign\ClientApi;
use Symfony\Component\Routing\RouterInterface;

abstract class BaseApi
{
    /**
     * @var ClientApi
     */
    protected $root;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(ClientApi $root, RouterInterface $router)
    {
        $this->root = $root;
        $this->router = $router;
    }

    /**
     * Get errors returned by Yousign API
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->root->getErrors();
    }

    /**
     * Cast Yousign API respose to a given entity
     *
     * @param $response
     * @param $entityClassName
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

    private function isAssociativeArray($array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}