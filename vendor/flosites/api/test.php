<?php
/**
 * @author AlexanderC
 */

date_default_timezone_set('Europe/Chisinau');

require __DIR__ . "/autoload.php";

/*$api = new \FloFilliate\Api(
    new \FloFilliate\Fingerprint(),
    new \FloFilliate\Network('native'),
    "http://localhost/app_dev.php/api"
);*/

$api = \FloFilliate\Api::create("http://localhost/app_dev.php/en/api");

// push implicit for testing
$_REQUEST[\FloFilliate\Api::PROMO_CODE_KEY] = '1412mkl';
$_REQUEST[\FloFilliate\Api::TRACK_ID_KEY] = sprintf("%s-%s", 0x003, 1);

if (\FloFilliate\Api::hasPromoCodeAndTrackId()) {
    list($promoCode, $trackId) = \FloFilliate\Api::listPromoCodeAndTrackId();

    echo "You promo code is: {$promoCode}\n", "You track id is: {$trackId}\n";

    // now we gonna get result
    try {
        var_dump($api->push($trackId));
    } catch (\Exception $e) {
        echo "Exception thrown: ", $e->getMessage(), "\n";
    }
} else {
    echo "Promo Code And Track Id Not Found...\n";
}
