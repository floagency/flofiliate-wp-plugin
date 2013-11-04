<?php
/**
 * @author AlexanderC
 */

require_once __DIR__ . '/detectors/IDetector.php';

class FloFiliate_Manager
{
    /**
     * @var array
     */
    protected $detectors = array();

    /**
     * @var FloFilliate\Api
     */
    protected $api;

    /**
     * @var array
     */
    protected $storage;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->bootstrap();
        $this->detectEnvironment();

        $apiUrl = get_option('flofiliate_api_url');

        if(!empty($apiUrl)) {
            @session_start();

            if(!isset($_SESSION['__flofiliate_manager_storage'])) {
                $_SESSION['__flofiliate_manager_storage'] = array();
            }
            $this->storage = & $_SESSION['__flofiliate_manager_storage'];

            // init api
            $this->api = \FloFilliate\Api::create($apiUrl);

            if( strlen(get_option( 'flofiliate_api_key' ) ) ){
                $this->api->setApiKey( get_option( 'flofiliate_api_key' ) );                
            }
            
            if(!empty($this->detectors)) {
                $this->dispatchRequest();
            }
        }
    }

    /**
     * @param array $codes
     */
    public function pushCodes(array $codes)
    {
        if(count($codes) > 0) {
            foreach($codes as $code) {
                if(isset($this->storage[$code])) {
                    $trackId = $this->storage[$code];
                    $this->api->push($trackId);
                }
            }
        }
    }

    /**
     * @return void
     */
    public function dispatchRequest()
    {
        if (\FloFilliate\Api::hasPromoCodeAndTrackId()) {
            list($promoCode, $trackId) = \FloFilliate\Api::listPromoCodeAndTrackId();

            $this->storage[$promoCode] = $trackId;

            /** @var FloFiliate_IDetector $detector */
            foreach($this->detectors as $detector) {
                $detector->dispatch($promoCode, $trackId);
            }
        }
    }

    /**
     * @return void
     */
    public function register()
    {
        /** @var FloFiliate_IDetector $detector */
        foreach($this->detectors as $detector) {
            $detector->register();
        }
    }

    /**
     * @return array
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @return \FloFilliate\Api
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @return array
     */
    public function getDetectors()
    {
        return $this->detectors;
    }

    /**
     * @return void
     */
    protected function detectEnvironment()
    {
        /** @var FloFiliate_IDetector $detector */
        foreach($this->detectors as $key => $detector) {
            if(!$detector->isAvailable()) {
                unset($this->detectors[$key]);
            }
        }
    }

    /**
     * @return void
     */
    protected function bootstrap()
    {
        $dir = __DIR__ . '/detectors';

        $directory = new RecursiveDirectoryIterator($dir);
        $regexIterator = new RecursiveRegexIterator($directory, '/[a-zA-Z]\w+Detector\.php/');
        $recursiveIterator = new RecursiveIteratorIterator($regexIterator);

        foreach($recursiveIterator as $file){
            require_once $file;

            $class = "FloFiliate_" . substr(basename($file), 0, -4);

            if(class_exists($class)) {
                $detector = new $class($this);

                if($detector instanceof FloFiliate_IDetector) {
                    $this->detectors[] = $detector;
                } else {
                    exit(
                        "Detector {$class} should implement FloFiliate_IDetector interface (already loaded " .
                        (empty($this->detectors) ? 'NONE' : implode(', ', $this->detectors))
                        . ")"
                    );
                }
            }
        }
    }
}