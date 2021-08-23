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
	 * 	API section of general tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_api_general_setting') === FALSE) :
	function __beans_admin_api_general_setting()
	{
		return array(
			'stop_beans_image' => array(
				'label' => esc_html__('Stop Beans Image API','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'general',
				'section' => 'api',
			),
			'stop_beans_widget' => array(
				'label' => esc_html__('Stop Beans Widget API','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'general',
				'section' => 'api',
			),
			'stop_beans_customizer' => array(
				'label' => esc_html__('Stop Beans Customizer API','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'general',
				'section' => 'api',
			),
		);

	}// Method
	endif;
