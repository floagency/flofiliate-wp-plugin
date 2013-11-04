<?php
/**
 * @author AlexanderC
 */

date_default_timezone_set('Europe/Chisinau');

require __DIR__ . "/autoload.php";

/*$api = new \FloFilliate\Api(
    new \FloFilliate\Fingerprint(),
    new \FloFilliate\Network('native'),
    "http://localhost/app_dev.php/en/api"
);*/

$api = \FloFilliate\Api::create("http://localhost/app_dev.php/en/api");

// push implicit for testing
$_REQUEST[\FloFilliate\Api::PROMO_CODE_KEY] = '1412mkl';
$_REQUEST[\FloFilliate\Api::TRACK_ID_KEY] = sprintf("%s-%s", 0x003, 1);

echo "Initial:   ", $api->getFingerprint(), "\n";
$api->getFingerprint()->regenerateUsingIp('217.12.117.138');
echo "Ip:        ", $api->getFingerprint(), "\n";
$api->getFingerprint()->regenerateUsingIpAndUA(
    '217.12.117.138',
    'NokiaC5-00/061.005 (SymbianOS/9.3; U; Series60/3.2 Mozilla/5.0; Profile/MIDP-2.1 Configuration/CLDC-1.1) AppleWebKit/525 (KHTML, like Gecko) Version/3.0 Safari/525 3gpp-gba'
);
echo "Ip and UA: ", $api->getFingerprint(), "\n";
//exit();

if (\FloFilliate\Api::hasPromoCodeAndTrackId()) {
    list($promoCode, $trackId) = \FloFilliate\Api::listPromoCodeAndTrackId();

    echo "You promo code is: {$promoCode}\n", "You track id is: {$trackId}\n";

    // now we gonna get result
    try {
        var_dump($api->push($trackId, 50 /* minus 50% of points */, "http://example.com/my-callback-url", array('orderId' => 33)));
        var_dump($api->delete('9d12e035f87f97107431ffd09a90ee0c86caafe5', '17'));
    } catch (\Exception $e) {
        echo "Exception thrown: ", $e->getMessage(), "\n";
    }
} else {
    echo "Promo Code And Track Id Not Found...\n";
}
