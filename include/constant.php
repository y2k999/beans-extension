<?php
/**
 * Define constants and global variables.
 * @package Beans_Extension
 * @license GPL3.0+
 * @since 1.0.1
*/

/**
 * Inspired by Beans Framework WordPress Theme
 * @link https://www.getbeans.io
 * @author Thierry Muller
*/
// namespace Beans_Extension;


/* Prepare
______________________________
*/

// If this file is called directly,abort.
if(!defined('WPINC')){die;}


/* Exec
______________________________
*/

/**
 * @since 1.0.1
 * 	Common constants.
*/
if(!defined('BEANS_EXTENSION_VERSION')){
	define('BEANS_EXTENSION_VERSION','1.0.8');
}

if(!defined('BEANS_EXTENSION_PRIORITY')){
	define('BEANS_EXTENSION_PRIORITY',array(
		'default' => 10,
		'min' => 1,
		'low' => 9,
		'lower' => 11,
		'middle' => 20,
		'higher' => 99,
		'high' => 999,
		'max' => 9999,
	));
}

if(!defined('BEANS_EXTENSION_PREFIX')){
	define('BEANS_EXTENSION_PREFIX',array(
		// class/trait/file name.
		'beans' => '_beans_',
		'model' => '_model_',
		'tab'	 => '_tab_',
		'trait' => '_trait_',
		// function/method name.
		'hook' => '__hook_',
		'get' => '__get_',
		'set' => '__set_',
		'the' => '__the_',
		/**
		 * @reference (WP)
		 * 	Settings API.
		 * 	https://developer.wordpress.org/plugins/settings/settings-api/
		*/
		'group' => 'bx_group_',
		'view' => 'bx_view_',
		'option' => 'bx_option_',
		'section' => 'bx_section_',
		'setting' => 'bx_setting_',
	));
}


/**
 * @since 1.0.1
 * 	Base path & url.
*/
if(!defined('BEANS_EXTENSION_PATH')){
	/**
	 * @reference (WP)
	 * 	Get the filesystem directory path (with trailing slash) for the plugin __FILE__ passed in.
	 * 	https://developer.wordpress.org/reference/functions/plugin_dir_path/
	*/
	define('BEANS_EXTENSION_PATH',plugin_dir_path(dirname(__FILE__),1));
}

if(!defined('BEANS_EXTENSION_URL')){
	/**
	 * @reference (WP)
	 * 	Get the URL directory path (with trailing slash) for the plugin __FILE__ passed in.
	 * 	https://developer.wordpress.org/reference/functions/plugin_dir_url/
	*/
	define('BEANS_EXTENSION_URL',plugin_dir_url(dirname(__FILE__),1));
}

if(!defined('BEANS_EXTENSION_LANGUAGE_PATH')){
	define('BEANS_EXTENSION_LANGUAGE_PATH',BEANS_EXTENSION_PATH . 'asset/language/');
}


/**
 * @since 1.0.1
 * 	Theme path & url.
 * @reference
 * 	[Plugin]/include/component.php
 * 	[Plugin]/admin/tab/general.php
*/
if(!defined('BEANS_EXTENSION_THEME_PATH')){
	define('BEANS_EXTENSION_THEME_PATH',trailingslashit(get_template_directory()));
}

// API components setting global.
global $_beans_extension_component_setting;

if(!defined('BEANS_EXTENSION_TEMPLATE_PATH')){
	if(isset($_beans_extension_component_setting['template']) && ($_beans_extension_component_setting['template'] !== '')){
		define('BEANS_EXTENSION_TEMPLATE_PATH',BEANS_EXTENSION_THEME_PATH . $_beans_extension_component_setting['template']);
	}
	else{
		define('BEANS_EXTENSION_TEMPLATE_PATH',BEANS_EXTENSION_THEME_PATH . 'lib/templates/');
	}
}

if(!defined('BEANS_EXTENSION_STRUCTURE_PATH')){
	if(isset($_beans_extension_component_setting['structure']) && ($_beans_extension_component_setting['structure'] !== '')){
		define('BEANS_EXTENSION_STRUCTURE_PATH',BEANS_EXTENSION_TEMPLATE_PATH . $_beans_extension_component_setting['structure']);
	}
	else{
		define('BEANS_EXTENSION_STRUCTURE_PATH',BEANS_EXTENSION_TEMPLATE_PATH . 'lib/templates/structure/');
	}
}

if(!defined('BEANS_EXTENSION_FRAGMENT_PATH')){
	if(isset($_beans_extension_component_setting['fragment']) && ($_beans_extension_component_setting['fragment'] !== '')){
		define('BEANS_EXTENSION_FRAGMENT_PATH',BEANS_EXTENSION_TEMPLATE_PATH . $_beans_extension_component_setting['fragment']);
	}
	else{
		define('BEANS_EXTENSION_FRAGMENT_PATH',BEANS_EXTENSION_TEMPLATE_PATH . 'lib/templates/fragments/');
	}
}


/**
 * @since 1.0.1
 * 	Beans API path & url.
*/
if(!defined('BEANS_EXTENSION_API_PATH')){
	define('BEANS_EXTENSION_API_PATH',array(
		'admin' => BEANS_EXTENSION_PATH . 'admin/',
		'api' => BEANS_EXTENSION_PATH . 'api/',
		'asset' => BEANS_EXTENSION_PATH . 'asset/',
		'trait' => BEANS_EXTENSION_PATH . 'trait/',
		'utility' => BEANS_EXTENSION_PATH . 'utility/',
		'action' => BEANS_EXTENSION_PATH . 'api/action/',
		'compiler' => BEANS_EXTENSION_PATH . 'api/compiler/',
		'customizer' => BEANS_EXTENSION_PATH . 'api/customizer/',
		'field' => BEANS_EXTENSION_PATH . 'api/field/',
		'filter' => BEANS_EXTENSION_PATH . 'api/filter/',
		'html' => BEANS_EXTENSION_PATH . 'api/html/',
		'image' => BEANS_EXTENSION_PATH . 'api/image/',
		'layout' => BEANS_EXTENSION_PATH . 'api/layout/',
		'option' => BEANS_EXTENSION_PATH . 'api/option/',
		'post-meta' => BEANS_EXTENSION_PATH . 'api/post-meta/',
		'template' => BEANS_EXTENSION_PATH . 'api/template/',
		'term-meta' => BEANS_EXTENSION_PATH . 'api/term-meta/',
		'uikit' => BEANS_EXTENSION_PATH . 'api/uikit/',
		'widget' => BEANS_EXTENSION_PATH . 'api/widget/',
	));
}

if(!defined('BEANS_EXTENSION_API_URL')){
	define('BEANS_EXTENSION_API_URL',array(
		'admin' => BEANS_EXTENSION_URL . 'admin/',
		'api' => BEANS_EXTENSION_URL . 'api/',
		'asset' => BEANS_EXTENSION_URL . 'asset/',
		'action' => BEANS_EXTENSION_URL . 'api/action/',
		'compiler' => BEANS_EXTENSION_URL . 'api/compiler/',
		'customizer' => BEANS_EXTENSION_URL . 'api/customizer/',
		'field' => BEANS_EXTENSION_URL . 'api/field/',
		'filter' => BEANS_EXTENSION_URL . 'api/filter/',
		'html' => BEANS_EXTENSION_URL . 'api/html/',
		'image' => BEANS_EXTENSION_URL . 'api/image/',
		'layout' => BEANS_EXTENSION_URL . 'api/layout/',
		'option' => BEANS_EXTENSION_URL . 'api/option/',
		'post-meta' => BEANS_EXTENSION_URL . 'api/post-meta/',
		'template' => BEANS_EXTENSION_URL . 'api/template/',
		'term-meta' => BEANS_EXTENSION_URL . 'api/term-meta/',
		'uikit' => BEANS_EXTENSION_URL . 'api/uikit/',
		'widget' => BEANS_EXTENSION_URL . 'api/widget/',
	));
}
