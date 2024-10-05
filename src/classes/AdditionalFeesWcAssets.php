<?php
/**
 * All Assets for Additional Fees for WooCommerce
 * @package Additional Fees for WooCommerce
 * 
 */
namespace affw\src\classes;

if(!defined('ABSPATH')) exit;

use affw\src\traits\Singleton;

class AdditionalFeesWcAssets{
	use Singleton;
	public function __construct(){
		add_action('wp_footer', [$this, 'additional_fees_for_wc_reload_checkout']);
		add_action("wp_enqueue_scripts", [$this, 'additional_fees_for_wc_assets']);
	}

	/**
	 * Each Time User Change The Payment Method The 
	 * Checkout Section Should Be Refreshed
	 *  
	 */
	
	public function additional_fees_for_wc_reload_checkout(){
		if(is_checkout() && !is_wc_endpoint_url()){
			?>
			
			<?php
		}
	}

	public function additional_fees_for_wc_assets(){
		wp_register_script('additional-fees-for-woocommerce', AFFW_DIR_URI . '/assets/js/refresh-checkout.js', ['jquery'], filemtime(AFFW_DIR_PATH . '/assets/js/refresh-checkout.js'), true);

		if(is_checkout() && !is_wc_endpoint_url()){
			wp_enqueue_script('additional-fees-for-woocommerce');
		}
	}

	}