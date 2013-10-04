<?php
/**
 * @author AlexanderC
 */

namespace FloFilliate\Comm;


interface IComm
{
    /**
     * @param string $url
     * @return string
     * @throws FloFilliate\Comm\Exception\FailedToGetResourceException
     */
    public function get($url);
}