<?php
/**
 * @author AlexanderC
 */

namespace FloFilliate\Comm;


use FloFilliate\Comm\Exception\FailedToGetResourceException;

class CurlComm implements IComm
{
    /**
     * @throws \RuntimeException
     */
    public function __construct()
    {
        if (!function_exists('curl_version')) {
            throw new \RuntimeException("Unable to use curl driver. Curl extension should be loaded");
        }
    }

    /**
     * @param string $url
     * @return string
     * @throws FloFilliate\Comm\Exception\FailedToGetResourceException
     */
    public function get($url)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new FailedToGetResourceException("Failed to get resource using curl comm [" . curl_error($curl) . "]");
        }

        curl_close($curl);

        return $output;
    }
}