<?php
/**
 * @author AlexanderC
 */

abstract class FloFiliate_IDetector
{
    /**
     * @var FloFiliate_Manager
     */
    protected $manager;

    /**
     * @param FloFiliate_Manager $manager
     */
    final public function __construct(FloFiliate_Manager $manager)
    {
        $this->manager = $manager;
        $this->init();
    }

    /**
     * Do some actions after class constructor
     * finished to do their tasks
     *
     * @return void
     */
    abstract function init();

    /**
     * Check if plugin/module is available and actiavtd
     *
     * @return bool
     */
    abstract public function isAvailable();

    /**
     * @return void
     */
    abstract public function register();

    /**
     * @param string $code
     * @param string $trackId
     * @return void
     */
    abstract public function dispatch($code, $trackId);

    /**
     * @return string
     */
    abstract public function __toString();
}