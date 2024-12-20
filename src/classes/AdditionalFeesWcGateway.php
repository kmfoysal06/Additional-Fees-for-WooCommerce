<?php
/**
 * Fees Based on Payment Gateway
 * @package Additional Fees for WooCommerce
 * 
 */
namespace affw\src\classes;

if(!defined('ABSPATH')) exit;

use affw\src\traits\Singleton;

class AdditionalFeesWcGateway{
	use Singleton;
	public function __construct(){
		add_action("woocommerce_settings_tabs_vat_rate_tab", [$this, "settings_tab"]);
		add_action("woocommerce_update_options_vat_rate_tab", [$this, "update_settings_tab"]);
	}
	/**
	 * Getting All Available Payment Gateways and
	 * Forwarding Them in The load_fields Which Return 
	 * The Fields For Each Payment Gateway.
	 * @see load_fields
	 * @return array
	 * 
	 */

	public function vat_rate_tab_fields(){
		$payment_methods = WC()->payment_gateways->get_available_payment_gateways();
		$settings = [];
		foreach($payment_methods as $payment_method){
			$gateway_id = $payment_method->id;
			$gateway_title = $payment_method->settings['title'];
			$gateway_settings = $this->load_fields($gateway_id, $gateway_title);
			$settings = array_merge($settings,$gateway_settings ?? []);
		}
			return $settings;
			
	}

	/**
	 * Passing The Fields as Array in 
	 * woocommerce_admin_fields That Will 
	 * Return Them as HTML
	 * @see vat_rate_tab_fields
	 * 
	 */

	public function settings_tab(){
		woocommerce_admin_fields($this->vat_rate_tab_fields());
	}

	/**
	 * Passing The Fields as Array in 
	 * woocommerce_update_options That Will 
	 * Return Update The Data in The Database
	 * @see vat_rate_tab_fields
	 * 
	 */

	public function update_settings_tab(){
		woocommerce_update_options($this->vat_rate_tab_fields());
	}

	/**
	 * Default Structure of The Fields That Will Be 
	 * Loaded as HTML in The WooCommerce Settings for 
	 * Each Payment Methods
	 * 
	 */

	public function load_fields($gateway_id, $gateway_title){
		$settings = [
			$gateway_id . '_section_title' => [
				'id'       => esc_attr('additional_fees_for_wc_payment_gateway_'.$gateway_id),

				/* translators: %s Payment Gateway Title */

				'name' => sprintf(esc_html__("Configure Additional VAT rate for %s", 'additional-fees-for-woocommerce'), esc_html($gateway_title)),

				/* translators: %s: Payment Gateway Title */

				'desc'     => sprintf(esc_html__("Configure Additional VAT or TAX for Your Products That Your Castomers Will See in The Checkout Page and They Have to Pay The Extra if The Select %s", 'additional-fees-for-woocommerce'), esc_html($gateway_title)),
				'type'     => 'title'
			],
			'dynamic_vat_rate_'.$gateway_id => [
				'id'       => 'additional_fees_for_wc_payment_gateway_'.$gateway_id.'_dynamic',
				'name'    => esc_html__("Dynamic VAT", 'additional-fees-for-woocommerce'),
				'desc'     => esc_html__("Add a Number and The Percentage of Your Provided Number Will be Added to The Cart Total", 'additional-fees-for-woocommerce'),
				'type'     => 'text'
			],
			'flat_vat_rate'.$gateway_id => [
				'id'       => 'additional_fees_for_wc_payment_gateway_'.$gateway_id.'_static',
				'name'    => esc_html__("flat VAT", 'additional-fees-for-woocommerce'),
				'desc'     => esc_html__("Add a Number That Will Be Added to Your Cart Total as flat Rate", 'additional-fees-for-woocommerce'),
				'type'     => 'text'
			],

			$gateway_id . '_section_end' => [
				'id' => 'additional_fees_for_wc_payment_gateway_'.$gateway_id.'_sectionend',
				'type'=> 'sectionend'
			]
		];

		return apply_filters("additional_fees_for_woocommerce_tab_" . $gateway_title . "_fields", $settings);
	}
	
}