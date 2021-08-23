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
	 * 	Archive section of layout tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_archive_layout_setting') === FALSE) :
	function __beans_admin_archive_layout_setting()
	{
		return array(
			'home' => array(
				'label' => esc_html__("Layout for Blog Page(home.php)",'beans-extension'),
				'type' => 'radio_image',
				'default' => 'list',
				'group' => 'layout',
				'section' => 'archive',
			),
			'archive' => array(
				'label' => esc_html__("Layout for Archive Page(archive.php)",'beans-extension'),
				'type' => 'radio_image',
				'default' => 'card',
				'group' => 'layout',
				'section' => 'archive',
			),
		);

	}// Method
	endif;
