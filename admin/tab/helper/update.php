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
 * @reference (WP)
 * 	Updates the value of an option that was already added.
 * 	https://developer.wordpress.org/reference/functions/update_option/
 * [TOC]
 * 	__beans_admin_update_boolean()
 * 	__beans_admin_update_integer()
 * 	__beans_admin_update_string()
 * 	__beans_admin_update_select()
 * 	__beans_admin_update_radio()
*/

	/* Method
	_________________________
	*/
	if(function_exists('__beans_admin_update_boolean') === FALSE) :
	function __beans_admin_update_boolean($option,$value)
	{
		/**
			@access (public)
				Update/sanitize value of boolean type.
			@param (string) $option
				Name of the option to update.
				Expected to not be SQL-escaped.
			@param (int) $value
				Option value.
				Must be serializable if non-scalar. Expected to not be SQL-escaped.
			@return (void)
			@reference
				[Plugin]/admin/tab/general.php
				[Plugin]/admin/tab/layout.php
		*/
		if(!isset($option)){return;}

		// Implement
		if($value == 1 || $value == TRUE){
			update_option($option,1);
		}
		else{
			update_option($option,0);
		}

	}// Method
	endif;


	/* Method
	_________________________
	*/
	if(function_exists('__beans_admin_update_integer') === FALSE) :
	function __beans_admin_update_integer($option,$value)
	{
		/**
			@access (public)
				Update/sanitize value of number type.
			@param (string) $option
				Name of the option to update.
				Expected to not be SQL-escaped.
			@param (int) $value
				Option value.
				Must be serializable if non-scalar.
				Expected to not be SQL-escaped.
			@return (void)
			@reference
				[Plugin]/admin/tab/general.php
				[Plugin]/admin/tab/layout.php
		*/
		if(!isset($option)){return;}

		// Implement
		if(isset($value) && is_numeric($value)){
			update_option($option,$value);
		}
		else{
			update_option($option,'');
		}

	}// Method
	endif;


	/* Method
	_________________________
	*/
	if(function_exists('__beans_admin_update_string') === FALSE) :
	function __beans_admin_update_string($option,$value)
	{
		/**
			@access (public)
				Update/sanitize value of string type.
			@param (string) $option
				Name of the option to update.
				Expected to not be SQL-escaped.
			@param (string) $value
				Option value.
				Must be serializable if non-scalar.
				Expected to not be SQL-escaped.
			@return (void)
			@reference
				[Plugin]/admin/tab/general.php
				[Plugin]/admin/tab/layout.php
		*/
		if(!isset($option)){return;}

		// Implement
		if(isset($value) && is_string($value)){
			update_option($option,$value);
		}
		else{
			update_option($option,'');
		}

	}// Method
	endif;


	/* Method
	_________________________
	*/
	if(function_exists('__beans_admin_update_select') === FALSE) :
	function __beans_admin_update_select($option,$value)
	{
		/**
			@access (public)
				Update/sanitize value of select type.
			@param (string) $option
				Name of the option to update.
				Expected to not be SQL-escaped.
			@param (string) $value
				Option value.
				Must be serializable if non-scalar.
				Expected to not be SQL-escaped.
			@return (void)
			@reference
				[Plugin]/admin/tab/general.php
				[Plugin]/admin/tab/layout.php
		*/
		if(!isset($option)){return;}

		// Implement
		if(isset($value)){
			update_option($option,$value);
		}
		else{
			update_option($option,'');
		}

	}// Method
	endif;


	/* Method
	_________________________
	*/
	if(function_exists('__beans_admin_update_radio') === FALSE) :
	function __beans_admin_update_radio($option,$value)
	{
		/**
			@access (public)
				Update/sanitize value of image type.
			@param (string) $option
				Name of the option to update.
				Expected to not be SQL-escaped.
			@param (string) $value
				Option value.
				Must be serializable if non-scalar.
				Expected to not be SQL-escaped.
			@return (void)
			@reference
				[Plugin]/admin/tab/general.php
				[Plugin]/admin/tab/layout.php
		*/
		if(!isset($option)){return;}

		// Implement
		switch($value){
			case 'c_sp' :
				update_option($option,'c_sp');
				break;
			case 'c' :
				update_option($option,'c');
				break;
			case 'list' :
				update_option($option,'list');
				break;
			case 'card' :
				update_option($option,'card');
				break;
			default :
				break;
		}

	}// Method
	endif;
