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
if(class_exists('_beans_customizer') === FALSE) :
class _beans_customizer
{
/**
 * @since 1.0.1
 * 	The Beans WP Customize component extends the Beans Fields API and makes it easy to add options to the WP Theme Customizer.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_hook()
 * 	invoke_hook()
 * 	beans_register_wp_customize_options()
 * 	enqueue_customize_preview()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $_class
			Name/Identifier with Prefix.
		@var (array)$hook
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
				Fires once the Customizer preview has initialized and JavaScript settings have been printed.
				https://developer.wordpress.org/reference/hooks/customize_preview_init/
			@return (array)
				The collection of hooks that is being registered (that is, actions or filters).
			@reference
				[Plugin]/trait/hook.php
		*/
		return $this->set_parameter_callback(array(
			'customize_preview_init' => array(
				'tag' => 'beans_add_smart_action',
				'hook' => 'customize_preview_init'
			),
		));

	}// Method


	/* Method
	_________________________
	*/
	public static function __register(array $fields,$section,$args = array())
	{
		/**
			[ORIGINAL]
				beans_register_wp_customize_options()
				https://www.getbeans.io/code-reference/functions/beans_register_wp_customize_options/
			@access (public)
				Register WP Customize Options.
				This function should only be called with the 'customize_register' action.
			@param (array) $fields{
				Array of fields to register.
				@type (string) $id
					A unique ID used for the field. This ID will also be used to save the value in the database.
				@type (string) $type
					The type of field to use.
					Please refer to the Beans core field types for more information.
					Custom field types are accepted here.
				@type (string) $label
					The field label.
					[Default] FALSE.
				@type (string) $description
					The field description.
					The description can be truncated using <!--more--> as a delimiter.
					[Default] FALSE.
				@type (array) $attributes
					An array of attributes to add to the field.
					The array key defines the attribute name and the array value defines the attribute value.
					[Default] array.
				@type (mixed) $default
					The default field value.
					[Default] FALSE.
				@type (array) $fields
					Must only be used for the 'group' field type.
					The array arguments are similar to the{@see beans_register_fields()} $fields arguments.
				@type (bool) $db_group
					Must only be used for the 'group' field type.
					It defines whether the group of fields registered should be saved as a group in the database or as individual entries.
					[Default] FALSE.
			}
			@param (string) $section
				The WP customize section to which the fields should be added.
				Add a unique id to create a new section.
			@param (array) $args{
				[Optional]
				Array of arguments used to register the fields.
				@type (string) $title
					The visible name of a controller section.
				@type (int) $priority
					This controls the order in which this section appears in the Theme Customizer sidebar.
					[Default] 30.
				@type (string) $description
					This optional argument can add additional descriptive text to the section.
					[Default] FALSE.
			}
			@return (_Beans_WP_Customize)|(bool)FALSE on failure.
			@reference
				[Plugin]/api/field/beans.php
				[Plugin]/api/customizer/runtime.php
		*/

		/**
		 * @reference (Beans)
		 * 	Filter the customizer fields.
		 * 	The dynamic portion of the hook name,$section,refers to the section id which defines the group of fields.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_wp_customize_fields_section/
		 * @param (array) $fields
		 * 	An array of customizer fields.
		*/
		$fields = apply_filters("beans_extension_wp_customize_field_{$section}",_beans_field::__standardize($fields));

		/**
		 * @since 1.0.1
		 * 	Stop here if the current page isn't concerned.
		 * @reference (WP)
		 * 	Whether the site is being previewed in the Customizer.
		 * 	https://developer.wordpress.org/reference/functions/is_customize_preview/
		*/
		if(!is_customize_preview()){
			return FALSE;
		}

		/**
		 * @since 1.0.1
		 * 	Stop here if the field can't be registered.
		 * @reference (Beans)
		 * 	Register fields.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_fields/
		*/
		if(!_beans_field::__register($fields,'wp_customize',$section)){
			return FALSE;
		}

		// Load the class only if this function is called to prevent unnecessary memory usage.
		return new _beans_runtime_customizer($section,$args);

	}// Method


	/* Hook
	_________________________
	*/
	public function customize_preview_init()
	{
		/**
			[ORIGINAL]
				beans_do_enqueue_wp_customize_assets
				https://www.getbeans.io/code-reference/functions/beans_do_enqueue_wp_customize_assets/
			@access (public)
				Enqueue Beans assets for the WordPress Customizer.
			@return (void)
			@reference
				[Plugin]/include/constant.php
		*/

		/**
		 * @reference (WP)
		 * 	Whether the site is being previewed in the Customizer.
		 * 	https://developer.wordpress.org/reference/functions/is_customize_preview/
		*/
		if(!is_customize_preview()){return;}

		/**
		 * @reference (WP)
		 * 	Enqueue a script.
		 * https://developer.wordpress.org/reference/functions/wp_enqueue_script/
		*/
		wp_enqueue_script(
			'beans-extension-wp-customize-preview',
			BEANS_EXTENSION_API_URL['asset'] . 'js/customize-preview.min.js',
			array(
				'jquery',
				'customize-preview',
			),
			BEANS_EXTENSION_VERSION,
			TRUE
		);

	}// Method


}// Class
endif;
// new _beans_customizer();
_beans_customizer::__get_instance();
