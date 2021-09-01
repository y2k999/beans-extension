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
if(class_exists('_beans_admin_option_data') === FALSE) :
class _beans_admin_option_data
{
/**
 * @since 1.0.1
 * 	Option values for SELECT type settings.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_option()
 * 	__get_setting()
*/

	/**
		@access (private)
			Class properties.
		@var (array) $_option
			Option values for <select> markup of the settings API fields.
	*/
	private static $_option = array();


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
		self::$_option = $this->set_option();

	}// Method


	/* Method
	_________________________
	*/
	private function set_option()
	{
		/**
			@access (private)
				Option valuefor css framework setting of general tab/group.
			@return (array)
			@reference
				[Plugin]/admin/tab/general.php
		*/
		return array(
			'uikit_version' => array(
				'uikit3' => 'Latest Uikit3 (via CDN)',
				'uikit2' => 'Legacy Uikit2 (LESS files)',
			),
		);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_setting($needle = '')
	{
		/**
			@access (public)
			@param (string) $needle
			@return (array)
		 */
		if(!isset($needle) || !array_key_exists($needle,self::$_option)){return;}

		return self::$_option[$needle];

	}// Method


}// Class
endif;
// new _beans_admin_option_data();
_beans_admin_option_data::__get_instance();
