<?php
/**
 * Register and enqueue required scripts and styles.
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
if(class_exists('_beans_asset') === FALSE) :
class _beans_asset
{
/**
 * @since 1.0.1
 * 	Enqueue scripts and styles.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_hook()
 * 	invoke_hook()
 * 	reset_jquery()
 * 	enqueue_uikit2_component()
 * 	enqueue_uikit3_cdn()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $_class
			Name/Identifier with prefix.
		@var (string) $plugin_name
			The ID of this plugin.
		@var (string) $version
			The current version of this plugin.
		@var (array) $_hook
			The collection of hooks that is being registered (that is, actions or filters).
	*/
	private static $_class = '';
	protected $plugin_name;
	protected $version;
	private $hook = array();

	/**
	 * Traits.
	*/
	use _trait_singleton;
	use _trait_hook;


	/* Constructor
	_________________________
	*/
	private function __construct()
	{
		/**
			@access (private)
				Class constructor.
				This is only called once, since the only way to instantiate this is with the get_instance() method in trait (singleton.php).
			@return (void)
			@reference
				[Plugin]/trait/singleton.php
				[Plugin]/include/constant.php
				[Plugin]/utility/general.php
		*/

		// Init properties.
		self::$_class = __utility_get_class(get_class($this));

		if(defined('BEANS_EXTENSION_VERSION')){
			$this->version = BEANS_EXTENSION_VERSION;
		}
		else{
			$this->version = '1.0.1';
		}
		$this->plugin_name = 'beans-extension';

		// Register hooks.
		$this->hook = $this->set_hook();
		$this->invoke_hook($this->hook);

	}// Method


	/* Setter
	_________________________
	*/
	private function set_hook()
	{
		/**
			@access (private)
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@return (array)
				The collection of hooks that is being registered (that is, actions or filters).
			@reference
				[Plugin]/trait/hook.php
				[Plugin]/include/component.php
				[Plugin]/admin/tab/general.php
		*/
		$return['reset_jquery'] = array(
			'tag' => 'add_action',
			'hook' => 'wp_enqueue_scripts',
		);
		$return['admin_enqueue_scripts'] = array(
			'tag' => 'add_action',
			'hook' => 'admin_enqueue_scripts',
		);

		// Custom global variable.
		global $_beans_extension_component_setting;

		$return['enqueue_uikit2_component'] = array(
			'tag' => 'add_action',
			'hook' => 'beans_extension_uikit_enqueue_script',
		);

		$return['enqueue_uikit3_cdn'] = array(
			'tag' => 'add_action',
			'hook' => 'wp_enqueue_scripts',
		);

		if(isset($_beans_extension_component_setting['general']['uikit2']) && ($_beans_extension_component_setting['general']['uikit2'] === 'full')){
			unset($return['enqueue_uikit3_cdn']);
		}

		return $this->set_parameter_callback($return);

	}// Method


	/* Hook
	_________________________
	*/
	public function reset_jquery()
	{
		/**
			@access (public)
				Change jquery library version.
			@return (void)
			@reference (WP)
				Enqueue a script.
				https://developer.wordpress.org/reference/functions/wp_enqueue_script/
		*/
		wp_enqueue_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js',array(),'3.6.0',TRUE);

	}// Method


	/* Hook
	_________________________
	*/
	public function admin_enqueue_scripts()
	{
		/**
			@access (public)
				Enqueue Google web font.
			@return (void)
			@reference (WP)
				Enqueue a CSS stylesheet.
				https://developer.wordpress.org/reference/functions/wp_enqueue_style/
		*/
		wp_register_style($this->plugin_name . '-admin-font','https://fonts.googleapis.com/css?family=Roboto',$this->version);
		wp_enqueue_style($this->plugin_name . '-admin-font');

	}// Method


	/* Hook
	_________________________
	*/
	public function enqueue_uikit2_component()
	{
		/**
			[ORIGINAL]
				beans_enqueue_uikit_components()
				https://www.getbeans.io/code-reference/functions/beans_enqueue_uikit_components/
			@access (public)
				Enqueue UIkit2 core component and theme.
				Theme style is enqueuedto have access to UIKit LESS variables.
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@return (void)
			@reference
				[Plugin]/admin/tab/general.php
				[Plugin]/api/uikit/beans.php
		*/

		// Custom global variable.
		global $_beans_extension_component_setting;

		switch($_beans_extension_component_setting['general']['uikit2']){
			case 'full' :
				_beans_uikit::__enqueue_component(array(
					'base',
					'block',
					'column',
					'flex',
					'grid',
					'animation',
					'article',
					'comment',
					'panel',
					'nav',
					'navbar',
					'subnav',
					'table',
					'breadcrumb',
					'pagination',
					'list',
					'form',
					'button',
					'badge',
					'alert',
					'dropdown',
					'offcanvas',
					'text',
					'utility',
					'icon',
				),'core',FALSE);
				break;

			case 'min' :
			default :
				/**
				 * @since 1.0.1
				 * 	Set the Uikit2 component that is minimum size and not used in Uikit3.
				*/
				_beans_uikit::__enqueue_component(array(
					// 'base',
					'block',
				),'core',FALSE);
				break;
		}

	}// Method


	/* Hook
	_________________________
	*/
	public function enqueue_uikit3_cdn()
	{
		/**
			@access (public)
				Enqueue UIkit3 via CDN.
			@return (void)
			@reference
				[Plugin]/admin/tab/general.php
		*/

		/**
		 * @reference (WP)
		 * 	Enqueue a CSS stylesheet.
		 * 	https://developer.wordpress.org/reference/functions/wp_enqueue_style/
		 * 	Enqueue a script.
		 * 	https://developer.wordpress.org/reference/functions/wp_enqueue_script/
		*/
		wp_register_style('uikit3','https://cdn.jsdelivr.net/npm/uikit@3.7.2/dist/css/uikit.min.css',array(),'3.7.2','all');
		wp_register_script('uikit3','https://cdn.jsdelivr.net/npm/uikit@3.7.2/dist/js/uikit.min.js',array(),'3.7.2',TRUE);
		wp_register_script('uikit3-icons','https://cdn.jsdelivr.net/npm/uikit@3.7.2/dist/js/uikit-icons.min.js',array(),'3.7.2',TRUE);

		wp_enqueue_style('uikit3');
		wp_enqueue_script('uikit3');
		wp_enqueue_script('uikit3-icons');

	}// Method


}// Class
endif;
_beans_asset::__get_instance();
