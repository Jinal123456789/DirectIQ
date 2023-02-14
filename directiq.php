<?php
/**
 * Plugin Name: DirectIQ
 * Plugin URI: https://www.directiq.com/
 * Description: A dynamic plugin for API managemnet and form creation.
 * Version: 2.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: DirectIQ
 * Author URI: https://www.directiq.com/
 * Text Domain: directiq 
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Restrict direct access
 */
if ( !defined('ABSPATH') ) exit; 

if ( ! defined( 'WPINC' ) ) die; 

/**
 * Default constants 
 */
define( 'DIRECTIQ_PLUGIN_BASE_NAME', plugin_basename(__FILE__));
define( 'DIRECTIQ_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define( 'DIRECTIQ_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
define( 'DIRECTIQ_REST_BASE_URL', "http://rest.directiq.com/subscription");

require_once('init.php');
// Activation hook and funciton
register_activation_hook( __FILE__, 'diq_activate' );
if ( !function_exists( 'diq_activate' ) ){
	function diq_activate(){
		// require_once('init.php');
		// Initalize
		// $diq_instance = new Initialize();
	}
}


// Deactivation hook and funciton
register_uninstall_hook( __FILE__, 'diq_deactivate' );
if ( !function_exists( 'diq_deactivate' ) ){
	function diq_deactivate(){
		$diq_obj = InitializeDirectIq::GetInstance();
		$diq_obj->diq_deactivate_plugin();
	}
}


