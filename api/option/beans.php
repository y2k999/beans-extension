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
if(class_exists('_beans_option') === FALSE) :
class _beans_option
{
/**
 * @since 1.0.1
 * 	Fields can be attached to an existing option page by hooking a callback function to admin_init and using beans_register_options().
 * 	Fields values can easily be retrieved in the front-end using the WPs core get_option() function.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_hook()
 * 	invoke_hook()
 * 	beans_register_options()
 * 	beans_options()
 * 	_beans_options_page_actions()
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
				This hook is fired once WP, all plugins, and the theme are fully loaded and instantiated.
				https://developer.wordpress.org/reference/hooks/wp_loaded/
			@return (array)
				The collection of hooks that is being registered (that is, actions or filters).
			@reference
				[Plugin]/trait/hook.php
		*/
		return $this->set_parameter_callback(array(
			'render_screen' =>array(
				'tag' => 'add_action',
				'hook' => 'wp_loaded'
			),
		));

	}// Method


	/* Method
	_________________________
	*/
	public static function __register(array $fields,$menu_slug,$section,$args = array())
	{
		/**
			[ORIGINAL]
				beans_register_options()
				https://www.getbeans.io/code-reference/functions/beans_register_options/
			@access (public)
				Register options.
				This function should only be invoked through the 'admin_init' action.
			@param (array) $fields{
				Array of fields to register.
				@type (string) $id
					A unique id used for the field.
					This id will also be used to save the value in the database.
				@type (string) $type
					The type of field to use.
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
					[Default] array
				@type (mixed) $default
					The default field value.
					[Default] FALSE
				@type (array) $fields
					Must only be used for 'group' field type.
					The array arguments are similar to the $fields arguments.
					{@see beans_register_fields()}
				@type (bool) $db_group
					Must only be used for 'group' field types.
					Defines whether the group of fields registered should be saved as a group in the database or as individual entries.
					[Default] FALSE
			}
			@param (string) $menu_slug
				The menu slug used by fields.
			@param (string) $section
				A section id to define the group of fields.
			@param (array) $args{
				[Optional]
					Array of arguments used to register the fields.
				@type (string) $title 
					The metabox title.
					[Default] 'Undefined'
				@type (string) $context
					Where on the page where the metabox should be shown ('normal','column').
					[Default] 'normal'.
			}
			@return (bool)
				True on success,false on failure.
			@reference
				[Plugin]/utility/beans.php
				[Plugin]/api/field/beans.php
				[Plugin]/api/option/runtime.php
		*/

		/**
		 * @since 1.0.1
		 * 	Filter the options fields.
		 * 	The dynamic portion of the hook name,$section,refers to the section id which defines the group of fields.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_options_fields_section/
		 * @param (array) $fields
		 * 	An array of options fields.
		*/
		$fields = apply_filters("beans_extension_option_field_{$section}",_beans_field::__standardize($fields));

		/**
		 * @since 1.0.1
		 * 	Filter the options fields menu slug.
		 * 	The dynamic portion of the hook name,$section,refers to the section id which defines the group of fields.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_options_menu_slug_section/
		 * @param (array) $menu_slug
		 * 	The menu slug.
		*/
		$menu_slug = apply_filters("beans_extension_option_menu_slug_{$section}",$menu_slug);

		// Stop here if the page isn't concerned.
		if((_beans_utility::__get_global_value('page') !== $menu_slug) || !is_admin()){return;}

		/**
		 * @since 1.0.1
		 * 	Stop here if the field can't be registered.
		 * @reference (Beans)
		 * 	Register fields.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_fields/
		*/
		if(!_beans_field::__register($fields,'option',$section)){
			return FALSE;
		}

		// Load the class only if this function is called to prevent unnecessary memory usage.
		$class = new _beans_runtime_option();
		$class->register($section,$args);

		return TRUE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __render($menu_slug)
	{
		/**
			[ORIGINAL]
				beans_options()
				https://www.getbeans.io/code-reference/functions/beans_options/
			@access (public)
				Echo the registered options.
				This function echos the options registered for the defined admin page.
			@param (array) $menu_slug
				The menu slug used to register the options.
			@return (bool)
			@reference
				[Plugin]/api/option/runtime.php
		*/
		$class = new _beans_runtime_option();
		$class->render($menu_slug);

	}// Method


	/* Method
	_________________________
	*/
	public function render_screen()
	{
		/**
			[ORIGINAL]
				_beans_options_page_actions()
			@access (public)
				Fires the options form actions.
			@return (void)
			@reference
				[Plugin]/utility/beans.php
				[Plugin]/api/option/runtime.php
		*/
		if(!_beans_utility::__post_global_value('beans_extension_option_nonce')){return;}

		// Load the class only if this function is called to prevent unnecessary memory usage.
		$class = new _beans_runtime_option();
		$class->process();

	}// Method


}// Class
endif;
// new _beans_option();
_beans_option::__get_instance();
