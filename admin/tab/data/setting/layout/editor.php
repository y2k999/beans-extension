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
	 * 	Configuration section of layout tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_editor_layout_setting') === FALSE) :
	function __beans_admin_editor_layout_setting()
	{
		return array(
			'post_meta' => array(
				'label' => esc_html__('Show Layouts on Post Edit Screen?','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'layout',
				'section' => 'editor',
			),
			'term_meta' => array(
				'label' => esc_html__('Show Layouts on Category Edit Screen?','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'layout',
				'section' => 'editor',
			),
		);

	}// Method
	endif;
