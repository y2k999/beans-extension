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
	 * 	Title and description for general tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	*/
	if(function_exists('__beans_admin_general_section') === FALSE) :
	function __beans_admin_general_section()
	{
		return array(
			'api' => array(
				'title' => esc_html__('Beans API Components','beans-extension'),
				'description' => esc_html__("Check here, if you don't want to use ",'beans-extension') . '<a href="https://www.getbeans.io/documentation/editing-images/" target="_blank">Beans Image API</a>, ' . '<a href="https://www.getbeans.io/documentation/adding-a-widget-area/" target="_blank">Beans Widget API</a>, ' . '<a href="' . admin_url('customize.php') . '">Beans Customizer API</a>.',
			),
			'module' => array(
				'title' => esc_html__('Beans Default Modules','beans-extension'),
				'description' => esc_html__("Check here, if you want to use Beans default 6 layouts ",'beans-extension') . '<a href="https://www.getbeans.io/documentation/page-layouts/" target="_blank">("c_sp", "sp_c", "c_sp_ss", "sp_c_ss", "sp_ss_c")</a>' . esc_html__(", and Beans Accessibility function ",'beans-extension') . '<a href="https://make.wordpress.org/themes/2019/08/03/planning-for-keyboard-navigation/" target="_blank">(beans_build_skip_links())</a>',
			),
			'path' => array(
				'title' => esc_html__('Beans Template Directories','beans-extension'),
				'description' => esc_html__("You can configure Beans constants for template files under the directory path of your theme ",'beans-extension') . '(<a href="https://developer.wordpress.org/reference/functions/get_template_directory/" target="_blank">get_template_directory()</a>)',
			),
			'css' => array(
				'title' => esc_html__('Uikit Version','beans-extension'),
				'description' => __('If you want to use Uikit3, select "<strong>Load only normalize.css</strong>" option and check "<strong>Uikit3 CDN</strong>".','beans-extension'),
			),
		);

	}// Method
	endif;
