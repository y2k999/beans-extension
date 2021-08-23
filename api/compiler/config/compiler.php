<?php 
/**
 * Configuration file of Beans API.
 * @package Beans_Extension
 * @license GPL3.0+
 * @since 1.0.1
*/

/**
 * Inspired by Beans Framework WordPress Theme
 * @link https://www.getbeans.io
 * @author Thierry Muller
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

return array(
	'beans_extension_compiler_item' => array(
		'id' => 'beans_extension_compiler_item',
		'type' => 'flush_cache',
		'description' => esc_html__('Clear CSS and JS Cached Files. New cached versions will be Compiled on Page Load.','beans-extension'),
	),
	'beans_extension_compile_all_style' => array(
		'id' => 'beans_extension_compile_all_style',
		'label' => esc_html__('Compile All WordPress Styles','beans-extension'),
		'checkbox_label' => esc_html__('Select to Compile Styles.','beans-extension'),
		'type' => 'checkbox',
		'default' => FALSE,
		'description' => esc_html__('Compile and Cache All the CSS files that have been enqueued to the WordPress head.','beans-extension'),
	),
	'beans_extension_compile_all_script_group' => array(
		'id' => 'beans_extension_compile_all_script_group',
		'label' => esc_html__('Compile All WordPress Scripts','beans-extension'),
		'type' => 'group',
		'field' => array(
			array(
				'id' => 'beans_extension_compile_all_script',
				'type' => 'activation',
				'label' => esc_html__('Select to Compile Scripts.','beans-extension'),
				'default' => FALSE,
			),
			array(
				'id' => 'beans_extension_compile_all_script_mode',
				'type' => 'select',
				'label' => esc_html__('Choose the Level of Compilation.','beans-extension'),
				'default' => 'aggressive',
				'option' => array(
					'aggressive' => esc_html__('Aggressive','beans-extension'),
					'standard' => esc_html__('Standard','beans-extension'),
				),
			),
		),
		'description' => esc_html__('Compile and Cache All the JS files that have been enqueued to the WordPress head. <br/> JavaScript is outputted in the footer if the level is set to <strong>Aggressive</strong> and might conflict with some third-party plugins which are not following WordPress Standards.','beans-extension'),
	),
);
