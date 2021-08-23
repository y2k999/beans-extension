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
if(class_exists('_beans_accessibility') === FALSE) :
final class _beans_accessibility
{
/**
 * [NOTE]
 * 	You can use this template ONLY when you select the "Use Beans Accessibility (Skip to Link) HTML" in Preview tab..
 * @since 1.0.1
 * 	This file contains Beans accessibility features.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_skip_link()
 * 	set_hook()
 * 	invoke_hook()
 * 	beans_build_skip_links()
 * 	beans_output_skip_links()
 * 	enqueue_script()
 * 	enqueue_style()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $_class
			Name/Identifier with prefix.
		@var (array)$_skip_link
			Array of skip links to render.
		@var (array)$hook
			The collection of hooks that is being registered (that is, actions or filters).
	*/
	private static $_class = '';
	private static $_skip_link = array();
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
		*/

		// Init properties.
		self::$_class = __utility_get_class(get_class($this));
		self::$_skip_link = $this->set_skip_link();

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
				Fires when UIkit scripts and styles are enqueued.
				https://www.getbeans.io/code-reference/hooks/beans_uikit_enqueue_scripts/
			@return (array)
			@reference
				[Plugin]/trait/hook.php
				[Plugin]/api/uikit/beans.php
		*/
		return $this->set_parameter_callback(array(
			'enqueue_script' => array(
				'tag' => 'beans_add_smart_action',
				'hook' => 'beans_extension_uikit_enqueue_script'
			),
			'enqueue_style' => array(
				'tag' => 'beans_add_smart_action',
				'hook' => 'beans_extension_uikit_enqueue_script'
			),
		));

	}// Method


	/* Setter
	_________________________
	*/
	private function set_skip_link()
	{
		/**
			[ORIGINAL]
				beans_build_skip_links()
			@since 1.5.0
			@access (private)
				Build the skip links html.
			@return (array)
			@reference
				[Plugin]/api/layout/beans.php
		*/
		$return = array();

		/**
		 * @reference (Beans)
		 * 	Get the current web page's layout ID.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_layout/
		*/
		$layout = _beans_layout::__get_current();

		/**
		 * @reference (WP)
		 * 	Determines whether a registered nav menu location has a menu assigned to it.
		 * 	https://developer.wordpress.org/reference/functions/has_nav_menu/
		*/
		if(has_nav_menu('primary_navigation')){
			$return['beans-primary-navigation'] = esc_html__('Skip to the primary navigation.','beans-extension');
		}
		$return['beans-content'] = esc_html__('Skip to the content.','beans-extension');

		if('c' !== $layout){
			if(_beans_layout::__has_primary_sidebar($layout)){
				$return['beans-primary-sidebar'] = esc_html__('Skip to the primary sidebar.','beans-extension');
			}
			if(_beans_layout::__has_secondary_sidebar($layout)){
				$return['beans-secondary-sidebar'] = esc_html__('Skip to the secondary sidebar.','beans-extension');
			}
		}

		/**
			@description
				Filter the skip links.
			@param (array) $return{
				Default skip links.
				@type (string)
					HTML ID attribute value to link to.
				@type (string)
					Anchor text.
			}
		*/
		$return = (array)apply_filters('beans_extension_skip_link',$return);
		return $return;

	}// Method


	/* Method
	_________________________
	*/
	public static function __render()
	{
		/**
			[ORIGINAL]
				beans_output_skip_links()
			@since 1.5.0
			@access (public)
				Echo Skip links output.
			@return (void)
			@reference
				[Plugin]/api/html/beans.php
		*/

		/**
		 * @reference (Beans)
		 * 	HTML markup.
		 * 	https://www.getbeans.io/code-reference/functions/beans_open_markup_e/
		 * 	https://www.getbeans.io/code-reference/functions/beans_close_markup_e/
		*/
		_beans_html::__open_markup_e("_list[beans_skip_link]",'ul',array('class' => 'beans-skip-links'));

		foreach(self::$_skip_link as $key => $value){
			_beans_html::__open_markup_e("_item[beans_skip_link][{$key}]",'li');
				_beans_html::__open_markup_e("_link[beans_skip_link][{$key}]",'a',array(
					'href' => '#' . $key,
					'class' => 'screen-reader-shortcut',
				));
					esc_html_e($value);
				_beans_html::__close_markup_e("_link[beans_skip_link][{$key}]",'a');
			_beans_html::__close_markup_e("_item[beans_skip_link][{$key}]",'li');
		}

		_beans_html::__close_markup_e("_list[beans_skip_link]",'ul');

	}// Method


	/* Hook
	_________________________
	*/
	public function enqueue_script()
	{
		/**
			[ORIGINAL]
				beans_accessibility_skip_link_fix()
			@since 1.5.0
			@access (public)
				Enqueue the JavaScript fix for Internet Explorer 11
			@return (void)
			@reference
				[Plugin]/include/constant.php
		*/
		$script = BEANS_EXTENSION_API_URL['asset'] . 'js/skip2link.min.js';
		wp_register_script('beans-extension-skip-link-fix',$script,array(),BEANS_EXTENSION_VERSION);
		wp_enqueue_script('beans-extension-skip-link-fix');

	}// Method


	/* Hook
	_________________________
	*/
	public function enqueue_style()
	{
		/**
			@access (public)
				WordPress theme requirement
				Add the theme style as a UIkit fragment to have access to all the variables.
			@return (void)
			@reference
				[Plugin]/api/compiler/beans.php
				[Plugin]/include/constant.php
		*/
		_beans_compiler::__add_fragment('uikit',BEANS_EXTENSION_API_PATH['asset'] . 'less/skip2link.less','less');

	}// Method


}// Class
endif;
// new _beans_accessibility();
_beans_accessibility::__get_instance();
