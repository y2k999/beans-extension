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

// Custom global variable.
global $_beans_extension_component_setting;

switch($_beans_extension_component_setting['uikit']){
	case 'uikit2' :
		return array(
			'cover' => array(
				'core' => array(
					'flex',
				),
			),
			'lightbox' => array(
				'core' => array(
					'animation',
					'flex',
					'close',
					'modal',
					'overlay',
				),
				// 'add-ons' => array(
				'component' => array(
					'slidenav',
				),
			),
			'modal' => array(
				'core' => array(
					'close',
				),
			),
			'notify' => array(
				'core' => array(
					'close',
				),
			),
			'overlay' => array(
				'core' => array(
					'flex',
				),
			),
			'panel' => array(
				'core' => array(
					'badge',
				),
			),
			'parallax' => array(
				'core' => array(
					'flex',
				),
			),
			'scrollspy' => array(
				'core' => array(
					'animation',
				),
			),
			'slider' => array(
				// 'add-ons' => array(
				'component' => array(
					'slidenav',
				),
			),
			'slideset' => array(
				'core' => array(
					'animation',
					'flex',
				),
				// 'add-ons' => array(
				'component' => array(
					'dotnav',
					'slidenav',
				),
			),
			'slideshow' => array(
				'core' => array(
					'animation',
					'flex',
				),
				// 'add-ons' => array(
				'component' => array(
					'dotnav',
					'slidenav',
				),
			),
	/*
				'tab' => array(
					'core' => array(
						'switcher',
					),
				),
	*/
		);
		break;

	case 'uikit3' :
	default :
		return array();
		break;
}
