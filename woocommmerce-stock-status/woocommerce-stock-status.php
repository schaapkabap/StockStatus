<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://cherement.nl/demo
 * @since             1.0.0
 * @package           woocommerce-stock-status
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce stock status
 * Plugin URI:        http://cherement.nl/demo
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author URI:        http://schaapkabap.nl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-stock-status
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-stock-status-activator.php';
	WoocommerceStockStatus_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-stock-status-deactivator.php';
	WoocommerceStockStatus_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce-stock-status' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce-stock-status' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-stock-status.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Woocommerce_Stock_Status() {

	$plugin = new Woocommerce_Stock_Status();
	$plugin->run();


}
run_Woocommerce_Stock_Status();

if (!function_exists('write_log')) {
	function write_log ( $log )  {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}
	}
}

