<?php
/**
 * A set of tools to ease building applications.
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
/**
 * @since 1.0.1
 * 	Common utilities for this plugin.
 * 
 * [TOC]
 * 	__utility_get_class()
 * 	__utility_get_index()
 * 	__utility_get_function()
*/


	/* Method
	_________________________
	*/
	if(function_exists('__utility_get_class') === FALSE) :
	function __utility_get_class($class = '')
	{
		/**
			@access (public)
				Returns the trimmed name of the class.
			@param (string) $class
				The name of the class of an object.
				The result of get_class() PHP class.
			@return (string)
			@reference
				Used in almost all of the class files in this plugin.
		*/
		if(!isset($class)){return;}

		$exploded = explode('\\',$class);
		return strtolower(end($exploded));

	}// Method
	endif;


	/* Method
	_________________________
	*/
	if(function_exists('__utility_get_index') === FALSE) :
	function __utility_get_index($class = '')
	{
		/**
			@access (public)
				Returns the name of the class without prefix.
			@param (string) $class
				The trimmed name of the class (The result of __utility_get_class()).
			@return (string)
			@reference
				Used in almost all of the class files in this plugin.
				[Plugin]/inc/constant.php
		*/
		if(!isset($class)){return;}

		$exploded = explode('\\',$class);
		$classname = strtolower(end($exploded));

		if(substr($class_name,0,5) === BEANS_EXTENSION_PREFIX['tab']){
			return strtolower(str_replace(substr($classname,0,5),'',$classname));
		}
		elseif(substr($classname,0,7) === BEANS_EXTENSION_PREFIX['model']){
			return strtolower(str_replace(substr($classname,0,7),'',$classname));
		}
		else{
			return strtolower($classname);
		}

	}// Method
	endif;


	/* Method
	_________________________
	*/
	if(function_exists('__utility_get_function') === FALSE) :
	function __utility_get_function($function = '')
	{
		/**
			@access (public)
				Returns the name of the function/method without prefix.
			@param (string) $function
				The function name (__FUNCTION__).
			@return (string)
			@reference
				Used in almost all of the class files in this plugin.
		*/
		if(!isset($function)){return;}

		if(preg_match("/^__is_/",$function)){
			return str_replace(substr($function,0,5),'',$function);
		}
		elseif(preg_match("/^__get_/",$function)){
			return str_replace(substr($function,0,6),'',$function);
		}
		elseif(preg_match("/^__set_/",$function)){
			return str_replace(substr($function,0,6),'',$function);
		}
		elseif(preg_match("/^__the_/",$function)){
			return str_replace(substr($function,0,6),'',$function);
		}
		elseif(preg_match("/^__hook_/",$function)){
			return str_replace(substr($function,0,7),'',$function);
		}
		elseif(preg_match("/^__init_/",$function)){
			return str_replace(substr($function,0,7),'',$function);
		}
		elseif(preg_match("/^__check_/",$function)){
			return str_replace(substr($function,0,8),'',$function);
		}
		elseif(preg_match("/^__apply_/",$function)){
			return str_replace(substr($function,0,8),'',$function);
		}
		elseif(preg_match("/^__render_/",$function)){
			return str_replace(substr($function,0,9),'',$function);
		}
		else{
			return $function;
		}

	}// Method
	endif;
