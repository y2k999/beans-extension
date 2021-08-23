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
	 * 	Emoji section of cleaner tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_emoji_cleaner_setting') === FALSE) :
	function __beans_admin_emoji_cleaner_setting()
	{
		return array(
			'print_emoji_detection_script' => array(
				'label' => esc_html__('Emoji Scripts','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'cleaner',
				'section' => 'emoji',
			),
			'print_emoji_styles' => array(
				'label' => esc_html__('Emoji Styles','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'cleaner',
				'section' => 'emoji',
			),
		);

	}// Method
	endif;
