<?php
/**
 * @author AlexanderC
 */

namespace FloFilliate;


class Network
{
    /**
     * @var FloFilliate\Comm\IComm
     */
    protected $comm;

    /**
     * @var array
     */
    protected static $availableDrivers = array(
        'curl', 'native'
    );

    /**
     * @param null|string $driver
     */
    public function __construct($driver = null)
    {
        if (!$driver) {
            $driver = $this->guessDriver();
        }

        $commClass = $this->getCommClass($driver);
        $this->comm = new $commClass;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        return call_user_func_array(array($this->comm, $name), $arguments);
    }

    /**
     * @return array
     */
    public static function getAvailableDrivers()
    {
        return self::$availableDrivers;
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    protected function guessDriver()
    {
        if (function_exists('curl_version')) {
            return 'curl';
        } else {
            return 'native';
        }
    }

    /**
     * @param string $driver
     * @return string
     * @throws \BadMethodCallException
     */
    protected function getCommClass($driver)
    {
        $driver = lcfirst($driver);

        if (!in_array($driver, self::getAvailableDrivers())) {
            throw new \BadMethodCallException("Invalid driver {$driver} provided");
        }

        return __NAMESPACE__ . "\\Comm\\" . ucfirst($driver) . "Comm";
    }
}