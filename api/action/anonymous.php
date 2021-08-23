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
if(class_exists('_beans_anonymous_action') === FALSE) :
class _beans_anonymous_action
{
/**
 * @since 1.0.1
 * 	Anonymous Action.
 * 	This class creates an anonymous callback,which is required since Beans still supports PHP 5.2.
 * 
 * [TOC]
 * 	__construct()
 * 	callback()
*/

	/**
		@access(private)
			Class properties.
		@var (string) $_callback
			the callback to register to the given
	*/
	private $callback;


	/* Constructor
	_________________________
	*/
	public function __construct($hook,array $callback,$priority = 10,$args = 1)
	{
		/**
			@access (public)
				Constructor.
			@param (string) $hook
				The name of the action to which the $callback is hooked.
			@param (array)$callback
				The callback to register to the given $hook and arguments to pass.
			@param (int) $priority
				[Optional]
				Used to specify the order in which the functions associated with a particular action are executed.
				[Default] 10.
				Lower numbers correspond with earlier execution,and functions with the same priority are executed in the order in which they were added to the action.
			@param (int) $args...,
				[Optional]
				The number of arguments the function accepts.
				[Default] 1.
			@return (void)
		*/

		// Init properties.
		$this->callback = $callback;

		// Register hooks.
		add_action($hook,[$this,'callback'],$priority,$args);

	}// Method


	/* Method
	_________________________
	*/
	public function callback()
	{
		/**
			@access (public)
				Get action content and set it as the callback.
			@return (void)
		*/

		/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- The callback handles escaping its output, as Beans does not know what HTML or content will be passed back to it. */
		echo call_user_func_array($this->callback[0],$this->callback[1]);

	}// Method


}// Class
endif;
