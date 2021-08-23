<?php
/**
 * Define Beans API classes.
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
if(class_exists('_beans_anonymous_filter') === FALSE) :
final class _beans_anonymous_filter
{
/**
 * @since 1.0.1
 * 	Anonymous Filter.
 * 	This class creates an anonymous callback,which is required since Beans still supports PHP 5.2.
 * 
 * [TOC]
 * 	__construct()
 * 	callback()
*/

	/**
		@access(private)
			Class properties.
		@var (string) $_value_to_return
			The value that will be returned when this anonymous callback runs.
	*/
	private $value_to_return;


	/* Constructor
	_________________________
	*/
	public function __construct($hook,$value_to_return,$priority = 10,$args = 1)
	{
		/**
			@access (public)
				Class constructor.
			@param (string) $hook
				The name of the filter to which the $callback is hooked.
			@param (mixed)$value_to_return
				The value that will be returned when this anonymous callback runs.
			@param (int) $priority...
				[Optional]
				Used to specify the order in which the functions associated with a particular filter are executed.
				[Default] 10
				Lower numbers correspond with earlier execution,and functions with the same priority are executed in the order in which they were added to the filter.
			@param (int) $args...
				[Optional]
				The number of arguments the function accepts.
				[Default] 1
			@return (void)
		*/

		// Init properties.
		$this->value_to_return = $value_to_return;

		/**
		 * @since 1.0.1
		 * 	Register hooks.
		 * @reference (WP)
		 * 	Hook a function or method to a specific filter action.
		 * 	https://developer.wordpress.org/reference/functions/add_filter/
		*/
		add_filter($hook,[$this,'callback'],$priority,$args);

	}// Method


	/* Hook
	_________________________
	*/
	public function callback()
	{
		/**
			@access (public)
				Get filter content and set it as the callback.
			@return (array)
		*/
		return $this->value_to_return;

	}// Method


}// Class
endif;
