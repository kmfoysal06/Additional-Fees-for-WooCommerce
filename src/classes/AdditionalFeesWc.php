<?php
/**
 * Main Class for Additional Fees for WooCommerce
 * @package Additional Fees for WooCommerce
 */
namespace src\classes;
use src\traits\Singleton;

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

	public function additional_fees_wc(){
		global $woocommerce;
		$flat_vat = !empty(get_option('additional_fees_for_wc_payment_gateway_static')) ? get_option('additional_fees_for_wc_payment_gateway_static') : 0;

		$dynamic_vat = !empty(get_option('additional_fees_for_wc_payment_gateway_dynamic')) ? get_option('additional_fees_for_wc_payment_gateway_dynamic') : 0;
		$get_dynamic_vat = $woocommerce->cart->cart_contents_total * ( $dynamic_vat / 100 );

		$total_vat = $flat_vat + $get_dynamic_vat;
		if($total_vat > 0){
			$woocommerce->cart->add_fee(esc_html__("Flat + Vat ( ".$flat_vat." + ". $dynamic_vat ."% ) ", 'additional-fees-for-wc'), $total_vat);
		}
	}

	public function payment_gateways_fees(){
		global $woocommerce;
        $chosen_payment_method = WC()->session->get('chosen_payment_method');
			$gateway_title = $this->get_payment_gateway_title($chosen_payment_method);

			$this->add_fee_for_payment_gateways($chosen_payment_method, $gateway_title);
		}

		public function add_fee_for_payment_gateways($payment_method,$payment_method_title){
			global $woocommerce;
            $flate_vat = !empty(get_option('additional_fees_for_wc_payment_gateway_'.$payment_method.'_static')) ? get_option('additional_fees_for_wc_payment_gateway_'.$payment_method.'_static') : 0;
            
            $dynamic_vat = !empty(get_option('additional_fees_for_wc_payment_gateway_'.$payment_method.'_dynamic')) ? get_option('additional_fees_for_wc_payment_gateway_'.$payment_method.'_dynamic') : 0;


			$get_dynamic_vat = $woocommerce->cart->cart_contents_total * ( $dynamic_vat / 100 );

			$total_vat = $flate_vat + $get_dynamic_vat;

            if($total_vat > 0){
                $woocommerce->cart->add_fee(esc_html__("Flat + Vat for ".$payment_method_title." ( ".$flate_vat." + ". $dynamic_vat ."% ) ", 'additional-fees-for-wc'), $total_vat);
            }
		}

		public function get_payment_gateway_title($gateway_id){
			$payment_gateways = WC()->payment_gateways->payment_gateways;
			foreach($payment_gateways as $payment_gateway){
				if($payment_gateway->id == $gateway_id){
					return $payment_gateway->settings['title'];
				}
			}
		}



	}