<?php
/**
 * Plugin Name: Digito Instruction Steps
 * Description: A plugin to create interactive step-by-step instructions on WordPress pages.
 * Version: 1.0.0
 * Author: digito digital marketing agency
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: digito-instruction-steps
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define plugin constants
define( 'DIGITO_IS_VERSION', '1.0.0' );
define( 'DIGITO_IS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Include necessary files
require_once DIGITO_IS_PLUGIN_DIR . 'includes/class-digito-instruction-steps.php';

// Initialize the plugin
function digito_is_init() {
    $plugin = new Digito_Instruction_Steps();
    $plugin->run();
}
add_action( 'plugins_loaded', 'digito_is_init' );
