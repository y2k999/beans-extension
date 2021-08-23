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
	 * 	wp_head() section of cleaner tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_wphead_cleaner_setting') === FALSE) :
	function __beans_admin_wphead_cleaner_setting()
	{
		return array(
			'wp_generator' => array(
				'label' => esc_html__('WordPress Generator Meta Tag','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'cleaner',
				'section' => 'wphead',
			),
			'wp_shortlink_wp_head' => array(
				'label' => esc_html__('Post Shortlinks','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'cleaner',
				'section' => 'wphead',
			),
			'wlwmanifest_link' => array(
				'label' => esc_html__('Windows Live Writer','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'cleaner',
				'section' => 'wphead',
			),
			'wp_resource_hints' => array(
				'label' => esc_html__('Resource Hints','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'cleaner',
				'section' => 'wphead',
			),
			'adjacent_posts_rel_link_wp_head' => array(
				'label' => esc_html__('Previous and Next Links','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'cleaner',
				'section' => 'wphead',
			),
			'start_post_rel_link' => array(
				'label' => esc_html__('Relational Link for the First Post','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'cleaner',
				'section' => 'wphead',
			),
			'index_rel_link' => array(
				'label' => esc_html__('Relational Link for the Site Index','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'cleaner',
				'section' => 'wphead',
			),
			'rel_canonical' => array(
				'label' => esc_html__('Canonical links','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'cleaner',
				'section' => 'wphead',
			),
		);

	}// Method
	endif;
