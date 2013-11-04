<?php
/**
 * @author AlexanderG
 */

class FloFiliate_Cart66Detector extends FloFiliate_IDetector
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
        
        // 
        add_action('cart66_after_order_saved', array($this, 'saveFloAffCode'));
    }

    /**
     * @return void
     */
    public function saveFloAffCode($orderInfo){
        if(isset($_COOKIE['flo_affiliate_code'])){  
            // save the cookie in transiend to make it available after we get back from the Gateway site
            set_transient( 'flo_affiliate_code_'.$orderInfo['id'], $_COOKIE['flo_affiliate_code'], 60*60 ); // save it for 1 hour

        }
        if(isset($_SESSION['flo_user_ip'])){
            // save user IP in the transient to make it available after we come back from the gateway
            set_transient( 'flo_affiliate_user_ip'.$orderInfo['id'], $_SESSION['flo_user_ip'], 60*60 ); // save it for 1 hour            
        }
        
    }

    /**
     * @return void
     */
    public function register()
    {
        //add_action('cart66_after_order_saved', array($this, 'apply'));

        // this is not an official hook, in order to make it work, add in 
        // cart66/gateways/Cart66PayPalStandard.php   after // End iDevAffiliate Tracking  this:

        //do_action('cart66_after_storePendingOrder', $orderInfo);    

        add_action('cart66_after_storePendingOrder', array($this, 'apply'));
        

        
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        if(class_exists('Cart66')){
            return true;    
        }
    }

    /**
     * @param string $code
     * @param string $trackId
     * @return void
     */
    public function dispatch($code, $trackId)
    { 
        
        //Cart66::loadCoreModels(); // load cart66 core models

        setcookie("flo_affiliate_code", $trackId, time()+(3600*24*30), SITECOOKIEPATH  );  /* expire in 30 days */
        
        // automatically aplly the coupon
        //Cart66Session::get('Cart66Cart')->applyPromotion(strtoupper($code), true);
    }

    /**
     * @return string
     */
    public function __toString()
    {
            
        return "Cart66 version ". CART66_VERSION_NUMBER;
        
    }

    /** Other methods */

    /**
     * @param obj $order
     */
    public function apply($order)
    {  

        if( isset($order->id) && is_numeric($order->id) && $order->id > 0){
            

            // get the affiliate code from the cookies
            if ( false !== ( $trackId = get_transient( 'flo_affiliate_code_'.$order->id ) ) ) {
            $ex = "";
                try {

                        // check if the User IP is available in the transient
                        if ( false !== ( $flo_affiliate_user_ip = get_transient( 'flo_affiliate_user_ip'.$order->id ) ) ) {
                            $this->manager->getApi()->getFingerprint()->regenerateUsingIp($flo_affiliate_user_ip);
                                // public function regenerateUsingIpAndUA($ip, $ua)
                                /* UA- user agent
                                   $_SERVER['HTTP_USER_AGENT'] */

                        }
                        
                            $this->manager->getApi()->push($trackId);
                } catch(\Exception $e) {
                        $ex = $e->getMessage();
                }
                
                //unset($_COOKIE['flo_affiliate_code']);   // should we ?
            }
        }
        
        
        
    }
}