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
	 * 	Picture section of image tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	 * 	[Plugin]/include/constant.php
	*/
	if(function_exists('__beans_admin_picture_image_setting') === FALSE) :
	function __beans_admin_picture_image_setting()
	{
		return array(
			'profile' => array(
				'label' => esc_html__('Author','beans-extension'),
				'type' => 'image',
				'default' => BEANS_EXTENSION_API_URL['asset'] . 'image/misc/nopost.jpg',
				'group' => 'image',
				'section' => 'picture',
			),
			'nopost' => array(
				'label' => esc_html__('Not Found','beans-extension'),
				'type' => 'image',
				'default' => BEANS_EXTENSION_API_URL['asset'] . 'image/misc/nopost.jpg',
				'group' => 'image',
				'section' => 'picture',
			),
		);

	}// Method
	endif;
