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
	 * 	Page section of column tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_page_column_setting') === FALSE) :
	function __beans_admin_page_column_setting()
	{
		return array(
			'id_page' => array(
				'label' => esc_html__('ID','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'column',
				'section' => 'page',
			),
			'thumbnail_page' => array(
				'label' => esc_html__('Thumbnail','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'column',
				'section' => 'page',
			),
			'template_page' => array(
				'label' => esc_html__('Template','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'column',
				'section' => 'page',
			),
			'slug_page' => array(
				'label' => esc_html__('Slug','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'column',
				'section' => 'page',
			),
		);

	}// Method
	endif;
