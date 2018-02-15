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
 * Class Initiator
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class Initiator extends ModelBase
{
    /**
     * name
     *
     * @var string
     */
    protected $name;

    /**
     * email
     *
     * @var string
     */
    protected $email;

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}