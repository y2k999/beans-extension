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
	 * 	Settings for Singular Section of Layout tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_singular_layout_setting') === FALSE) :
	function __beans_admin_singular_layout_setting()
	{
		return array(
			'single' => array(
				'label' => esc_html__("Layout for Single Posts(single.php)",'beans-extension'),
				'type' => 'radio_image',
				'default' => 'c_sp',
				'group' => 'layout',
				'section' => 'singular',
			),
			'page' => array(
				'label' => esc_html__("Layout for Single Pages(page.php)",'beans-extension'),
				'type' => 'radio_image',
				'default' => 'c_sp',
				'group' => 'layout',
				'section' => 'singular',
			),
		);

	}// Method
	endif;
