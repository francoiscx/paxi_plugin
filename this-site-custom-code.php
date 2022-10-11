<?php
/*
    Plugin Name: This Website's Custom Code
    Plugin URI: https://acls-pals.co.za
    Description: This is where any custom code should go in to prevent overwriting by plugin and theme updates.
    Version: 0.0.1
    Author: Web-X | For Everything Web | South Africa
    Author URI: https://web-x.co.za/
    License: GPLv2 or later
    Text Domain: south_africa_ppp_shipping
*/

if (!defined('ABSPATH')) {
    exit;
}

/* ------------------------------------------------------------------------------------------------------------------ 
Code goes below this line
 ------------------------------------------------------------------------------------------------------------------- */
 


if ( in_array( 'woocommerce/woocommerce.php',
apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    	function south_african_shipping_methods_init() {
    		if ( ! class_exists( 'PPP_SOUTH_AFRICAN_SHIPPING_METHODS_PAXI' ) ) {
    			class PPP_SOUTH_AFRICAN_SHIPPING_METHODS_PAXI extends WC_Shipping_Method {
    				/**
    				 * Constructor for your shipping class
    				 *
    				 * @access public
    				 * @return void
    				 */
    				public function __construct() {
    					$this->id                 = 'south_african_shipping_methods_paxi'; // Id for your shipping method. Should be uunique.
    					$this->method_title       = __( 'South African Shiping Methods', 'south_africa_ppp_shipping');  // Title shown in admin
    					$this->method_description = __( 'PAXI, PostNet & pargo shipping options', 'south_africa_ppp_shipping' ); // Description shown in admin
    					$this->title              = __( 'PAXI, PostNet & pargo', 'south_africa_ppp_shipping'); // This can be added as an setting but for this example its forced.
                        $this->init();
                        $this->enabled            = $this->settings["enable"]; // This can be added as an setting but for this example its forced enabled
    				}


    
    				/**
    				 * Init your settings
    				 *
    				 * @access public
    				 * @return void
    				 */
    				function init() {
    					// Load the settings API
    					$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
    					$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
//                        $providers = south_africa_ppp_shipping_providers();
                        $this->countries['ZA'];
                        $this->available = 'including';

    					// Save settings in admin if you have any defined
    					add_action( 'woocommerce_update_options_shipping_' . $this->id,
                            array( $this, 'process_admin_options' ) );
    				}
    
                    
                    /**
                     * Settings Fields
                     * @return void
                     */
                    function init_form_fields() {
                        $this->form_fields = array(
                            'enable' => array(
                                'title' => __( 'Enable', 'south_africa_ppp_shipping' ),
                                'type' => 'checkbox',
                                'description' => __( 'Enable this shipping.', 'south_africa_ppp_shipping' ),
                                'default' => 'no'
                            ),

    //                        'use_paxi' => array(
    //                            'title' => __( 'Enable PAXI', 'south_africa_ppp_shipping' ),
    //                            'type' => 'checkbox',
    //                            'description' => __( 'Enable PAXI as shipping option.', 'south_africa_ppp_shipping' ),
    //                            'default' => 'yes'
    //                        ),
    //                        'use_postnet' => array(
    //                            'title' => __( 'Enable PostNet', 'south_africa_ppp_shipping' ),
    //                            'type' => 'checkbox',
    //                            'description' => __( 'Enable PostNet as shipping option.', 'south_africa_ppp_shipping' ),
    //                            'default' => 'yes'
    //                        ),
    //                        'use_pargo' => array(
    //                            'title' => __( 'Enable pargo', 'south_africa_ppp_shipping' ),
    //                            'type' => 'checkbox',
    //                            'description' => __( 'Enable pargo as shipping option.', 'south_africa_ppp_shipping' ),
    //                            'default' => 'yes'
    //                        ),  
                        );
                    }


                        
                    





















    				/**
    				 * calculate_shipping function.
    				 *
    				 * @access public
    				 * @param mixed $package
    				 * @return void
    				 */
    				public function calculate_shipping( $package = array() ) {
    					$rates = array(
    						'label' => $this->title,
    						'cost' => '199',
    						'calc_tax' => 'per_item'
    					);
    
    					// Register the rate
    					$this->add_rate( $rates );
    				}
    			}
    		}
    	}
    
    	add_action( 'woocommerce_shipping_init', 'south_african_shipping_methods_init' );
    
    	function add_south_african_shipping_methods( $methods ) {
    		$methods['south_african_shipping_methods_paxi'] = 'PPP_SOUTH_AFRICAN_SHIPPING_METHODS_PAXI';
    		return $methods;
    	}
    
    	add_filter( 'woocommerce_shipping_methods', 'add_south_african_shipping_methods' );
    
    
    



        // Add custom fields to a specific selected shipping method
        add_action( 'woocommerce_after_shipping_rate', 'south_african_shipping_methods_custom_fields', 20, 2 );
        function south_african_shipping_methods_custom_fields( $method, $index ) {
            if( ! is_checkout()) {return;}  // Only on checkout page
        
            // $customer_south_african_shipping_methods_method = 'south_african_shipping_methods_postnet';
        
            if( $method->id != $customer_south_african_shipping_methods_method ) return; // Only display for "local_pickup"
        
            $chosen_method_id = WC()->session->chosen_shipping_methods[ $index ];
        
            // If the chosen shipping method is 'south_african_shipping_methods_postnet' we display
            if($chosen_method_id == "south_african_shipping_methods_paxi" ) {
        
                echo '<div class="custom-south_african_shipping_methods_paxi">';
            
                woocommerce_form_field( 'south_african_shipping_methods_location' , array(
                    'type'          => 'text',
                    'class'         => array('form-row-wide south_african_shipping_methods_paxi-location'),
                    'label'         => '<a href="https://www.paxi.co.za/points" target="_blank">Locate nearest PAXI:</a>',
                    'required'      => true,
                    'placeholder'   => 'Nearest PAXI',
                ), WC()->checkout->get_value( 'south_african_shipping_methods_location' ));
            
                woocommerce_form_field( 'south_african_shipping_methods_number' , array(
                    'type'          => 'hidden',
                    'class'         => array('form-row-wide south_african_shipping_methods_paxi-number'),
                    'required'      => true,
                    'placeholder'   => 'Number',
                    'value'         => 1,
                ), WC()->checkout->get_value( 'south_african_shipping_methods_number' ));

            } elseif($chosen_method_id == "south_african_shipping_methods_postnet" ) {
        
                echo '<div class="custom-south_african_shipping_methods_paxi">';
            
                woocommerce_form_field( 'south_african_shipping_methods_location' , array(
                    'type'          => 'text',
                    'class'         => array('form-row-wide south_african_shipping_methods_paxi-location'),
                    'label'         => '<a href="https://www.postnet.co.za/stores" target="_blank">Locate nearest PostNet:</a>',
                    'required'      => true,
                    'placeholder'   => 'Nearest PostNet',
                ), WC()->checkout->get_value( 'south_african_shipping_methods_location' ));
            
                woocommerce_form_field( 'south_african_shipping_methods_number' , array(
                    'type'          => 'hidden',
                    'class'         => array('form-row-wide south_african_shipping_methods_paxi-number'),
                    'required'      => true,
                    'placeholder'   => 'Number',
                    'value'         => 1,
                ), WC()->checkout->get_value( 'south_african_shipping_methods_number' ));

            }  elseif($chosen_method_id == "south_african_shipping_methods_pargo" ) {
        
                echo '<div class="custom-south_african_shipping_methods_paxi">';
            
                woocommerce_form_field( 'south_african_shipping_methods_location' , array(
                    'type'          => 'text',
                    'class'         => array('form-row-wide south_african_shipping_methods_paxi-location'),
                    'label'         => '<a href="https://pargo.co.za/find-a-store/" target="_blank">Locate nearest pargo:</a>',
                    'required'      => true,
                    'placeholder'   => 'Nearest pargo',
                ), WC()->checkout->get_value( 'south_african_shipping_methods_location' ));
            
                woocommerce_form_field( 'south_african_shipping_methods_number' , array(
                    'type'          => 'hidden',
                    'class'         => array('form-row-wide south_african_shipping_methods_paxi-number'),
                    'required'      => true,
                    'placeholder'   => 'Number',
                    'value'         => 1,
                ), WC()->checkout->get_value( 'south_african_shipping_methods_number' ));

            }
            
                global $wpdb;
                update_option('south_african_shipping_methods_chosen_shipping', $chosen_method_id);
            echo '</div>';
        }





        /* Produce errors if PostNet method is used, without providing location */
        add_action('woocommerce_checkout_process', 'south_african_shipping_methods_check_if_selected');

        function south_african_shipping_methods_check_if_selected()
        {
            global $wpdb;
            $chosen_method_id = get_option('south_african_shipping_methods_chosen_shipping');

            if($chosen_method_id == "south_african_shipping_methods_postnet"){
                if (empty($_POST['south_african_shipping_methods_location'])) {
                    wc_add_notice('<strong>Please provide your nearest <a href="https://www.postnet.co.za/stores" target="blank">PostNet</a> Branch</strong>:<br>Visit the <a href="https://www.postnet.co.za/stores" target="blank">PostNet website</a> and search for the nearest branch, then enter the PostNet branch name into the space provided on this form', 'error');
                } elseif (strlen($_POST['south_african_shipping_methods_location']) < 5) {
                        wc_add_notice('<strong><a href="https://www.postnet.co.za/stores" target="blank">PostNet</a> Branch does not appear to be correct</strong>:<br>Ensure you provide the correct branch by visiting the <a href="https://www.postnet.co.za/stores" target="blank">PostNet website</a> searching for the nearest branch, and then entering the name of the nearest PostNet branch to you, into the space provided on this form', 'error');
                }
            }

            return $errors;
        }




        /* Store the area selected for PostNet branch */
        add_action( 'woocommerce_checkout_update_order_meta', 'south_african_shipping_methods_checkout_field_update_order_meta' );
        
        function south_african_shipping_methods_checkout_field_update_order_meta( $order_id ) {
            if ( ! empty( $_POST['south_african_shipping_methods_location'] ) ) {
                update_post_meta( $order_id, 'south_african_shipping_methods_location', sanitize_text_field( $_POST['south_african_shipping_methods_location'] ) );
            }
        }







        add_filter('woocommerce_checkout_fields', 'south_african_shipping_methods_billing_another_group');
        
        function south_african_shipping_methods_billing_another_group($checkout_fields)
        {
            $checkout_fields['order']['billing_south_african_shipping_methods_location'] = $_POST['south_african_shipping_methods_location'];
            // $checkout_fields['order']['billing_user_OtherID'] = $checkout_fields['billing']['billing_user_OtherID'];
            // $checkout_fields['order']['billing_user_OIDT'] = $checkout_fields['billing']['billing_user_OIDT'];
            // $checkout_fields['order']['billing_user_OIDI'] = $checkout_fields['billing']['billing_user_OIDI'];
            // $checkout_fields['order']['billing_SAIDD'] = $checkout_fields['billing']['billing_SAIDD'];
            // unset($checkout_fields['billing']['billing_user_SAID']);
            // unset($checkout_fields['billing']['billing_user_OtherID']);
            // unset($checkout_fields['billing']['billing_user_OIDT']);
            // unset($checkout_fields['billing']['billing_user_OIDI']);
            // unset($checkout_fields['billing']['billing_SAIDD']);
            // unset($checkout_fields['shipping']['shipping_user_SAID']);
            // unset($checkout_fields['shipping']['shipping_user_OtherID']);
            // unset($checkout_fields['shipping']['shipping_user_OIDT']);
            // unset($checkout_fields['shipping']['shipping_user_OIDI']);
            // unset($checkout_fields['shipping']['shipping_SAIDD']);
            return $checkout_fields;
        }

        /* Display field value on the order in the backend edit page on order form */
        add_action('woocommerce_admin_order_data_after_billing_address', 'south_african_shipping_methods_custom_checkout_field_display_admin_order_meta', 10, 1);

        function south_african_shipping_methods_custom_checkout_field_display_admin_order_meta($order)
        {
            if (get_post_meta($order->get_id(), 'south_african_shipping_methods_location', true)) {
                echo '<p><strong>' . __('Deliver to PostNet') . ':</strong><br>' . get_post_meta($order->get_id(), 'south_african_shipping_methods_location', true) . '</p>';
            }
            // if (get_post_meta($order->get_id(), '_billing_user_OtherID', true)) {
            //     echo '<p><strong>' . __('Other Idendification #') . ':</strong><br>' . get_post_meta($order->get_id(), '_billing_user_OtherID', true) . '</p>';
            // }
            // if (get_post_meta($order->get_id(), '_billing_user_OIDT', true)) {
            //     echo '<p><strong>' . __('Identification Type') . ':</strong><br>' . get_post_meta($order->get_id(), '_billing_user_OIDT', true) . '</p>';
            // }
            // if (get_post_meta($order->get_id(), '_billing_user_OIDI', true)) {
            //     echo '<p><strong>' . __('Country of Issue') . ':</strong><br>' . get_post_meta($order->get_id(), '_billing_user_OIDI', true) . '</p>';
            // }
        }
        add_action( 'woocommerce_checkout_update_order_meta', 'bbloomer_save_weight_order' );
 


        function your_function() {
            global $woocommerce, $post; 
                // echo "The order weight is: " . WC()->cart->cart_contents_weight;

            // global $wpdb;
            //     echo "<br>Chosen Method: " . get_option('south_african_shipping_methods_chosen_shipping');

                
                echo "Shipping Classes:<br>";
                
                // $shipping_classes = get_terms( array('taxonomy' => 'product_shipping_class', 'hide_empty' => false ) );

                // var_dump($shipping_classes);


                function wc_get_shipping_classes(){
                    global $wpdb;
                    return $wpdb->get_results( "
                        SELECT * FROM {$wpdb->prefix}terms as t
                        INNER JOIN {$wpdb->prefix}term_taxonomy as tt ON t.term_id = tt.term_id
                        WHERE tt.taxonomy LIKE 'product_shipping_class'
                    " );
                }

                $shipping_classes = wc_get_shipping_classes(); // Get Shipping Classes
                echo '<pre>'; var_dump($shipping_classes); echo '</pre>'; // Test raw output   


                echo "<br>Shipping Classes ends: ";


        }
        add_action( 'wp_footer', 'your_function' );

}
