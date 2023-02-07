<?php
/**
 * Plugin Name: SimPay
 * Description: A plugin that adds a simulated payment method to WooCommerce
 * Version: 1.0.0
 * Author: Weberwin
 * Author URI: https://weberwin.pl/plugins/simpay
 */

class WC_Simpay_init {
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }

    public function init() {
        if ( !class_exists( 'WC_Payment_Gateway' ) ) {
            return;
        }

        require 'class-wc-simpay-gateway.php';

        add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateway' ) );
    }

    public function add_gateway( $methods ) {
        $methods[] = 'WC_SimPay';
        return $methods;
    }
}

new WC_Simpay_init();
