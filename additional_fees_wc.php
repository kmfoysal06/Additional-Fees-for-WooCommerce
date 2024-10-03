<?php
/**
 * Plugin Name: Additional Fees for WooCommerce
 * Author: kmfoysal06
 * Version: 1.0
 * text-domain: additional-fees-wc
 */

require 'vendor/autoload.php';




if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option("active_plugins")))) {
    return;
}

if(function_exists('additional_fees_wc_init')){
	add_action("plugin_loaded", "additional_fees_wc_init");
}


function additional_fees_wc_init(){
	if(class_exists('src\\classes\\AdditionalFeesWc')){
		return \src\classes\AdditionalFeesWc::get_instance();
	}
}
