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
	 * 	Title and description for column tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_column_section') === FALSE) :
	function __beans_admin_column_section()
	{
		return array(
			'post' => array(
				'title' => esc_html__('Posts List Settings','beans-extension'),
				'description' => esc_html__('Configurate Admin Posts Column. ex. Thumbnail, Word Counts, Post Views etc.','beans-extension'),
			),
			'page' => array(
				'title' => esc_html__('Pages List Settings','beans-extension'),
				'description' => esc_html__('Configurate Admin Pages Column. ex) Page Template, Slug etc.','beans-extension'),
			),
			'profile' => array(
				'title' => esc_html__('User Profile Settings','beans-extension'),
				'description' => esc_html__('Configurate Admin User Profile. ex) your SNS Account Url.','beans-extension'),
			),
		);

	}// Method
	endif;
