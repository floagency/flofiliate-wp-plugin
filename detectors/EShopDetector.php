<?php
/**
 * @author AlexanderC
 */

class FloFiliate_EShopDetector extends FloFiliate_IDetector
{
    /**
     * @return void
     */
    public function init()
    {   }

    /**
     * @return void
     */
    public function register()
    {
        if(is_admin()) {
            // still need to check if this works
            //add_action('eshop_order_status_updated', array($this, 'apply'));
        }
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return defined('ESHOP_VERSION');
    }

    /**
     * @param string $code
     * @param string $trackId
     * @return void
     */
    public function dispatch($code, $trackId)
    {
        // if affiliate code is detected in the URL then we create a cookie with that value
        //setcookie("flo_affiliate_code", $code, time()+(3600*24*30), SITECOOKIEPATH  );  // expire in 30 days 
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "eShop version " . ESHOP_VERSION . ' (not working)';
    }

    /** Other methods */


    public function apply()
    {
        // get the affiliate code from the cookies
        if(isset($_COOKIE['flo_affiliate_code'])){
            $promo_code = array($_COOKIE['flo_affiliate_code']); 
            
            $this->manager->pushCodes($promo_code);

            unset($_COOKIE['flo_affiliate_code']);
        }
    }
}
