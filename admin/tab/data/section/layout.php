<?php
/**
 * Define default values.
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
	 * @access (public)
	 * 	Title and description for layout tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_layout_section') === FALSE) :
	function __beans_admin_layout_section()
	{
		return array(
			'editor' => array(
				'title' => esc_html__('Layout Settings','beans-extension'),
				'description' => esc_html__('Show Page Layouts on Post Editor and Category Editor.','beans-extension'),
			),
			'singular' => array(
				'title' => esc_html__('Singular Pages Layout','beans-extension'),
				'description' => esc_html__('Select Layout for Single Posts and Pages.','beans-extension'),
			),
			'archive' => array(
				'title' => esc_html__('Archive Pages Layout','beans-extension'),
				'description' => esc_html__('Select Layout for Archive Pages.','beans-extension'),
			),
		);

	}// Method
	endif;
