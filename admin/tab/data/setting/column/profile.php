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
	 * 	Profile section of column tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_profile_column_setting') === FALSE) :
	function __beans_admin_profile_column_setting()
	{
		return array(
			'account_twitter' => array(
				'label' => esc_html('Twitter'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'column',
				'section' => 'profile',
			),
			'account_facebook' => array(
				'label' => esc_html('Facebook'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'column',
				'section' => 'profile',
			),
			'account_instagram' => array(
				'label' => esc_html('Instagram'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'column',
				'section' => 'profile',
			),
			'account_github' => array(
				'label' => esc_html('Github'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'column',
				'section' => 'profile',
			),
			'account_youtube' => array(
				'label' => esc_html('YouTube'),
				'type' => 'boolean',
				'default' => 1,
				'group' => 'column',
				'section' => 'profile',
			),
		);

	}// Method
	endif;
