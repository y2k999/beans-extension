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
if(class_exists('_beans_admin_source_data') === FALSE) :
class _beans_admin_source_data
{
/**
 * @since 1.0.1
 * 	Structural source for each tabs/groups.
 * 	Setting API of each tabs/groups are generated from this source.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_group()
 * 	set_section()
 * 	set_setting()
 * 	__get_section()
 * 	__get_setting()
*/

	/**
		@access (private)
			Class properties.
		@var (array) $_group
			Part of the Settings API.
		@var (array) $_section
			Part of the Settings API.
		@var (array) $_setting
			Part of the Settings API.
	*/
	private static $_group = array();
	private static $_section = array();
	private static $_setting = array();


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
		self::$_group = $this->set_group();
		self::$_section = $this->set_section();
		self::$_setting = $this->set_setting();

	}// Method


	/* Method
	_________________________
	*/
	private function set_group()
	{
		/**
			@access (private)
				Tab groups.
			@return (array)
			@reference
				[Plugin]/admin/beans.php
				[Plugin]/admin/tab/xxx.php
		*/
		return array(
			'general',
			'column',
			'image',
			'layout',
			'cleaner',
		);

	}// Method


	/* Method
	_________________________
	*/
	private function set_section()
	{
		/**
			@access (private)
				Sections in each tab/group.
			@return (array)
			@reference
				[Plugin]/admin/tab/data/section/xxx.php
				[Plugin]/include/constant.php
		*/
		$return = array();

		foreach(self::$_group as $group){
			require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/data/section/' . $group . '.php';
			$method = __NAMESPACE__ . '\__beans_admin_' . $group . '_section';
			$return[$group] = call_user_func($method);
		}
		return $return;

	}// Method


	/* Method
	_________________________
	*/
	private function set_setting()
	{
		/**
			@access (public)
				Settings in each section.
			@return (array)
			@reference
				[Plugin]/admin/tab/data/setting/xxx.php
				[Plugin]/include/constant.php
		*/
		$return = array();

		foreach(self::$_group as $group){
			foreach(self::$_section[$group] as $key => $value){
				require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/data/setting/' . $group . DIRECTORY_SEPARATOR . $key . '.php';
				$method = __NAMESPACE__ . '\__beans_admin_' . $key . '_' . $group . '_setting';
				$return[$key] = call_user_func($method);
			}
		}
		return $return;

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_section($group = '')
	{
		/**
			@access (public)
			@param (string) $group
			@return (array)
		*/
		if(!isset($group) || !array_key_exists($group,self::$_section)){return;}
		return self::$_section[$group];

	}// Method


	/* Method
	_________________________
	 */
	public static function __get_setting($section = '')
	{
		/**
			@access (public)
			@param (string) $section
			@return (array)
		*/
		if(!isset($section) || !array_key_exists($section,self::$_setting)){return;}
		return self::$_setting[$section];

	}// Method


}// Class
endif;
// new _beans_admin_source_data();
_beans_admin_source_data::__get_instance();
