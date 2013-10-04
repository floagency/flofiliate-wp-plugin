<?php
/**
 * @author AlexanderC
 */

namespace FloFilliate\Decoder;

interface IDecode
{
    /**
     * @param string $raw
     * @return array
     * @throws \FloFilliate\Decoder\Exception\UnableToDecodeException
     */
    public function decode($raw);
}