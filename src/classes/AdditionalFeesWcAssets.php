<?php
/**
 * All Assets for Additional Fees for WooCommerce
 * @package Additional Fees for WooCommerce
 * 
 */
namespace src\classes;
use src\traits\Singleton;

class AdditionalFeesWcAssets{
	use Singleton;
	public function __construct(){
		add_action('wp_footer', [$this, 'additional_fees_for_wc_reload_checkout']);
	}

	/**
	 * Each Time User Change The Payment Method The 
	 * Checkout Section Should Be Refreshed
	 *  
	 */
	
	public function additional_fees_for_wc_reload_checkout(){
		if(is_checkout() && !is_wc_endpoint_url()){
			?>
			<script>
				jQuery( function($){
					$('form.checkout').on('change', 'input[name=payment_method]', function(){
						$(document.body).trigger('update_checkout');
					})
				} )
			</script>
			<?php
		}
	}

	}