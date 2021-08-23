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
	'beans_extension_edited_image_directory' => array(
		'id' => 'beans_extension_edited_image_directory',
		'type' => 'flush_edited_image',
		'description' => esc_html__('Clear All Edited Images. New Images will be created on Page Load.','beans-extension'),
	),
);
