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
	 * 	Title and description for image tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_image_section') === FALSE) :
	function __beans_admin_image_section()
	{
		return array(
			'configuration' => array(
				'title' => esc_html__('Image Settings','beans-extension'),
				'description' => esc_html__('Automatically generate Featured Image from the First Attachment in Post or Registerd Categories.','beans-extension'),
			),
			'eyecatch' => array(
				'title' => esc_html__('Featured Images','beans-extension'),
				'description' => esc_html__('Set Featured Images for your Blog Categories.','beans-extension'),
			),
			'picture' => array(
				'title' => esc_html__('Miscellaneous','beans-extension'),
				'description' => esc_html__('Set Images for your Profile and No-Posts.','beans-extension'),
			),
		);

	}// Method
	endif;
