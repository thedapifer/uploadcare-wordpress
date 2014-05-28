<?php

/**
 * Check if WooCommerce is active
 **/
function uploadcare_is_woo_enabled() {
    return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
}


function uploadcare_woo_enqueue_scripts() {
    if(uploadcare_is_woo_enabled()) {
        wp_enqueue_script('uploadcare-woocommerce');
        wp_enqueue_style('uploadcare-style');
    }
}


function uploadcare_woo_checkout_field($checkout) {
    echo '<div id="uploadcare_checkout_field_container"><h2>' . __('Add files to order') . '</h2>';

    woocommerce_form_field(
        'uploadcare_checkout_field',
        array(
            'type'          => 'text',
            'class'         => array('uploadcare-woo-checkout'),
            'label'         => __('Fill in this field'),
            // 'placeholder'   => __('Enter something'),
            'custom_attributes' => array('role' => 'uploadcare-uploader'),
        ),
        $checkout->get_value('uploadcare_checkout_field')
    );

    echo '<div id="uploadcare_checkout_field_preview"></div></div>';
}


/**
 * Process the checkout
 */
function uploadcare_checkout_field_process() {
    // Check if set, if its not set add an error.
    if ( ! $_POST['uploadcare_checkout_field'] ) {
        // wc_add_notice( __( 'Please enter something into this new shiny field.' ), 'error' );
    }
}

/**
 * Update the order meta with field value
 */
function uploadcare_checkout_field_update_order_meta($order_id) {
    if (!empty($_POST['uploadcare_checkout_field'])) {
        update_post_meta($order_id, 'uploadcare-woo-order-files', $_POST['uploadcare_checkout_field']);
    }
}

/**
 * Display field value on the order edit page
 */
function uploadcare_checkout_field_display_admin_order_meta($order) {
    $files = get_post_meta($order->id, 'uploadcare-woo-order-files', true);
    if(!empty($files)) {
        echo '<p><strong>'.__('Attached files').':</strong> ' . $files . '</p>';
    }
}


if(uploadcare_is_woo_enabled()) {
    add_action('wp_enqueue_scripts', 'uploadcare_woo_enqueue_scripts');
    add_action('woocommerce_after_order_notes', 'uploadcare_woo_checkout_field');
    // add_action('woocommerce_checkout_process', 'uploadcare_checkout_field_process');
    add_action('woocommerce_admin_order_data_after_shipping_address', 'uploadcare_checkout_field_display_admin_order_meta', 10, 1);
    add_action('woocommerce_checkout_update_order_meta', 'uploadcare_checkout_field_update_order_meta');
}

?>
