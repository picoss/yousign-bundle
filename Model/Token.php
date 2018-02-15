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
 * Class Token
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
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