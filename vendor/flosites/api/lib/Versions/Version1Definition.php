<?php
/**
 * @author AlexanderC
 */

namespace FloFilliate\Versions;


interface Version1Definition
{
    const VERSION = '1.0b';

    const PROMO_CODE_KEY = "pc";
    const TRACK_ID_KEY = "tsic";
    const UID_KEY = "uid";
    const ADD_FLAGS = "f=1";

    const PUSH_PART = "/push";

    const DECODER_CLASS = "FloFilliate\\Decoder\\ApiV1Decode";

    const TRACK_ID_REGEX = "/^(\d+)-(\d+)$/ui";
}