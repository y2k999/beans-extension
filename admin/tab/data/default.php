<?php 
/**
 * Prepare meta data for this application building.
 * @package Beans_Extension
 * @license GPL3.0+
 * @since 1.0.1
*/

/**
 * Inspired by AE Admin Customizer WordPress Plugin
 * @link https://wordpress.org/plugins/ae-admin-customizer/
 * @author Allan Empalmado
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
if(class_exists('_beans_admin_default_data') === FALSE) :
class _beans_admin_default_data
{
/**
 * @since 1.0.1
 * 	Define default values for each setting.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_general()
 * 	set_column()
 * 	set_image()
 * 	set_layout()
 * 	set_cleaner()
 * 	__get_setting()
*/

	/**
		@access (private)
			Class properties.
		@var (array) $_general
			Settings API group.
		@var (array) $_column
			Settings API group.
		@var (array) $_image
			Settings API group.
		@var (array) $_layout
			Settings API group.
		@var (array) $_cleaner
			Settings API group.
	*/
	private static $_general = array();
	private static $_column = array();
	private static $_image = array();
	private static $_layout = array();
	private static $_cleaner = array();


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

		// Init properties.
		self::$_general = $this->set_general();
		self::$_column = $this->set_column();
		self::$_image = $this->set_image();
		self::$_layout = $this->set_layout();
		self::$_cleaner = $this->set_cleaner();

	}// Method


	/* Setter
	_________________________
	*/
	private function set_general()
	{
		/**
			@access (private)
				Default values for the fields of Settings API group.
			@return (array)
			@reference
				[Plugin]/admin/tab/general.php
		*/
		return array(
			'api' => array(
				'stop_beans_image' => 0,
				'stop_beans_widget' => 0,
				'stop_beans_customizer' => 0,
			),
			'module' => array(
				'beans_legacy_layout' => 0,
				'beans_accessibility' => 0,
			),
			'path' => array(
				'theme_template_path' => 'lib/templates/',
				'theme_structure_path' => 'lib/templates/structure/',
				'theme_fragment_path' => 'lib/templates/fragments/',
			),
			'css' => array(
				'uikit_version' => 'uikit3',
			),
		);

	}// Method


	/* Setter
	_________________________
	*/
	private function set_column()
	{
		/**
			@access (private)
				Default values for the fields of Settings API group.
			@return (array)
			@reference
				[Plugin]/admin/tab/column.php
		 */
		return array(
			'post' => array(
				'id_post' => 1,
				'thumbnail_post' => 1,
				'wordcount_post' => 1,
				'pv_post' => 1,
			),
			'page' => array(
				'id_page' => 0,
				'thumbnail_page' => 0,
				'template_page' => 0,
				'slug_page' => 0,
			),
			'profile' => array(
				'account_twitter' => 1,
				'account_facebook' => 1,
				'account_instagram' => 1,
				'account_github' => 1,
				'account_youtube' => 1,
			),
		);

	}// Method


	/* Setter
	_________________________
	*/
	private function set_image()
	{
		/**
			@access (private)
				Default values for the fields of Settings API group.
			@return (array)
			@reference
				[Plugin]/admin/tab/image.php
				[Plugin]/include/constant.php
		*/
		return array(
			'configuration' => array(
				'autosave_thumbnail' => 1,
				'num_of_categories' => 5,
			),
			'eyecatch' => array(
/*
				0 => BEANS_EXTENSION_API_URL['asset'] . 'image/misc/nopost.jpg',
				1 => BEANS_EXTENSION_API_URL['asset'] . 'image/misc/nopost.jpg',
				2 => BEANS_EXTENSION_API_URL['asset'] . 'image/misc/nopost.jpg',
				3 => BEANS_EXTENSION_API_URL['asset'] . 'image/misc/nopost.jpg',
				4 => BEANS_EXTENSION_API_URL['asset'] . 'image/misc/nopost.jpg',
*/
			),
			'picture' => array(
				'profile' => BEANS_EXTENSION_API_URL['asset'] . 'image/misc/nopost.jpg',
				'nopost' => BEANS_EXTENSION_API_URL['asset'] . 'image/misc/nopost.jpg',
			),
		);

	}// Method


	/* Setter
	_________________________
	*/
	private function set_layout()
	{
		/**
			@access (private)
				Default values for the fields of Settings API group.
			@return (array)
			@reference
				[Plugin]/admin/tab/layout.php
		*/
		return array(
			'editor' => array(
				'post_meta' => 0,
				'term_meta' => 0,
			),
			'singular' => array(
				'single' => 'c_sp',
				'page' => 'c_sp',
			),
			'archive' => array(
				'home' => 'list',
				'archive' => 'card',
			),
		);

	}// Method


	/* Setter
	_________________________
	*/
	private function set_cleaner()
	{
		/**
			@access (private)
				Default values for the fields of Settings API group.
			@return (array)
			@reference
				[Plugin]/admin/tab/cleaner.php
		*/
		return array(
			'oembed' => array(
				'wp_oembed_add_discovery_links' => 0,
				'wp_oembed_add_host_js' => 0,
			),
			'rest' => array(
				'rsd_link' => 0,
				'rest_output_link_wp_head' => 0,
			),
			'feed' => array(
				'feed_links' => 1,
				'feed_links_extra' => 1,
			),
			'emoji' => array(
				'print_emoji_detection_script' => 1,
				'print_emoji_styles' => 1,
			),
			'wphead' => array(
				'wp_generator' => 1,
				'wp_shortlink_wp_head' => 1,
				'wlwmanifest_link' => 1,
				'wp_resource_hints' => 1,
				'adjacent_posts_rel_link_wp_head' => 1,
				'start_post_rel_link' => 1,
				'index_rel_link' => 1,
				'rel_canonical' => 1,
			),
		);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_setting($group,$section,$setting)
	{
		/**
			@access (public)
			@param (string) $group
			@param (string) $section
			@param (string) $setting
			@return (array)
		*/
		if(!isset($group)){return;}

		switch($group){
			case 'general' :
				if(!isset($section)){return;}
				if(!isset($setting) || !array_key_exists($setting,self::$_general[$section])){return;}
				return self::$_general[$section][$setting];
				break;

			case 'column' :
				if(!isset($section)){return;}
				if(!isset($setting) || !array_key_exists($setting,self::$_column[$section])){return;}
				return self::$_column[$section][$setting];
				break;

			case 'image' :
				if(!isset($section)){return;}
				if(!isset($setting) || !array_key_exists($setting,self::$_image[$section])){return;}
				return self::$_image[$section][$setting];
				break;

			case 'layout' :
				if(!isset($section)){return;}
				if(!isset($setting) || !array_key_exists($setting,self::$_layout[$section])){return;}
				return self::$_layout[$section][$setting];
				break;

			case 'cleaner' :
				if(!isset($section)){return;}
				if(!isset($setting) || !array_key_exists($setting,self::$_cleaner[$section])){return;}
				return self::$_cleaner[$section][$setting];
				break;

			default :
				break;
		}

	}// Method


}// Class
endif;
// new _beans_admin_default_data();
_beans_admin_default_data::__get_instance();
