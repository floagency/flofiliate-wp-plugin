<?php
/**
 * @author AlexanderC
 */

namespace FloFilliate;

use FloFilliate\Versions\Version1Definition;

class Api implements Version1Definition
{
    /**
     * @var Fingerprint
     */
    protected $fingerprint;

    /**
     * @var Network
     */
    protected $network;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var Decoder\IDecode
     */
    protected $decoder;

    /**
     * @var string
     */
    protected $apiKey = "_";

    /**
     * @param Fingerprint $fingerprint
     * @param Network $network
     * @param string $apiUrl
     * @throws \BadMethodCallException
     */
    public function __construct(Fingerprint $fingerprint, Network $network, $apiUrl)
    {
        if (!filter_var($apiUrl, FILTER_VALIDATE_URL)) {
            throw new \BadMethodCallException("You must provide an valid api url");
        }

        $this->apiUrl = rtrim($apiUrl, "/");
        $this->fingerprint = $fingerprint;
        $this->network = $network;

        $decoderClass = self::DECODER_CLASS;
        $this->decoder = new $decoderClass;
    }

    /**
     * @param string $key
     */
    public function setApiKey($key)
    {
        $this->apiKey = $key;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiUrl
     * @return Api
     */
    public static function create($apiUrl)
    {
        $fingerprint = new Fingerprint();
        $network = new Network();

        return new self($fingerprint, $network, $apiUrl);
    }

    /**
     * @param string $sKey
     * @param string $id
     * @return array
     */
    public function delete($sKey, $id)
    {
        $delimiter = false === strpos($this->apiUrl, "?") ? "?" : "&";

        $url = sprintf(
            "{$this->apiUrl}%s{$delimiter}" .
            self::ID_KEY . "=%s&" .
            self::SKEY_KEY . "=%s&",
            self::DELETE_PART,
            $id,
            $sKey
        );

        return $this->decoder->decode($this->network->get($url));
    }

    /**
     * @param string $trackId           Track id received
     * @param int $total                Total amount of money of products the user bought
     * @param int $pDiff                Points difference percentage (-n% from the points set)
     * @param bool $callbackUrl         Callback to be called after data persisted into the system
     * @param array $additionalData     Additional query data to be returned to the callback url
     * @return array
     * @throws \BadMethodCallException
     *
     * Note: Total has priority to the pdiff parameter.
     *          So the % from the total is set for promo code
     *          instead of setting fixed amount of points assigned(- pdiff if set)
     */
    public function push($trackId, $total = -1, $pDiff = 0, $callbackUrl = false, array $additionalData = null)
    {
        if (!self::validateTrackId($trackId)) {
            throw new \BadMethodCallException("Invalid Track Id provided");
        }

        $delimiter = false === strpos($this->apiUrl, "?") ? "?" : "&";

        $append = "";
        if(filter_var($callbackUrl, FILTER_VALIDATE_URL)) {
            $append .= sprintf("&%s=%s", self::CU_KEY, urlencode($callbackUrl));
        }

        if($additionalData) {
            foreach($additionalData as $k => $v) {
                $append .= "&{$k}=" . urlencode($v);
            }
        }

        $url = sprintf(
            "{$this->apiUrl}%s{$delimiter}" .
            self::UID_KEY . "=%s&" .
            self::TRACK_ID_KEY . "=%s&" .
            self::PDIFF_KEY . "=%s" .
            self::SUMM_KEY . "=%s" .
            self::API_KEY_KEY . "=%s" .
            "%s&" .
            self::ADD_FLAGS,

            self::PUSH_PART,
            $this->fingerprint,
            $trackId,
            (int) $pDiff,
            (int) $total,
            urlencode($this->apiKey),
            $append
        );

        return $this->decoder->decode($this->network->get($url));
    }

    /**
     * @param bool $request
     * @return bool
     */
    public static function hasTrackId($request = false)
    {
        return self::validateTrackId(self::requestTrackId($request));
    }

    /**
     * @param bool $request
     * @return bool
     */
    public static function hasPromoCodeAndTrackId($request = false)
    {
        list($promoCode, $trackId) = self::listPromoCodeAndTrackId($request);

        return isset($promoCode, $trackId) && self::validateTrackId($trackId);
    }

    /**
     * @param bool $request
     * @return array
     */
    public static function listPromoCodeAndTrackId($request = false)
    {
        return array(
            self::requestPromoCode($request),
            self::requestTrackId($request)
        );
    }

    /**
     * @param bool $request
     * @return null
     */
    public static function requestTrackId($request = false)
    {
        $request = $request ? : $_REQUEST;

        return isset($request[self::TRACK_ID_KEY])
            ? $request[self::TRACK_ID_KEY]
            : null;
    }

    /**
     * @param bool $request
     * @return null
     */
    public static function requestPromoCode($request = false)
    {
        $request = $request ? : $_REQUEST;

        return isset($request[self::PROMO_CODE_KEY])
            ? $request[self::PROMO_CODE_KEY]
            : null;
    }

    /**
     * @param string $trackId
     * @return int
     */
    protected static function validateTrackId($trackId)
    {
        return preg_match(self::TRACK_ID_REGEX, $trackId);
    }

    /**
     * @return Fingerprint
     */
    public function getFingerprint()
    {
        return $this->fingerprint;
    }
}