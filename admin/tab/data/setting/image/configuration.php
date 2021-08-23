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
	 * 	Configuration section of image tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_configuration_image_setting') === FALSE) :
	function __beans_admin_configuration_image_setting()
	{
		return array(
			'autosave_thumbnail' => array(
				'label' => esc_html__('Autosave Thumbnail','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'image',
				'section' => 'configuration',
			),
			'num_of_categories' => array(
				'label' => esc_html__('Max Number of Categories','beans-extension'),
				'type' => 'integer',
				'default' => 5,
				'group' => 'image',
				'section' => 'configuration',
			),
		);

	}// Method
	endif;
