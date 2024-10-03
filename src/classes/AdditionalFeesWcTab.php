<?php
/**
 * Add Tabs for Adding General Tax Tab for Everyone
 * @package Additional Fees for WooCommerce
 */
namespace src\classes;
use src\traits\Singleton;

class AdditionalFeesWcTab{
	use Singleton;
	public function __construct(){
		add_action("woocommerce_settings_tabs_array", [$this, 'add_vat_rate_tab'], 50);
		add_action("woocommerce_settings_tabs_vat_rate_tab", [$this, "settings_tab"]);
		add_action("woocommerce_update_options_vat_rate_tab", [$this, "update_settings_tab"]);
	}

	public function add_vat_rate_tab($tabs){
		$tabs['vat_rate_tab'] = __("Extra Fee", 'additional-fees-wc');
		return $tabs;
	}

	public function vat_rate_tab_fields(){
		$settings = [
			'section_title' => [
				'id'       => 'vat_rate_tab_title',
				'name'    => esc_html__("Configure Additional VAT rate for Everyone", 'woofees','additional-fees-wc'),
				'desc'     => esc_html__("Configure Additional VAT or TAX for Your Products That Your Castomers Will See in The Checkout Page and They Have to Pay The Extra",'additional-fees-wc'),
				'type'     => 'title'
			],
			'dynamic_vat_rate' => [
				'id'       => 'additional_fees_for_wc_payment_gateway_static',
				'name'    => esc_html__("Dynamic VAT", 'additional-fees-wc'),
				'desc'     => esc_html__("Add a Number and The Percentage of Your Provided Number Will be Added to The Cart Total", 'additional-fees-wc'),
				'type'     => 'text'
			],
			'flate_vat_rate' => [
				'id'       => 'additional_fees_for_wc_payment_gateway_dynamic',
				'name'    => esc_html__("Flate VAT", 'additional-fees-wc'),
				'desc'     => esc_html__("Add a Number That Will Be Added to Your Cart Total as Flate Rate", 'additional-fees-wc'),
				'type'     => 'text'
			],
			'section_end' => [
				'id' => 'vat_rate_tab_sectionend',
				'type'=> 'sectionend'
			]
		];

		return apply_filters("vat_rate_tab_fields", $settings);
	}

	public function settings_tab(){
		woocommerce_admin_fields($this->vat_rate_tab_fields());
	}

	public function update_settings_tab(){
		woocommerce_update_options($this->vat_rate_tab_fields());
	}
}