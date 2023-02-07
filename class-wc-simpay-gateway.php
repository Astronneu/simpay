<?php

class WC_SimPay extends WC_Payment_Gateway {
    public function __construct() {
        $this->id                 = 'simpay';
        $this->icon               = '';
        $this->has_fields         = false;
        $this->title              = __( 'SimPay', 'woocommerce' );
        $this->description        = __( 'Pay with simpay!', 'woocommerce' );
        $this->method_title       = __( 'SimPay', 'woocommerce' );
        $this->method_description = __( 'A simulated payment gateway for WooCommerce', 'woocommerce' );

        $this->init_form_fields();
        $this->init_settings();

        if( $this->get_option( 'enabled_for_admin' ) == 'yes' ){
            if( !current_user_can( 'administrator' ) ){
                $this->enabled = false;
            }
        }

        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    }

    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => __( 'Enable/Disable', 'woocommerce' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable Simulated Payment', 'woocommerce' ),
                'default' => 'yes'
            ),            
            'enabled_for_admin' => array(
                'title'   => __( 'Only for admin', 'woocommerce' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable SimPay just for admin users', 'woocommerce' ),
                'default' => 'yes'
            ),
            'payment_title' => array(
                'title'   => __( 'Name', 'woocommerce' ),
                'type'    => 'text',
                'label'   => __( 'Name shown to users', 'woocommerce' ),
                'default' => 'SimPay'
            ),
            'payment_description' => array(
                'title'   => __( 'Description', 'woocommerce' ),
                'type'    => 'text',
                'label'   => __( 'Description of the payment method visible to users', 'woocommerce' ),
                'default' => 'Pay with SimPay, simple payments!'
            ),
            'default_order_status' => array(
                'title'   => __( 'Status', 'woocommerce' ),
                'type'    => 'select',
                'label'   => __( 'Select the status that the order will take when paid by this payment method', 'woocommerce' ),
                'default' => 'processing',
                'options' => $this->get_order_statuses()
            ),
        );
    }

    public function process_payment( $order_id ) {
        $order = wc_get_order( $order_id );

        $order->update_status( $this->get_order_status(), __( 'Simulated payment completed', 'woocommerce' ) );
        if( $this->get_order_status() == 'wc-completed'){
            $order->payment_complete();
        }
        return array(
            'result'   => 'success',
            'redirect' => $this->get_return_url( $order )
        );
    }

    public function get_order_status(){
        return $this->get_option( 'default_order_status' );
    }

    private function get_order_statuses(){
        return wc_get_order_statuses();
    }
}