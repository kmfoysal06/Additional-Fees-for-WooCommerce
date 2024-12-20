<?php
/**
 * @package Additional Fees for WooCommerce
 * 
 * Plugin Name: Additional Fees for WooCommerce
 * Author: kmfoysal06
 * Version: 1.2
 * Stable tag: 1.2
 * text-domain: additional-fees-for-woocommerce
 * Description: Setting Up Additional Fee For WooCommerce
 * Author: kmfoysal06
 * Author URI: https://profiles.wordpress.org/kmfoysal06
 * Tags: woocommerce-extra-fee, woocommerce-additional-fee, woocommerce-extra-tax, woocommerce-additional-tax, additional-fee-for-payment-methods
 * Requires at least: 5.0
 * Tested up to: 6.6
 * Requires PHP: 7.0
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if(!defined('ABSPATH')) exit;

require 'vendor/autoload.php';

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option("active_plugins")))) {
    return;
}

if(function_exists('additional_fees_for_woocommerce_init')){
	add_action("plugin_loaded", "additional_fees_for_woocommerce_init");
}

if (!defined("AFFW_DIR_PATH")) {
    define("AFFW_DIR_PATH", untrailingslashit(plugin_dir_path(__FILE__)));
}
if (!defined("AFFW_DIR_URI")) {
    define("AFFW_DIR_URI", untrailingslashit(plugin_dir_url(__FILE__)));
}

/**
 * Instantiating The AdditionalFeesWc Class if The Plugin Loaded and Using Singleton to Avoid Repeating The Class Instace
 * 
 */

function additional_fees_for_woocommerce_init(){
	if(class_exists('affw\\src\\classes\\AdditionalFeesWc')){
		return \affw\src\classes\AdditionalFeesWc::get_instance();
	}
}
