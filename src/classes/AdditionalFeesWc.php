<?php
/**
 * Main Class for Additional Fees for WooCommerce
 * @package Additional Fees for WooCommerce
 * 
 */
namespace affw\src\classes;

if(!defined('ABSPATH')) exit;

use affw\src\traits\Singleton;

class AdditionalFeesWc{
	use Singleton;
	public function __construct(){
		add_action("woocommerce_cart_calculate_fees",[$this, 'payment_gateways_fees']);

		add_action("woocommerce_cart_calculate_fees",[$this, 'additional_fees_wc']);

		add_action("wp_loaded", [$this, 'AdditionalFeesWcInit']);
	}

	public function AdditionalFeesWcInit(){
		AdditionalFeesWcTab::get_instance();
		AdditionalFeesWcGateway::get_instance();
		AdditionalFeesWcAssets::get_instance();
	}

	/**
	 * Setting Additional Fee With The Data That Been 
	 * Set By User in WooCommerce Setting Extra Fee Tab
	 * 
	 */

	public function additional_fees_wc(){
		global $woocommerce;

		$flate_vat = !empty(get_option('additional_fees_for_wc_payment_gateway_static')) ? get_option('additional_fees_for_wc_payment_gateway_static') : 0;

		$dynamic_vat = !empty(get_option('additional_fees_for_wc_payment_gateway_dynamic')) ? get_option('additional_fees_for_wc_payment_gateway_dynamic') : 0;

		$get_dynamic_vat = $woocommerce->cart->cart_contents_total * ( $dynamic_vat / 100 );

		$total_vat = $flate_vat + $get_dynamic_vat;

		if($total_vat > 0){
			$fee_label = sprintf(
		    /* translators: 1: Flate Fee, 2: Vat Percentage */
		    __("Flat Fee + Vat ( %1\$s + %2\$s%% )", 'additional-fees-for-woocommerce'),
		    $flate_vat,
		    $dynamic_vat
		);

			$woocommerce->cart->add_fee($fee_label, $total_vat);
	}
}

	/**
	 * Getting Payment Method Details and And Forward 
	 * Them to add_fee_for_payment_gateways method
	 *  
	 * @see add_fee_for_payment_gateways
	 * @see get_payment_gateway_title
	 * 
	 */

	public function payment_gateways_fees(){
		global $woocommerce;

        $chosen_payment_method = WC()->session->get('chosen_payment_method');

		$gateway_title = $this->get_payment_gateway_title($chosen_payment_method);

		$this->add_fee_for_payment_gateways($chosen_payment_method, $gateway_title);
	}

	/**
	 * Setting Additional Fee for Different Payment 
	 * Methods With The Data That Been Set By User in 
	 * WooCommerce Setting Extra Fee Tab
	 * 
	 */

	public function add_fee_for_payment_gateways($payment_method,$payment_method_title){
			global $woocommerce;

            $flate_vat = !empty(get_option('additional_fees_for_wc_payment_gateway_'.$payment_method.'_static')) ? get_option('additional_fees_for_wc_payment_gateway_'.$payment_method.'_static') : 0;
            
            $dynamic_vat = !empty(get_option('additional_fees_for_wc_payment_gateway_'.$payment_method.'_dynamic')) ? get_option('additional_fees_for_wc_payment_gateway_'.$payment_method.'_dynamic') : 0;


			$get_dynamic_vat = $woocommerce->cart->cart_contents_total * ( $dynamic_vat / 100 );

			$total_vat = $flate_vat + $get_dynamic_vat;

            if($total_vat > 0){
            	$fee_label = sprintf(
			    /* translators: 1: Flate Fee, 2: Vat Percentage, 3: Payment Gateway Title */
			    __("Flat Fee + Vat ( %1\$s + %2\$s%% ) for %3\$s", 'additional-fees-for-woocommerce'),
			    $flate_vat,
			    $dynamic_vat,
			    $payment_method_title
			);

                $woocommerce->cart->add_fee($fee_label, $total_vat);
            }
		}

		/**
		 * Getting Payment Gateway Title
		 * @param $gateway_id
		 * @return string
		 */

		public function get_payment_gateway_title($gateway_id){
			$payment_gateways = WC()->payment_gateways->payment_gateways;
			foreach($payment_gateways as $payment_gateway){
				if($payment_gateway->id == $gateway_id){
					return $payment_gateway->settings['title'];
				}
			}
		}



	}