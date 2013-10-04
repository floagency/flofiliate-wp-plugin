<?php
/**
 * @author AlexanderC
 */

class FloFiliate_WooCommerceDetector extends FloFiliate_IDetector
{
    /**
     * @var Woocommerce
     */
    protected $shop;

    /**
     * @return void
     */
    public function init()
    {
        if(class_exists('Woocommerce')
            && isset($GLOBALS['woocommerce'])
            && $GLOBALS['woocommerce'] instanceof Woocommerce) {
            $this->shop = $GLOBALS['woocommerce'];
        }
    }

    /**
     * @return void
     */
    public function register()
    {
        //add_action('woocommerce_payment_complete', array($this, 'apply'));

        if(is_admin()) {
            //add_action('woocommerce_order_status_completed', array($this, 'apply'));
        }
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return $this->shop instanceof Woocommerce;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "WooCommerce version {$this->shop->version} (not working)";
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

    /** Other methods */

    /**
     * @param int $order_id
     */
    public function apply($order_id)
    {
        // This must still tested and reviewd    
        // get the affiliate code from the cookies
        if(isset($_COOKIE['flo_affiliate_code'])){
            $promo_code = array($_COOKIE['flo_affiliate_code']); 
            
            $this->manager->pushCodes($promo_code);

            unset($_COOKIE['flo_affiliate_code']);
        }
    }
}
