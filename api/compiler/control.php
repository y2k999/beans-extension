<?php
/**
 * Define Beans API classes.
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
if(class_exists('_beans_control_compiler') === FALSE) :
final class _beans_control_compiler
{
/**
 * @since 1.0.1
 * 	Beans Compiler Options Handler.
 * 	This class handles adding the Beans' Compiler options to the Beans' Settings page.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	init()
 * 	set_hook()
 * 	invoke_hook()
 * 	register()
 * 	get_field()
 * 	is_supported()
 * 	flush()
 * 	render_success_notice()
 * 	render_flush_button()
 * 	render_styles_not_compiled_notice()
 * 	render_scripts_not_compiled_notice()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $_class
			Name/Identifier with prefix.
		@var (array) $hook
			The collection of hooks that is being registered (that is, actions or filters).
	*/
	private static $_class = '';
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

	}// Method


	/* Method
	_________________________
	*/
	public function init()
	{
		/**
			@since 1.5.0
			@access (public)
				Initialize the hooks.
			@return (void)
		*/

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
			@return (array)
				The collection of hooks that is being registered (that is, actions or filters).
			@reference
				[Plugin]/trait/hook.php
		*/
		return $this->set_parameter_callback(array(
			'register' => array(
				'tag' => 'add_action',
				'hook' => 'admin_init'
			),
			'flush' => array(
				'tag' => 'add_action',
				'hook' => 'admin_init',
				'priority' => -1
			),
			'success_notice' => array(
				'tag' => 'add_action',
				'hook' => 'admin_notices'
			),
			'flush_button' => array(
				'tag' => 'add_action',
				'hook' => 'beans_extension_field_flush_cache'
			),
			'style_not_compiled_notice' => array(
				'tag' => 'add_action',
				'hook' => 'beans_extension_field_description_beans_extension_compile_all_style_append_markup'
			),
			'script_not_compiled_notice' => array(
				'tag' => 'add_action',
				'hook' => 'beans_extension_field_description_beans_extension_compile_all_script_group_append_markup'
			),
		));

	}// Method


	/* Method
	_________________________
	*/
	public function register()
	{
		/**
			@access (public)
				Register options.
				Fires as an admin screen or script is being initialized.
				https://developer.wordpress.org/reference/hooks/admin_init/
			@return (bool)
			@reference
				[Plugin]/api/option/beans.php
		*/
		return _beans_option::__register(
			$this->get_field(),
			'beans_extension_setting',
			'beans_extension_compiler_option',
			array(
				'title' => esc_html__('Compiler Option','beans-extension'),
				'context' => 'normal',
			));

	}// Method


	/**
		@since 1.5.0
		@access (private)
			Get the fields to register.
		@return (array)
		@reference
			[Plugin]/include/component.php
	*/
	private function get_field()
	{
		$fields = require dirname(__FILE__) . '/config/compiler.php';

		// If not supported,remove the styles' fields.
		if($this->is_supported('wp_style_beans_extension_compiler')){
			unset($fields['beans_extension_compile_all_style']);
		}

		// If not supported,remove the scripts' fields.
		if($this->is_supported('wp_script_beans_extension_compiler')){
			unset($fields['beans_extension_compile_all_script_group']);
		}
		return $fields;

	}// Method


	/**
		@since 1.5.0
		@access (private)
			Checks if the component is not supported.
		@param (string) $component
			The component to check.
		@return (bool)
		@reference
			[Plugin]/include/component.php
	*/
	private function is_supported($component)
	{
		return !_beans_component::__get_support($component);

	}// Method


	/* Method
	_________________________
	*/
	public function flush()
	{
		/**
			@access (public)
				Flush the cached files.
			@return (void)
			@reference
				[Plugin]/utility/beans.php
				[Plugin]/api/compiler/beans.php
		*/
		if(!_beans_utility::__post_global_value('beans_extension_flush_compiler_cache')){return;}
		_beans_utility::__remove_directory(_beans_compiler::__get_directory());

	}// Method


	/* Method
	_________________________
	*/
	public function success_notice()
	{
		/**
			@access (public)
				Renders the success notice.
				Prints admin screen notices.
				https://developer.wordpress.org/reference/hooks/admin_notices/
			@return (void)
			@reference
				[Plugin]/utility/beans.php
		*/
		if(!_beans_utility::__post_global_value('beans_extension_flush_compiler_cache')){return;}
		include dirname(__FILE__) . '/view/notice-flushed.php';

	}// Method


	/* Method
	_________________________
	*/
	public function flush_button($field)
	{
		/**
			@access (public)
				Render the flush button,which is used to flush the cache.
			@param (array)$field
				Registered options.
			@return (void)
		 */
		if('beans_extension_compiler_item' !== $field['id']){return;}
		include dirname(__FILE__) . '/view/button-flush.php';

	}// Method


	/* Method
	_________________________
	*/
	public function style_not_compiled_notice()
	{
		/**
			@access (public)
				Render a notice when styles should not be compiled.
			@return (void)
			@reference
				[Plugin]/api/compiler/beans.php
		*/
		if(!_beans_compiler::__is_dev_mode()){return;}
		if(!get_option('beans_extension_compile_all_style')){return;}

		$message = esc_html__('Styles are not compiled in development mode.','beans-extension');
		include dirname(__FILE__) . '/view/notice-not-compiled.php';

	}// Method


	/* Method
	_________________________
	*/
	public function script_not_compiled_notice()
	{
		/**
			@access (public)
				Maybe show disabled notice.
			@return (void)
			@reference
				[Plugin]/api/compiler/beans.php
		*/
		if(!_beans_compiler::__is_dev_mode()){return;}
		if(!get_option('beans_extension_compile_all_script')){return;}

		$message = esc_html__('Scripts are not compiled in development mode.','beans-extension');
		include dirname(__FILE__) . '/view/notice-not-compiled.php';

	}// Method


}// Class
endif;
