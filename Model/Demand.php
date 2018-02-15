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

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Demand
 *
 * @author Romain Honel <romain.honel@gmail.com>
 */
class Demand
{
    /**
     * @var array
     */
    private $files = [];

    /**
     * @var array
     */
    private $cosigners = [];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var OptionsResolver
     */
    private $visibleOptionsResolver;

    /**
     * Demand constructor.
     */
    public function __construct()
    {
        $this->visibleOptionsResolver = new OptionsResolver();
        $this->configureVisibleOptions($this->visibleOptionsResolver);

        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    /**
     * Add file
     *
     * @param File  $file
     * @param array $options
     *
     * @return Demand
     */
    public function addFile(File $file, array $options = [])
    {
        $id = spl_object_hash($file);
        if (!isset($this->files[$id])) {
            $this->files[$id] = [
                'file' => $file,
                'options' => $this->visibleOptionsResolver->resolve($options),
            ];
        }

        return $this;
    }

    /**
     * Get files
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add cosigner
     *
     * @param Cosigner $cosigner
     *
     * @return Demand
     */
    public function addCosigner(Cosigner $cosigner)
    {
        if (!in_array($cosigner, $this->cosigners)) {
            $this->cosigners[] = $cosigner;
        }

        return $this;
    }

    /**
     * Get cosigners
     *
     * @return array
     */
    public function getCosigners()
    {
        return $this->cosigners;
    }

    /**
     * Set demand options
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $this->visibleOptionsResolver->resolve($options);

        return $this;
    }

    /**
     * Get demand options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Configure signature demand options
     *
     * @param OptionsResolver $resolver
     */
    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'mode' => null,
                'archive' => false,
                'language' => 'EN',
            ])
            ->setDefined([
                'message',
                'title',
                'initMailSubject',
                'initMail',
                'endMailSubject',
                'endMail',
                'language',
            ])
            ->setAllowedValues('mode', [null, 'IFRAME'])
            ->setAllowedValues('language', ['FR', 'EN', 'DE'])
        ;
    }

    /**
     * Configure signature visible options
     *
     * @param OptionsResolver $resolver
     */
    private function configureVisibleOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'isVisibleSignature' => true,
            ])
            ->setRequired([
                'visibleSignaturePage',
                'visibleRectangleSignature',
            ])
        ;
    }
}