<?php 
/**
 * Checks the given asset's handle to determine if it should not be compiled.
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
	/**
	 * @since 1.0.1
	 * 	WP css
	*/
	'admin-bar',
	'dashicons',
	'wp-block-library',
	'wp-block-library-theme',

	/**
	 * @since 1.0.1
	 * 	plugin css
	*/
	'amp-icons',
	'beans-extension',
	'current-template-style',
	'debug-bar',
	'query-monitor',
	'swiper',

	/**
	 * @since 1.0.1
	 * 	CDN css
	*/
	'font-awesome',
	'dancing',
	'lato',
	'lora',
	'lusitana',
	'playfair',
	'poppins',
	'raleway',
	'shadows',
	'simonetta',
	'source',
	'tangerine',
	'vibes',
	'uikit3',

	/**
	 * @since 1.0.1
	 * 	WP js
	*/
	// 'admin-bar',
	// 'debug-bar',
	'wp-embed',

	/**
	 * @since 1.0.1
	 * 	plugin js
	*/
	// 'beans-extension',
	'current-template-js',
	'debug-bar-js',
	'query-monitor',
	// 'swiper',

	/**
	 * @since 1.0.1
	 * 	CDN js
	*/
	'jquery',
	'barba',
	'gsap',
	// 'uikit3',
	'uikit3-icons',
);
