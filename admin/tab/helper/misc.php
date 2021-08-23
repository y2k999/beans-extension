<?php
/**
 * Define helper functions.
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
/**
 * [TOC]
 * 	__beans_admin_boolean_check()
*/

	/**
		@access (public)
		@param (string) $group
		@param (string) $setting
		@return (bool)
		@reference
			[Plugin]/admin/tab/app/column.php
			[Plugin]/include/constant.php
	*/
	if(function_exists('__beans_admin_boolean_check') === FALSE) :
	function __beans_admin_boolean_check($group,$setting)
	{
		if(!$group || !$setting){return;}

		// bx_option_{tab_name}
		$option = get_option(BEANS_EXTENSION_PREFIX['option'] . $group);

		// bx_setting_{tab_name}_{setting_name}
		$needle = BEANS_EXTENSION_PREFIX['setting'] . $group . '_' . $setting;

		if(!empty($option) && $option[$needle]){
			return TRUE;
		}
		else{
			return FALSE;
		}

	}// Method
	endif;
