<?php

namespace Picoss\YousignBundle\Model;

abstract class ModelBase
{
    /**
     * Model relations
     *
     * @var array $subObjects
     */
    protected $subObjects = [];

    /**
     * Createable fields
     *
     * @var array
     */
    protected $createableFields = [];

    /**
     * Updateable fields
     *
     * @var array
     */
    protected $updateableFields = [];

    /**
     * Get model relations
     *
     * @return array
     */
    public function getSubObjects()
    {
        return $this->subObjects;
    }

    /**
     * Get creation fields
     *
     * @return array
     */
    public function getCreateableFields()
    {
        return $this->createableFields;
    }

    /**
     * Get uptadeable fields
     *
     * @return array
     */
    public function getUpdateableFields()
    {
        return $this->updateableFields;
    }
}