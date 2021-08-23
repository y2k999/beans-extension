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
	 * 	Feed section of cleaner tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_feed_cleaner_setting') === FALSE) :
	function __beans_admin_feed_cleaner_setting()
	{
		return array(
			'feed_links' => array(
				'label' => esc_html__('Post and Comment Feed','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'cleaner',
				'section' => 'feed',
			),
			'feed_links_extra' => array(
				'label' => esc_html__('Other Feeds, ex. Category Feed','beans-extension'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'cleaner',
				'section' => 'feed',
			),
		);

	}// Method
	endif;
