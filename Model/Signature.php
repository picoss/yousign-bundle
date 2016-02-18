<?php

namespace Picoss\YousignBundle\Model;

class Signature extends ModelBase
{
    const STATUS_COSIGNATURE_EVENT_REQUEST_PENDING = 'COSIGNATURE_EVENT_REQUEST_PENDING';
    const STATUS_COSIGNATURE_EVENT_OK = 'COSIGNATURE_EVENT_OK';
    const STATUS_COSIGNATURE_EVENT_PROCESSING = 'COSIGNATURE_EVENT_PROCESSING';
    const STATUS_COSIGNATURE_EVENT_CANCELLED = 'COSIGNATURE_EVENT_CANCELLED';
    const STATUS_COSIGNATURE_EVENT_PARTIAL_ERROR = 'COSIGNATURE_EVENT_PARTIAL_ERROR';

    /**
     * idDemand
     *
     * @var string
     */
    protected $idDemand;

    /**
     * fileInfos
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $fileInfos;

    /**
     * tokens
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $tokens;

    /**
     * dateCreation
     *
     * @var string
     */
    protected $dateCreation;

    /**
     * status
     *
     * @var string
     */
    protected $status;

    /**
     * cosignerInfos
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $cosignerInfos;

    /**
     * initiator
     *
     * @var Initiator
     */
    protected $initiator;

    /**
     * cosignatureEvent
     *
     * @var string
     */
    protected $cosignatureEvent;

    /**
     * Model relations
     *
     * @var array $subObjects
     */
    protected $subObjects = [
        'fileInfos' => 'Picoss\\YousignBundle\\Model\\File',
        'cosignerInfos' => 'Picoss\\YousignBundle\\Model\\Cosigner',
        'initiator' => 'Picoss\\YousignBundle\\Model\\Initiator',
        'tokens' => 'Picoss\\YousignBundle\\Model\\Token',
    ];

    /**
     * Get idDemand
     *
     * @return string
     */
    public function getIdDemand()
    {
        return $this->idDemand;
    }

    /**
     * Get fileInfos
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getFileInfos()
    {
        return $this->fileInfos;
    }

    /**
     * Get tokens
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * Get dateCreation
     *
     * @return string
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
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
     * Get cosignerInfos
     *
     * @return string
     */
    public function getCosignerInfos()
    {
        return $this->cosignerInfos;
    }

    /**
     * Get initiator
     *
     * @return string
     */
    public function getInitiator()
    {
        return $this->initiator;
    }

    /**
     * Get cosignatureEvent
     *
     * @return string
     */
    public function getCosignatureEvent()
    {
        return $this->cosignatureEvent;
    }
}