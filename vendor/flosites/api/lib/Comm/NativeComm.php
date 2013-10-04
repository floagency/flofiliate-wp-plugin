<?php
/**
 * @author AlexanderC
 */

namespace FloFilliate\Comm;

use FloFilliate\Comm\Exception\FailedToGetResourceException;

class NativeComm implements IComm
{
    /**
     * @throws \RuntimeException
     */
    public function __construct()
    {
        if (!ini_get('allow_url_fopen')) {
            throw new \RuntimeException("Unable to use native driver. allow_url_fopen should be true");
        }
    }

    /**
     * @param string $url
     * @return string
     * @throws FloFilliate\Comm\Exception\FailedToGetResourceException
     */
    public function get($url)
    {
        $result = @file_get_contents($url);

        if (false === $result) {
            throw new FailedToGetResourceException("Failed to get resource using native comm");
        }

        return $result;
    }
}