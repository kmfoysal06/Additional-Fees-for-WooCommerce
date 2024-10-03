<?php
/**
 * Fees Based on Payment Gateway
 * @package Additional Fees for WooCommerce
 */
namespace src\classes;
use src\traits\Singleton;

class AdditionalFeesWcGateway{
	use Singleton;
	public function __construct(){
		add_action("woocommerce_settings_tabs_vat_rate_tab", [$this, "settings_tab"]);
		add_action("woocommerce_update_options_vat_rate_tab", [$this, "update_settings_tab"]);
	}

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

	public function settings_tab(){
		woocommerce_admin_fields($this->vat_rate_tab_fields());
	}

	public function update_settings_tab(){
		woocommerce_update_options($this->vat_rate_tab_fields());
	}

	public function load_fields($gateway_id, $gateway_title){
		$settings = [
			$gateway_id . '_section_title' => [
				'id'       => esc_attr('additional_fees_for_wc_payment_gateway_'.$gateway_id),
				'name'    => esc_html__("Configure Additional VAT rate for " . $gateway_title,'additional-fees-wc'),
				'desc'     => esc_html__("Configure Additional VAT or TAX for Your Products That Your Castomers Will See in The Checkout Page and They Have to Pay The Extra if The Select " . $gateway_title, 'additional-fees-wc'),
				'type'     => 'title'
			],
			'dynamic_vat_rate_'.$gateway_id => [
				'id'       => 'additional_fees_for_wc_payment_gateway_'.$gateway_id.'_dynamic',
				'name'    => esc_html__("Dynamic VAT", 'additional-fees-wc'),
				'desc'     => esc_html__("Add a Number and The Percentage of Your Provided Number Will be Added to The Cart Total", 'additional-fees-wc'),
				'type'     => 'text'
			],
			'flate_vat_rate'.$gateway_id => [
				'id'       => 'additional_fees_for_wc_payment_gateway_'.$gateway_id.'_static',
				'name'    => esc_html__("Flate VAT", 'additional-fees-wc'),
				'desc'     => esc_html__("Add a Number That Will Be Added to Your Cart Total as Flate Rate", 'additional-fees-wc'),
				'type'     => 'text'
			],

			$gateway_id . '_section_end' => [
				'id' => 'additional_fees_for_wc_payment_gateway_'.$gateway_id.'_sectionend',
				'type'=> 'sectionend'
			]
		];

		return apply_filters("additional_fees_for_wc_vat_rate_" . $gateway_title . "_fields", $settings);
	}
	
}