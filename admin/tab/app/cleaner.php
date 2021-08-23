<?php
/**
 * Define application module for the tab/group.
 * @package Beans_Extension
 * @license GPL3.0+
 * @since 1.0.1
*/

/**
 * Inspired by AE Admin Customizer WordPress Plugin
 * @link https://wordpress.org/plugins/ae-admin-customizer/
 * @author Allan Empalmado
 * 
 * Inspired by Speed Up - Clean WP WordPress Plugin
 * @link http://wordpress.org/plugins/speed-up-clean-wp/
 * @author Simone Nigro
 * 
 * Inspired by wp_head() cleaner WordPress Plugin
 * @link https://wordpress.org/plugins/wp-head-cleaner/
 * @author Jonathan Wilsson
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
if(class_exists('_beans_admin_cleaner_app') === FALSE) :
class _beans_admin_cleaner_app
{
/**
 * @since 1.0.1
 * 	Execute remove actions.
 * 
 * [TOC]
 * 	__construct()
 * 	set_registerd()
 * 	set_hook()
 * 	invoke_hook()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $_class
			Name/Identifier with prefix.
		@var (string) $_index
			Name/Identifier without prefix.
		@var (array) $registerd
			The actions registered with WordPress to be removed.
		@var (array) $hook
			The collection of hooks that is being registered (that is, actions or filters).
	*/
	private static $_class = '';
	private static $_index = '';
	private $registerd = array();
	private $hook = array();


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

		// Init properties.
		self::$_class = __utility_get_class(get_class($this));
		self::$_index = basename(__FILE__,'.php');

		$this->registerd = $this->set_registerd();

		// Register hooks.
		$this->hook = $this->set_hook();
		$this->invoke_hook();

	}// Method


	/* Setter
	_________________________
	*/
	private function set_registerd()
	{
		/**
			@access (private)
			@return (array)
				The actions registered with WordPress to be removed.
		*/
		return array(
			// oEmbed
			'wp_oembed_add_discovery_links' => array(
				'hook' => 'wp_head',
				'priority' => BEANS_EXTENSION_PRIORITY['default'],
			),
			'wp_oembed_add_host_js' => array(
				'hook' => 'wp_head',
				'priority' => BEANS_EXTENSION_PRIORITY['default'],
			),
			// REST
			'rsd_link' => array(
				'hook' => 'wp_head',
				'priority' => BEANS_EXTENSION_PRIORITY['default'],
			),
			'rest_output_link_wp_head' => array(
				'hook' => 'wp_head',
				'priority' => BEANS_EXTENSION_PRIORITY['default'],
			),
			// Feed
			'feed_links' => array(
				'hook' => 'wp_head',
				'priority' => 2,
			),
			'feed_links_extra' => array(
				'hook' => 'wp_head',
				'priority' => 3,
			),
			// Emoji
			'print_emoji_detection_script' => array(
				'hook' => 'wp_head',
				'priority' => 7,
			),
			'print_emoji_styles' => array(
				'hook' => 'wp_print_styles',
				'priority' => BEANS_EXTENSION_PRIORITY['default'],
			),
			// wp_head()
			'wp_generator' => array(
				'hook' => 'wp_head',
				'priority' => BEANS_EXTENSION_PRIORITY['default'],
			),
			'wp_shortlink_wp_head' => array(
				'hook' => 'wp_head',
				'priority' => BEANS_EXTENSION_PRIORITY['default'],
			),
			'wlwmanifest_link' => array(
				'hook' => 'wp_head',
				'priority' => BEANS_EXTENSION_PRIORITY['default'],
			),
			'wp_resource_hints' => array(
				'hook' => 'wp_head',
				'priority' => 2,
			),
			'adjacent_posts_rel_link_wp_head' => array(
				'hook' => 'wp_head',
				'priority' => BEANS_EXTENSION_PRIORITY['default'],
			),
			'start_post_rel_link' => array(
				'hook' => 'wp_head',
				'priority' => BEANS_EXTENSION_PRIORITY['default'],
			),
			'index_rel_link' => array(
				'hook' => 'wp_head',
				'priority' => BEANS_EXTENSION_PRIORITY['default'],
			),
			'rel_canonical' => array(
				'hook' => 'wp_head',
				'priority' => BEANS_EXTENSION_PRIORITY['default'],
			),
		);

	}// Method


	/* Setter
	_________________________
	*/
	private function set_hook()
	{
		/**
			@access (private)
			@return (array)
				The collection of hooks that is being registered (that is, actions or filters).
			@reference
				[Plugin]/admin/tab/helper/misc.php
				[Plugin]/trait/hook.php
				[Plugin]/include/constant.php
		*/
		// bx_option_{tab_name}
		$option = get_option(BEANS_EXTENSION_PREFIX['option'] . self::$_index);
		if(empty($option)){return;}

		if(empty($this->registerd)){return;}

		$return = array();

		foreach($this->registerd as $key => $value){
			if(__beans_admin_boolean_check(self::$_index,$key)){
				$return[$key] = $value;
			}
		}
		return $return;

	}// Method


	/* Method
	_________________________
	*/
	public function invoke_hook()
	{
		/**
			@access (public)
				Removes a callback function from an action hook.
				https://developer.wordpress.org/reference/functions/remove_action/
			@return (void)
		*/
		if(empty($this->hook)){return;}

		foreach($this->hook as $key => $value){
			remove_action($value['hook'],$key,$value['priority']);
		}

	}// Method


}// Class
endif;
// new _beans_admin_column_app();
// _beans_admin_column_app::__get_instance();
