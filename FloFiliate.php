<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * @package   FloFiliate
 * @author    AlexanderC <self@alexanderc.me>
 * @license   GPL-2.0+
 * @link      http://flosites.com
 * @copyright 2013 FloSites LLC
 *
 * @wordpress-plugin
 * Plugin Name: FloFiliate
 * Plugin URI:  http://flosites.com
 * Description: FloFiliate integrates FloSites Affiliate application into multiple e-commerce platforms
 * Version:     0.0.1b
 * Author:      AlexanderC
 * Author URI:  http://flosites.com
 * Text Domain: flofiliate-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// TODO: replace `class-plugin-name.php` with the name of the actual plugin's class file
require_once( plugin_dir_path( __FILE__ ) . 'class-FloFiliate.php' );

// Load FloFiliate api library
require( plugin_dir_path( __FILE__ ) . '/vendor/flosites/api/autoload.php' );

// Register hooks that are fired when the plugin is activated or deactivated.
// When the plugin is deleted, the uninstall.php file is loaded.
// TODO: replace Plugin_Name with the name of the plugin defined in `class-plugin-name.php`
register_activation_hook( __FILE__, array( 'FloFiliate', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'FloFiliate', 'deactivate' ) );

// TODO: replace Plugin_Name with the name of the plugin defined in `class-plugin-name.php`
add_action( 'plugins_loaded', array( 'FloFiliate', 'get_instance' ) );

function flofiliate_api_key_notice(){
	if(is_admin()){
		echo '<div class="error"><p>Enter please the <a href="'.site_url( '/wp-admin/options-general.php?page=FloFiliate' ).'">API Key</a> for Flofilliate.</p></div>';	
	}	
}

if( !strlen(get_option( 'flofiliate_api_key' ) ) ){
    add_action( 'admin_notices', 'flofiliate_api_key_notice' ); 
}
