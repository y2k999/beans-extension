<?php
/**
 * Fired during plugin activation.
 * @package Beans_Extension
 * @license GPL3.0+
 * @since 1.0.1
*/

/**
 * Inspired by Beans Framework WordPress Theme
 * @link https://www.getbeans.io
 * @author Thierry Muller
*/
namespace Beans_Extension;


/* Prepare
______________________________
*/

// If this file is called directly,abort.
if(!defined('WPINC')){die;}


/* Exec
______________________________
*/
if(class_exists('_beans_i18n') === FALSE) :
class _beans_i18n
{
/**
 * @since 1.0.1
 * 	Define the internationalization functionality.
 * 	Loads and defines the internationalization files for this plugin so that it is ready for translation.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	load_plugin_textdomain()
*/

	/**
		@access (protected)
			Class properties.
		@var (string)$plugin_name
			The string used to uniquely identify this plugin.
	*/
	protected $plugin_name;


	/**
	 * Traits.
	*/
	use _trait_singleton;


	/* Constructor
	_________________________
	*/
	private function __construct()
	{
		/**
			@access (private)
				Class constructor.
				This is only called once, since the only way to instantiate this is with the get_instance() method in trait (singleton.php).
			@return (void)
			@reference
				[Plugin]/trait/singleton.php
		*/

		// Init Properties.
		$this->plugin_name = 'beans-extension';

		/**
		 * @reference (WP)
		 * 	Fires once activated plugins have loaded.
		 * 	https://developer.wordpress.org/reference/hooks/plugins_loaded/
		*/
		add_action('plugins_loaded',[$this,'load_plugin_textdomain']);

	}// Method


	/* Hook
	_________________________
	*/
	public function load_plugin_textdomain()
	{
		/**
			@access (public)
				Load the plugin text domain for translation.
				https://developer.wordpress.org/reference/functions/load_plugin_textdomain/
			@param (string) $domain
				Unique identifier for retrieving translated strings.
			@param (string)|(false) $deprecated
				Deprecated.
				Use the $plugin_rel_path parameter instead.
				Default value: false
			@param (string)|(false) $plugin_rel_path
				Relative path to WP_PLUGIN_DIR where the .mo file resides.
				Default value: false
			@return (void)
			@reference
				[Plugin]/include/constant.php
		*/
		load_plugin_textdomain($this->plugin_name,FALSE,BEANS_EXTENSION_LANGUAGE_PATH);

	}// Method


}// Class
endif;
// new _beans_i18n();
_beans_i18n::__get_instance();
