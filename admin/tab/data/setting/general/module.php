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
	if(function_exists('__beans_admin_module_general_setting') === FALSE) :
	function __beans_admin_module_general_setting()
	{
		return array(
			'beans_legacy_layout' => array(
				'label' => esc_html__('Use Beans Legacy Layout','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'general',
				'section' => 'module',
			),
			'beans_accessibility' => array(
				'label' => esc_html__('Use Beans Accessibility (Skip to Link) HTML','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'general',
				'section' => 'module',
			),
		);

	}// Method
	endif;
