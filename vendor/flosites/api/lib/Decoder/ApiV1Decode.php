<?php
/**
 * @author AlexanderC
 */

namespace FloFilliate\Decoder;


use FloFilliate\Decoder\Exception\UnableToDecodeException;

class ApiV1Decode implements IDecode
{
    /**
     * @param string $raw
     * @return array
     * @throws \FloFilliate\Decoder\Exception\UnableToDecodeException
     */
    public function decode($raw)
    {
        $result = @json_decode($raw, true);

        if (false === $result) {
            throw new UnableToDecodeException("Seems like result is not a json string");
        }

        return $result;
    }
}