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
	 * 	CSS section of general tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_css_general_setting') === FALSE) :
	function __beans_admin_css_general_setting()
	{
		return array(
			// 'css_framework' => array(
			'uikit2_component_range' => array(
				'label' => esc_html__('Range of Uikit2 Components to use','beans-extension'),
				'type' => 'select',
				'default' => 0,
				'group' => 'general',
				'section' => 'css',
			),
			'use_uikit3_cdn' => array(
				'label' => esc_html__('Use Uikit3 via CDN','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'general',
				'section' => 'css',
			),
		);

	}// Method
	endif;
