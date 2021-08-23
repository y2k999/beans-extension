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
	 * 	oEmbed section of cleaner tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_oembed_cleaner_setting') === FALSE) :
	function __beans_admin_oembed_cleaner_setting()
	{
		return array(
			'wp_oembed_add_discovery_links' => array(
				'label' => esc_html__('oEmbed Tags','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'cleaner',
				'section' => 'oembed',
			),
			'wp_oembed_add_host_js' => array(
				'label' => esc_html__('oEmbed Scripts','beans-extension'),
				'type' => 'boolean',
				'default' => 0,
				'group' => 'cleaner',
				'section' => 'oembed',
			),
		);

	}// Method
	endif;
