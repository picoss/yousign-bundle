<?php

/*
 * This file is part of the YesWeHack BugBounty backend
 *
 * (c) Romain Honel <romain.honel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Picoss\YousignBundle\Model;

/**
 * Class ModelBase
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
abstract class ModelBase
{
    /**
     * Model relations
     *
     * @var array $subObjects
     */
    protected $subObjects = [];

    /**
     * Get model relations
     *
     * @return array
     */
    public function getSubObjects()
    {
        return $this->subObjects;
    }
}