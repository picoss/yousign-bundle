<?php

namespace Picoss\YousignBundle\Model;

class Token extends ModelBase
{
    /**
     * token
     *
     * @var string
     */
    protected $token;

    /**
     * mail
     *
     * @var string
     */
    protected $mail;

    /**
     * phone
     *
     * @var string
     */
    protected $phone;

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
}