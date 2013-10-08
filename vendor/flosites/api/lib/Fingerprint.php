<?php
/**
 * @author AlexanderC
 */

namespace FloFilliate;

use phpbrowscap\Browscap;

class Fingerprint
{
    const DEFAULT_UA = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/536.30.1 (KHTML, like Gecko) Version/6.0.5 Safari/536.30.1';
    const DEFAULT_NS = 'default';

    /**
     * @var string
     */
    protected $fingerprint;

    /**
     * @var array
     */
    protected $capabilities;

    /**
     * @var string
     */
    protected $ns;

    /**
     * @param string $ns
     */
    public function __construct($ns = self::DEFAULT_NS)
    {
        $this->ns = (string)$ns;

        $this->dumpBrowserCapabilities();
        $this->dumpFingerprint();
    }

    /**
     * @param string $ip
     * @throws \InvalidArgumentException
     */
    public function regenerateUsingIp($ip)
    {
        if(!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException("An valid ip should be provided");
        }

        $tmpIp = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : null;
        $_SERVER['HTTP_CLIENT_IP'] = $ip;

        $this->dumpBrowserCapabilities();
        $this->dumpFingerprint();

        $_SERVER['HTTP_CLIENT_IP'] = $tmpIp;

        if(empty($_SERVER['HTTP_CLIENT_IP'])) {
            unset($_SERVER['HTTP_CLIENT_IP']);
        }
    }

    /**
     * @return void
     */
    protected function dumpFingerprint()
    {
        $uniques = array(
            $this->capabilities['browser_name'],
            'nan|nan|nan|nan',
            array(
                "RenderingEngine_Name.{$this->capabilities['RenderingEngine_Name']}"
            ),
            $this->capabilities['Platform']
        );

        if ($this->capabilities['Frames']) {
            $uniques[2][] = 'Frames.plugin';
        }

        if ($this->capabilities['IFrames']) {
            $uniques[2][] = 'IFrames.plugin';
        }

        if ($this->capabilities['Tables']) {
            $uniques[2][] = 'Tables.plugin';
        }

        if ($this->capabilities['JavaApplets']) {
            $uniques[2][] = 'JavaApplets.plugin';
        }

        if ($this->capabilities['ActiveXControls']) {
            $uniques[2][] = 'ActiveXControls.plugin';
        }

        if ($this->capabilities['VBScript']) {
            $uniques[2][] = 'VBScript.plugin';
        }

        $uniques[2] = implode("|", $uniques[2]);

        $fingerprint = "{$this->ns}.";

        foreach ($uniques as $info) {
            $fingerprint .= hash('sha256', $info);
            $fingerprint .= ".";
        }

        list($longIp, $ipInfo) = $this->getIpLong();

        // we store persistent browser uid
        $this->fingerprint = $fingerprint .
            sprintf(
                "%s.%s",
                $longIp,
                implode('', $ipInfo)
            );
    }

    /**
     * @return string
     */
    protected function getIpLong()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ip = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '127.0.0.1';
        }

        // we are not using ip2long due to ip.v6
        $long = '';
        $length = strlen($ip);
        $info = array();

        for ($i = 0; $i < $length; $i++) {
            $str = (string) ord($ip{$i});
            $info[] = strlen($str);
            $long .= $str;
        }

        return array($long, $info);
    }

    /**
     * @return void
     */
    protected function dumpBrowserCapabilities()
    {
        $cap = new Browscap(sys_get_temp_dir());

        $ua = empty($_SERVER['HTTP_USER_AGENT']) ? self::DEFAULT_UA : $_SERVER['HTTP_USER_AGENT'];
        $this->capabilities = $cap->getBrowser($ua, true);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->fingerprint;
    }
}