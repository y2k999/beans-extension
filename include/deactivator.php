<?php
/**
 * Fired during plugin deactivation.
 * @package Beans_Extension
 * @license GPL3.0+
 * @since 1.0.1
*/

/**
 * Inspired by Beans Framework WordPress Theme
 * @link https://www.getbeans.io
 * @author Thierry Muller
*/
// namespace Beans_Extension;


/* Prepare
______________________________
*/

// If this file is called directly,abort.
if(!defined('WPINC')){die;}


/* Exec
______________________________
*/
if(class_exists('_beans_deactivator') === FALSE) :
class _beans_deactivator
{
/**
 * @since 1.0.1
 * 	Fired during plugin deactivation.
 * 	This class defines all code necessary to run during the plugin's deactivation.
 * 
 * [TOC]
 * 	__construct()
 * 	run()
*/

	/**
		@access (protected)
			Class properties.
		@var (string)$plugin_name
			The string used to uniquely identify this plugin.
	*/
	protected $plugin_name;


	/* Constructor
	_________________________
	*/
	public function __construct()
	{
		/**
			@access (public)
				Class Constructor.
			@return (void)
		*/

		// Init Properties.
		$this->plugin_name = 'beans-extension';

		// Exec.
		$this->run();

	}// Method


	/* Method
	_________________________
	*/
	private function run()
	{
		/**
			@access (private)
				Set the deactivation hook for a plugin.
				https://developer.wordpress.org/reference/functions/register_deactivation_hook/
			@return (void)
		*/

		/**
		 * @reference (WP)
		 * 	Returns whether the current user has the specified capability.
		 * 	https://developer.wordpress.org/reference/functions/current_user_can/
		*/
		if(!current_user_can('activate_plugins')){return;}

		delete_option('beans_extension_setting');
		delete_option('beans_extension_layout');
		delete_option('beans_extension_mode_option');
		delete_option('beans_extension_compiler_option');
		delete_option('beans_extension_image_option');
		delete_option('beans_extension_dev_mode');

		delete_option('beans_extension_compile_all_style');
		delete_option('beans_extension_compile_all_script');
		delete_option('beans_extension_compile_all_script_group');
		delete_option('beans_extension_compile_all_script_mode');

		$this->delete_options_prefixed();

		$groups = array(
			'bx_option_api',
			'bx_option_column',
			'bx_option_general',
			'bx_option_image',
			'bx_option_layout',
			'bx_option_cleaner',
		);
		foreach($groups as $group){
			delete_option($group);
		}

		$settings = array(
			'bx_setting_general_stop_beans_image',
			'bx_setting_general_stop_beans_widget',
			'bx_setting_general_beans_legacy_layout',
			'bx_setting_general_beans_accessibility',
			'bx_setting_general_css_framework',
			'bx_setting_general_use_uikit3_cdn',
			'bx_setting_layout_single',
			'bx_setting_layout_page',
			'bx_setting_layout_home',
			'bx_setting_layout_archive',
		);
		foreach($settings as $setting){
			delete_option($setting);
		}

		/**
		 * @reference (WP)
		 * 	Returns an array containing the current upload directoryfs path and URL.
		 * 	https://developer.wordpress.org/reference/functions/wp_upload_dir/
		*/
		$wp_upload_dir = wp_upload_dir();

		/**
		 * @reference (WP)
		 * 	Normalize a filesystem path.
		 * 	https://developer.wordpress.org/reference/functions/wp_normalize_path/
		*/
		$dir = wp_normalize_path(trailingslashit($wp_upload_dir['basedir']) . $this->plugin_name);

		if(is_dir($dir)){
			// @rmdir($dir);
			$this->force_rmdir($dir);
		}

	}// Method


	/**
		@access (private)
			https://log.vavevoo.com/page/contents/c-100-remove-directory.html
		@return (void)
	*/
	private function force_rmdir($path)
	{
		$list = scandir($path);
		$length = count($list);
		for($i = 0; $i < $length; $i++){
			if($list[$i] != '.' && $list[$i] != '..'){
				if(is_dir($path . '/' . $list[$i])){
					$this->force_rmdir($path . '/' . $list[$i]);
				}
				else{
					unlink($path . '/' . $list[$i]);
				}
			}
		}
		rmdir($path);

	}// Method


	/**
		@access (private)
			https://stackoverflow.com/questions/8571037/wordpress-delete-option-wildcard-capability
		@global (wpdb) $wpdb
			WordPress database access abstraction class.
			https://developer.wordpress.org/reference/classes/wpdb/
		@return (void)
	*/
	private function delete_options_prefixed()
	{
		// WP global.
		global $wpdb;
		$wpdb->query($wpdb->prepare(
			"DELETE FROM $wpdb->options WHERE option_name LIKE %s",
			"beans_extension_term_%"
		));

	}// Method


}// Class
endif;
new _beans_deactivator();
