<?php

namespace Picoss\YousignBundle\Model;

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