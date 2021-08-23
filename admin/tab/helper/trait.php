<?php
/**
 * Trait file for Beans admin menu.
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
if(trait_exists('_trait_admin') === FALSE) :
trait _trait_admin
{
/**
 * [TOC]
 * 	set_section()
 * 	set_setting()
 * 	sanitize()
 * 	markup()
 * 	describe()
*/


	/* Setter
	_________________________
	*/
	public function set_section($index = '')
	{
		/**
			@access (public)
			@param (string) $index
			@return (array)
			@reference
				[Plugin]/admin/tab/data/source.php
		*/
		if(!isset($index)){
			$index = self::$_index;
		}
		return _beans_admin_source_data::__get_section($index);

	}// Method


	/* Setter
	_________________________
	*/
	public function set_setting($section = array())
	{
		/**
			@access (public)
			@param (array) $section
			@return (array)
			@reference
				[Plugin]/admin/tab/data/source.php
		*/
		if(empty($section)){return;}

		$setting = array();

		foreach((array)$section as $key => $value){
			$setting[$key] = _beans_admin_source_data::__get_setting($key);
		}
		return $setting;

	}// Method


	/* Method
	_________________________
	*/
	public function sanitize($input)
	{
		/**
			@access (public)
			@param (mixed) $input
			@return (void)
				[Plugin]/include/constant.php
		*/
		$new_input = array();

		// bx_option_{tab_name}
		// $option = get_option(BEANS_EXTENSION_PREFIX['option'] . self::$_index);

		foreach((array)self::$_section as $section_key => $section_value){

			foreach((array)self::$_setting[$section_key] as $setting_key => $setting_value){

				// bx_settings_{tab_name}_{setting_name}
				$needle = BEANS_EXTENSION_PREFIX['setting'] . self::$_index . '_' . $setting_key;

				switch($setting_value['type']){
					case 'boolean' :
						if(isset($input[$needle]) && $input[$needle] == 1){
							$new_input[$needle] = 1;
						}
						else{
							$new_input[$needle] = 0;
						}
						break;

					case 'integer' :
						if(isset($input[$needle])){
							$new_input[$needle] = absint($input[$needle]);
						}
						break;

					case 'string' :
						if(isset($input[$needle])){
							$new_input[$needle] = wp_kses_post($input[$needle]);
						}
						break;

					case 'checkbox' :
						if(isset($input[$needle])){
							$new_input[$needle] = (isset($input[$needle]) && TRUE == $input[$needle]) ? TRUE : FALSE;
						}
						break;

					case 'image' :
						if(isset($input[$needle])){
							$new_input[$needle] = esc_url_raw($input[$needle]);
						}
						break;

					case 'select' :
					case 'radio_image' :
					default :
						$new_input[$needle] = $input[$needle];
						break;
				}
			}
		}
		return $new_input;

	}// Method


	/* Method
	_________________________
	*/
	public function markup($setting,$value)
	{
		/**
			@access (public)
			@param (string) $setting
			@param (string) $value
			@return (void)
			@reference
				[Plugin]/admin/tab/helper/echo.php
				[Plugin]/include/constant.php
		*/
		if(!isset($setting) && !isset($value['type'])){return;}

		$needle = BEANS_EXTENSION_PREFIX['setting'] . self::$_index . '_' . $setting;
		$option = get_option(BEANS_EXTENSION_PREFIX['option'] . self::$_index);

		if($value['type'] === 'image'){
			$data = isset($option[$needle]) ? $option[$needle] : BEANS_EXTENSION_API_URL['asset'] . 'image/misc/nopost.jpg';
		}
		else{
			$data = isset($option[$needle]) ? $option[$needle] : _beans_admin_default_data::__get_setting(self::$_index,$value['section'],$setting);
		}

		switch($value['type']){
			case 'boolean' :
				__beans_admin_echo_boolean($needle,BEANS_EXTENSION_PREFIX['option'] . self::$_index,$data);
				break;

			case 'integer' :
				__beans_admin_echo_integer($needle,BEANS_EXTENSION_PREFIX['option'] . self::$_index,$data);
				break;

			case 'image' :
				__beans_admin_echo_image($needle,BEANS_EXTENSION_PREFIX['option'] . self::$_index,$data);
				break;

			case 'radio_image' :
				__beans_admin_echo_radio($needle,BEANS_EXTENSION_PREFIX['option'] . self::$_index,$data);
				break;

			case 'select' :
				__beans_admin_echo_select($setting,$needle,BEANS_EXTENSION_PREFIX['option'] . self::$_index,$data);
				break;

			case 'string' :
			default :
				__beans_admin_echo_string($needle,BEANS_EXTENSION_PREFIX['option'] . self::$_index,$data);
				break;

		}

	}// Method


	/* Method
	_________________________
	*/
	public function describe($description = '')
	{
		/**
			@access (public)
				Echo section lead sentence.
			@param (string) $description
			@return (void)
		*/
		if(!isset($description)){return;}
		?><p><?php echo $description; ?></p><?php

	}// Method


}// Trait
endif;
