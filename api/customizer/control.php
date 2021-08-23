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

// Customize Control class.
use WP_Customize_Control;

// Check.
if(!class_exists('WP_Customize_Control')){return;}


/* Exec
______________________________
*/
if(class_exists('_beans_control_customizer') === FALSE) :
class _beans_control_customizer extends WP_Customize_Control
{
/**
 * @reference (WP)
 * 	Render Beans fields content for WP Customize.
 * 	https://developer.wordpress.org/reference/classes/wp_customize_control/
 * 	This class controls the rendering of the Beans fields for WP Customize.
 * 
 * [TOC]
 * 	__construct()
 * 	render_content()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $beans_field
			Field data.
	*/
	private $beans_field;


	/* Constructor
	_________________________
	*/
	public function __construct()
	{
		/**
			@access (public)
				Class constructor.
			@return (void)
			@reference
				[Plugin]/api/field/beans.php
		*/

		// Init properties.
		$args = func_get_args();
		call_user_func_array(['parent','__construct'],$args);
		$this->beans_field = end($args);

	}// Method


	/* Method
	_________________________
	*/
	public function render()
	{
		/**
			[ORIGINAL]
				render_content()
			@access (public)
				Field content.
			@return (void)
			@reference
				[Plugin]/api/field/beans.php
		*/

		/**
		 * @reference (Beans)
		 * 	Render (echo) a field.
		 * 	https://www.getbeans.io/code-reference/functions/beans_field/
		*/
		_beans_field::__render($this->beans_field);

	}// Method


}// Class
endif;
