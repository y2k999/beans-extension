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
	 * 	Post section of column tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_post_column_setting') === FALSE) :
	function __beans_admin_post_column_setting()
	{
		return array(
			'id_post' => array(
				'label' => esc_html__('ID','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'column',
				'section' => 'post',
			),
			'thumbnail_post' => array(
				'label' => esc_html__('Thumbnail','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'column',
				'section' => 'post',
			),
			'wordcount_post' => array(
				'label' => esc_html__('Word Count','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'column',
				'section' => 'post',
			),
			'pv_post' => array(
				'label' => esc_html__('Activate Post View Count ?','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'column',
				'section' => 'post',
			),
		);

	}// Method
	endif;
