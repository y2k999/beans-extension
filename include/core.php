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

// Loadup required files.
require_once plugin_dir_path(dirname(__FILE__)) . 'include/constant.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'include/autoloader.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'trait/singleton.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'trait/hook.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'utility/general.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'utility/beans.php';


/* Exec
______________________________
*/
if(class_exists('_beans_core') === FALSE) :
class _beans_core
{
/**
 * @since 1.0.1
 * 	The core plugin class.
 * 	This is used to define internationalization,admin-specific hooks,and public-facing site hooks.
 * 	Also maintains the unique identifier of this plugin as well as the current version of the plugin.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_locale()
 * 	load_component()
 * 	load_misc()
 * 	define_admin_hook()
 * 	define_asset_hook()
 * 	get_plugin_name()
 * 	get_version()
*/

	/**
		@access (protected)
			Class properties.
		@var (string)$plugin_name
			The string used to uniquely identify this plugin.
		@var(string)$version
			The current version of the plugin.
	*/
	protected $plugin_name;
	protected $version;

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
				Define the core functionality of the plugin.
				Set the plugin name and the plugin version that can be used throughout the plugin.
				Load the dependencies,define the locale,and set the hooks for the admin area and the public-facing side of the site.
			@return (void)
				[Plugin]/trait/singleton.php
				[Plugin]/include/constant.php
		*/

		// Init Properties.
		if(defined('BEANS_EXTENSION_VERSION')){
			$this->version = BEANS_EXTENSION_VERSION;
		}
		else{
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'beans-extension';

		// Run methods.
		$this->set_locale();
		$this->load_component();
		$this->define_admin_hook();
		$this->define_asset_hook();

	}// Method


	/* Method
	_________________________
	*/
	private function set_locale()
	{
		/**
			@access (private)
				The class responsible for defining internationalization functionality of the plugin.
				Define the locale for this plugin for internationalization.
				Uses the Beans_Extension_i18n class in order to set the domain and to register the hook with WordPress.
			@return (void)
			@reference (WP)
				Get the filesystem directory path (with trailing slash) for the plugin __FILE__ passed in.
				https://developer.wordpress.org/reference/functions/plugin_dir_path/
		*/
		require_once plugin_dir_path(dirname(__FILE__)) . 'include/i18n.php';

	}// Method


	/* Method
	_________________________
	*/
	private function load_component()
	{
		/**
			@access (private)
				The class responsible for defining all actions that occur in the public-facing side of the site.
			@return (void)
			@reference (WP)
				Get the filesystem directory path (with trailing slash) for the plugin __FILE__ passed in.
				https://developer.wordpress.org/reference/functions/plugin_dir_path/
		*/
		require_once plugin_dir_path(dirname(__FILE__)) . 'include/component.php';

	}// Method


	/* Method
	_________________________
	*/
	private function define_admin_hook()
	{
		/**
			@access (private)
				The class responsible for defining all actions that occur in the admin area.
				Register all of the hooks related to the admin area functionality of the plugin.
			@return (void)
			@reference (WP)
				Get the filesystem directory path (with trailing slash) for the plugin __FILE__ passed in.
				https://developer.wordpress.org/reference/functions/plugin_dir_path/
		*/
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/beans.php';

	}// Method


	/* Method
	_________________________
	*/
	private function define_asset_hook()
	{
		/**
			@access (private)
				The class responsible for defining all actions that occur in the admin area.
				Register all of the hooks related to the public-facing functionality of the plugin.
			@return (void)
			@reference (WP)
				Get the filesystem directory path (with trailing slash) for the plugin __FILE__ passed in.
				https://developer.wordpress.org/reference/functions/plugin_dir_path/
		*/
		require_once plugin_dir_path(dirname(__FILE__)) . 'asset/beans.php';

	}// Method


	/**
		@access (private)
			The name of the plugin used to uniquely identify it within the context of WordPress and to define internationalization functionality.
		@return (string)
			The name of the plugin.
	*/
	private function get_plugin_name()
	{
		return $this->plugin_name;

	}// Method


	/**
		@access (private)
			Retrieve the version number of the plugin.
		@return (string)
			The version number of the plugin.
	*/
	private function get_version()
	{
		return $this->version;

	}// Method


}// Class
endif;
// new _beans_core();
_beans_core::__get_instance();
