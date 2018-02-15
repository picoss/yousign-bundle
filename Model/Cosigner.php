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
 * Class Cosigner
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class Cosigner extends ModelBase
{
    const MODE_MAIL = 'mail';
    const MODE_SMS = 'sms';
    const MODE_MASS = 'mass';
    const MODE_MANUAL = 'manual';
    const MODE_PHOTO = 'photo';

    const AUTH_MODE = [
        self::MODE_MAIL,
        self::MODE_SMS,
        self::MODE_MASS,
        self::MODE_MANUAL,
        self::MODE_PHOTO,
    ];

    const LEVEL_LOW = 'LOW';
    const LEVEL_HIGH = 'HIGH';

    const PROOF_LEVEL = [
        self::LEVEL_LOW,
        self::LEVEL_HIGH,
    ];
    /**
     * id
     *
     * @var string
     */
    protected $id;

    /**
     * status
     *
     * @var string
     */
    protected $status;

    /**
     * firstName
     *
     * @var string
     */
    protected $firstName;

    /**
     * lastName
     *
     * @var string
     */
    protected $lastName;

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
     * proofLevel
     *
     * @var string
     */
    protected $proofLevel;

    /**
     * authenticationMode
     *
     * @var string
     */
    protected $authenticationMode;

    /**
     * signatureDate
     *
     * @var string
     */
    protected $signatureDate;

    /**
     * textToWrite
     *
     * @var string
     */
    protected $textToWrite;

    /**
     * isCosignerCalling
     *
     * @var string
     */
    protected $isCosignerCalling;

    /**
     * token
     *
     * @var string
     */
    protected $token;

    public function toArray()
    {
        return array(
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'mail' => $this->mail,
            'phone' => $this->phone,
            'proofLevel' => $this->proofLevel,
            'authenticationMode' => $this->authenticationMode,
        );
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set firstName
     *
     * @var string $firstName
     * @return Cosigner
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @var string $lastName
     * @return Cosigner
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set mail
     *
     * @var string $mail
     * @return Cosigner
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
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
     * Set phone
     *
     * @var string $phone
     * @return Cosigner
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
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

    /**
     * Set proofLevel
     *
     * @var string $proofLevel
     * @return Cosigner
     */
    public function setProofLevel($proofLevel)
    {
        $proofLevel = strtoupper($proofLevel);
        if (!in_array($proofLevel, self::PROOF_LEVEL)) {
            throw new \InvalidArgumentException(sprintf('Invalid proof level "%s". Available proof levels: %s', $proofLevel, implode(',', self::PROOF_LEVEL)));
        }
        $this->proofLevel = $proofLevel;

        return $this;
    }

    /**
     * Get proofLevel
     *
     * @return string
     */
    public function getProofLevel()
    {
        return $this->proofLevel;
    }

    /**
     * Set authenticationMode
     *
     * @var string $authenticationMode
     * @return Cosigner
     */
    public function setAuthenticationMode($authenticationMode)
    {
        $authenticationMode = strtolower($authenticationMode);
        if (!in_array($authenticationMode, self::AUTH_MODE)) {
            throw new \InvalidArgumentException(sprintf('Invalid authentication mode "%s". Available modes: %s', $authenticationMode, implode(',', self::AUTH_MODE)));
        }
        $this->authenticationMode = $authenticationMode;

        return $this;
    }

    /**
     * Get authenticationMode
     *
     * @return string
     */
    public function getAuthenticationMode()
    {
        return $this->authenticationMode;
    }

    /**
     * Get signatureDate
     *
     * @return string
     */
    public function getSignatureDate()
    {
        return $this->signatureDate;
    }

    /**
     * Get textToWrite
     *
     * @return string
     */
    public function getTextToWrite()
    {
        return $this->textToWrite;
    }

    /**
     * Get isCosignerCalling
     *
     * @return string
     */
    public function getIsCosignerCalling()
    {
        return $this->isCosignerCalling;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}