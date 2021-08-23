<?php
/**
 * The plugin bootstrap file
 * This file is read by WordPress to generate the plugin information in the plugin admin area.
 * This file also includes all of the dependencies used by the plugin,registers the activation and deactivation functions,and defines a function that starts the plugin.
 * Plugin Name: Beans Extension
 * Plugin URI: https://wpbeansframework.com/
 * Description: This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version: 1.0.1
 * Author: That WP Developer
 * Author URI: https://github.com/thatwpdeveloper
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: beans-extension
 * Domain Path: /asset/language
*/

/**
 * Inspired by Beans Framework WordPress Theme
 * @link https://www.getbeans.io
 * @author Thierry Muller
*/


/* Prepare
______________________________
*/

// If this file is called directly,abort.
if(!defined('WPINC')){die;}


/* Exec
______________________________
*/

	/**
		@access (public)
			Set the activation hook for a plugin.
		@return (void)
		@reference (WP)
			The code that runs during plugin activation.
			https://developer.wordpress.org/reference/functions/register_activation_hook/
	*/
	if(function_exists('activate_beans_extension') === FALSE) :
	function activate_beans_extension()
	{
		/**
		 * @reference (WP)
		 * 	Get the filesystem directory path (with trailing slash) for the plugin __FILE__ passed in.
		 * 	https://developer.wordpress.org/reference/functions/plugin_dir_path/
		*/
		require_once plugin_dir_path(__FILE__) . 'include/activator.php';

	}// Method
	endif;


	/**
		@access (public)
			Sets the deactivation hook for a plugin.
		@return (void)
		@reference (WP)
			The code that runs during plugin deactivation.
			https://developer.wordpress.org/reference/functions/register_deactivation_hook/
	*/
	if(function_exists('deactivate_beans_extension') === FALSE) :
	function deactivate_beans_extension()
	{
		require_once plugin_dir_path(__FILE__) . 'include/deactivator.php';

	}// Method
	endif;

	/**
	 * @reference (WP)
	 * 	Set the activation hook for a plugin.
	 * 	https://developer.wordpress.org/reference/functions/register_activation_hook/
	*/
	register_activation_hook(__FILE__,'activate_beans_extension');

	/**
	 * @reference (WP)
	 * 	Set the deactivation hook for a plugin.
	 * 	https://developer.wordpress.org/reference/functions/register_deactivation_hook/
	*/
	register_deactivation_hook(__FILE__,'deactivate_beans_extension');


	/**
		@access (public)
			Begins execution of the plugin.
			Since everything within the plugin is registered via hooks,then kicking off the plugin from this point in the file does not affect the page life cycle.
		@return (void)
	*/
	if(function_exists('run_beans_extension') === FALSE) :
	function run_beans_extension()
	{
		// If Original Beans theme (tm-beans) is the active theme, switch to the latest WordPress default theme that is installed.
		if(in_array(wp_get_theme()->template,array('tm-beans'),TRUE)){
			$default = WP_Theme::get_core_default_theme();
			switch_theme($default);
		}

		// The core plugin class that is used to define internationalization,admin-specific hooks,and public-facing site hooks.
		require_once plugin_dir_path(__FILE__) . 'utility/tag-external.php';
		require_once plugin_dir_path(__FILE__) . 'utility/tag-internal.php';
		require plugin_dir_path(__FILE__) . 'include/core.php';

		/**
		 * @reference (Beans)
		 * 	Fires before Beans loads.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_before_init/
		*/
		do_action('beans_extension_before_init');

		/**
		 * @reference (Beans)
		 * 	Load Beans framework.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_init/
		*/
		do_action('beans_extension_init');

		/**
		 * @reference (Beans)
		 * 	Fires after Beans loads.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_after_init/
		*/
		do_action('beans_extension_after_init');

	}// Method
	endif;

	// Begins execution of the plugin.
	run_beans_extension();
