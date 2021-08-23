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
	 * 	Title and description for cleaner tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_cleaner_section') === FALSE) :
	function __beans_admin_cleaner_section()
	{
		return array(
			'oembed' => array(
				'title' => esc_html__('oEmbeds','beans-extension'),
				'description' => esc_html__('Remove oEmbed Discovery Links and wp-embed.min.js.','beans-extension'),
			),
			'rest' => array(
				'title' => esc_html__('RSD/REST API Links','beans-extension'),
				'description' => esc_html__('Remove the RSD and WordPress REST API links.','beans-extension'),
			),
			'feed' => array(
				'title' => esc_html__('RSS Feed Links','beans-extension'),
				'description' => esc_html__('Remove the RSS feed links.','beans-extension'),
			),
			'emoji' => array(
				'title' => esc_html__('Emoji Codes','beans-extension'),
				'description' => esc_html__('Remove the WordPress emoji codes, known as the WordPress Smilies.','beans-extension'),
			),
			'wphead' => array(
				'title' => esc_html__('General Tags','beans-extension'),
				'description' => esc_html__('Remove the generated by WordPress tag.','beans-extension'),
			),
		);

	}// Method
	endif;