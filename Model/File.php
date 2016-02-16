<?php

namespace Picoss\YousignBundle\Model;

class File extends ModelBase
{
    /**
     * idFile
     *
     * @var string
     */
    protected $idFile;

    /**
     * fileName
     *
     * @var string
     */
    protected $fileName;

    /**
     * file
     *
     * @var string
     */
    protected $file;

    /**
     * name
     *
     * @var string
     */
    protected $name;

    /**
     * sha1
     *
     * @var string
     */
    protected $sha1;

    /**
     * content
     *
     * @var string
     */
    protected $content;

    /**
     * pdfPassword
     *
     * @var string
     */
    protected $pdfPassword;

    /**
     * iua
     *
     * @var string
     */
    protected $iua;

    /**
     * cosignersWithStatus
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $cosignersWithStatus;

    /**
     * Model relations
     *
     * @var array $subObjects
     */
    protected $subObjects = [
        'cosignersWithStatus' => 'Picoss\\YousignBundle\\Model\\Cosigner',
    ];

    public function toArray()
    {
        return array(
            'idFile' => $this->idFile,
            'name' => $this->name,
            'content' => $this->content,
        );
    }

    /**
     * Set idFile
     *
     * @var string $idFile
     * @return File
     */
    public function setIdFile($idFile)
    {
        $this->idFile = $idFile;

        return $this;
    }

    /**
     * Get idFile
     *
     * @return string
     */
    public function getIdFile()
    {
        return $this->idFile;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set name
     *
     * @var string $name
     * @return File
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

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
     * Get sha1
     *
     * @return string
     */
    public function getSha1()
    {
        return $this->sha1;
    }

    /**
     * Set content
     *
     * @var string $content
     * @return File
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set pdfPassword
     *
     * @var string $pdfPassword
     * @return File
     */
    public function setPdfPassword($pdfPassword)
    {
        $this->pdfPassword = $pdfPassword;

        return $this;
    }

    /**
     * Get pdfPassword
     *
     * @return string
     */
    public function getPdfPassword()
    {
        return $this->pdfPassword;
    }

    /**
     * Get iua
     *
     * @return string
     */
    public function getIua()
    {
        return $this->iua;
    }

    /**
     * Get cosignersWithStatus
     *
     * @return string
     */
    public function getCosignersWithStatus()
    {
        return $this->cosignersWithStatus;
    }
}