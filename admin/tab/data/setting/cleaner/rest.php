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
	 * 	REST section of cleaner tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_rest_cleaner_setting') === FALSE) :
	function __beans_admin_rest_cleaner_setting()
	{
		return array(
			'rsd_link' => array(
				'label' => esc_html__('Really Simple Discovery','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'cleaner',
				'section' => 'rest',
			),
			'rest_output_link_wp_head' => array(
				'label' => esc_html__('REST API','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'cleaner',
				'section' => 'rest',
			),
		);

	}// Method
	endif;
