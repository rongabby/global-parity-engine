<?php
/**
 * Global Parity Data Plugin
 *
 * @package           GlobalParityData
 * @author            rongabby
 * @copyright         2026 rongabby
 * @license           MIT
 *
 * @wordpress-plugin
 * Plugin Name:       Global Parity Data
 * Plugin URI:        https://github.com/rongabby/global-parity-engine
 * Description:       Core data management plugin for tracking global parity data with REST API support.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            rongabby
 * Author URI:        https://github.com/rongabby
 * Text Domain:       global-parity-data
 * Domain Path:       /languages
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'GLOBAL_PARITY_DATA_VERSION', '1.0.0' );

/**
 * Plugin directory path.
 */
define( 'GLOBAL_PARITY_DATA_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL.
 */
define( 'GLOBAL_PARITY_DATA_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_global_parity_data() {
	// Activation code will be implemented here
	// - Register custom post type
	// - Set up database tables (if needed)
	// - Flush rewrite rules
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_global_parity_data() {
	// Deactivation code will be implemented here
	// - Flush rewrite rules
}

register_activation_hook( __FILE__, 'activate_global_parity_data' );
register_deactivation_hook( __FILE__, 'deactivate_global_parity_data' );

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 */
function run_global_parity_data() {
	// Plugin initialization code will be implemented here
	// This will be implemented in PR #2
}

run_global_parity_data();
