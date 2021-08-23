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
	 * 	API section of general tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_path_general_setting') === FALSE) :
	function __beans_admin_path_general_setting()
	{
		return array(
			'theme_template_path' => array(
				'label' => esc_html__('Template File Path (BEANS_TEMPLATES_PATH).','beans-extension'),
				'type' => 'string',
				'default' => "lib/templates/",
				'group' => 'general',
				'section' => 'path',
			),
			'theme_structure_path' => array(
				'label' => esc_html__('Structure File Path (BEANS_STRUCTURE_PATH).','beans-extension'),
				'type' => 'string',
				'default' => "lib/templates/structure/",
				'group' => 'general',
				'section' => 'path',
			),
			'theme_fragment_path' => array(
				'label' => esc_html__('Fragment File Path (BEANS_FRAGMENTS_PATH).','beans-extension'),
				'type' => 'string',
				'default' => "lib/templates/fragments/",
				'group' => 'general',
				'section' => 'path',
			),
		);

	}// Method
	endif;
