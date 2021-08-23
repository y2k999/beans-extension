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
if(class_exists('_beans_control_image') === FALSE) :
final class _beans_control_image
{
/**
 * @since 1.0.1
 * 	Beans Image Options Handler.
 * 	This class handles adding the Beans' Image options to the Beans' Settings page.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	init()
 * 	set_hook()
 * 	invoke_hook()
 * 	register()
 * 	get_fields_to_register()
 * 	has_metaboxes()
 * 	flush()
 * 	render_success_notice()
 * 	render_flush_button()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $_class
			Name/Identifier with Prefix.
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
			// Load with priority 15 so that we can check if other Beans metaboxes exist.
			'register' => array(
				'tag' => 'add_action',
				'hook' => 'admin_init',
				'priority' => 15
			),
			'flush' => array(
				'tag' => 'add_action',
				'hook' => 'admin_init',
				'priority' => -1
			),
			'notify' => array(
				'tag' => 'add_action',
				'hook' => 'admin_notices'
			),
			'button' => array(
				'tag' => 'add_action',
				'hook' => 'beans_extension_field_flush_edited_image'
			),
		));

	}// Method


	/* Hook
	_________________________
	*/
	public function register()
	{
		/**
			[ORIGINAL]
				register()
			@access (public)
				Register the options.
				Fires as an admin screen or script is being initialized.
				https://developer.wordpress.org/reference/hooks/admin_init/
			@return (bool)
			@reference
				[Plugin]/api/option/beans.php
		*/

		/**
		 * @reference (Beans)
		 * 	Register options.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_options/
		*/
		return _beans_option::__register(
			$this->get_field(),
			'beans_extension_setting',
			'beans_extension_image_option',
			array(
				'title' => esc_html__('Image Option','beans-extension','beans-extension'),
				'context' => $this->has_metabox() ? 'column' : 'normal',
			));

	}// Method


	/**
		[ORIGINAL]
			get_fields_to_register()
		@since 1.5.0
		@access (private)
			Get the fields to register.
		@return (array)
	*/
	private function get_field()
	{
		return require dirname(__FILE__) . '/config/image.php';

	}// Method


	/**
		[ORIGINAL]
			has_metaboxes()
		@since1.5.0
		@access(private)
			Checks if there are metaboxes registered already.
		@global (array) $wp_meta_boxes
			Globalize the metaboxes array, this holds all the widgets for wp-admin.
			https://developer.wordpress.org/apis/handbook/dashboard-widgets/
		@return (bool)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function has_metabox()
	{
		global $wp_meta_boxes;
		$metaboxes = _beans_utility::__get_global_value('beans_extension_setting',$wp_meta_boxes);

		return !empty($metaboxes);

	}// Method


	/* Hook
	_________________________
	*/
	public function flush()
	{
		/**
			[ORIGINAL]
				flush()
			@access (public)
				Flush images from the Beans cached folder.
			@return (void)
			@reference
				[Plugin]/utility/beans.php
				[Plugin]/api/image/beans.php
		*/
		if(!_beans_utility::__post_global_value('beans_extension_flush_edited_image')){return;}

		// Remove a directory and its files.
		_beans_utility::__remove_directory(_beans_image::__get_directory());

	}// Method


	/* Hook
	_________________________
	*/
	public function notify()
	{
		/**
			[ORIGINAL]
				render_success_notice()
			@access (public)
				Renders the success admin notice.
			@return (void)
			@reference
				[Plugin]/utility/beans.php
		*/
		if(!_beans_utility::__post_global_value('beans_extension_flush_edited_image')){return;}
		include dirname(__FILE__) . '/view/notice-flushed.php';

	}// Method


	/* Method
	_________________________
	*/
	public function button($field)
	{
		/**
			[ORIGINAL]
				render_flush_button()
			@access (public)
				Render the flush button,which is used to flush the images' cache.
			@param (array)$field
				Registered options.
			@return (void)
		*/
		if('beans_extension_edited_image_directory' !== $field['id']){return;}
		include dirname(__FILE__) . '/view/button-flush.php';

	}// Method


}// Class
endif;
