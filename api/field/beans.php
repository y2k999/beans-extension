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
if(class_exists('_beans_field') === FALSE) :
class _beans_field
{
/**
 * @since 1.0.1
 * 	The Beans Fields component offers a range of fields  which can be used in the WordPress admin.
 * 	Fields can be used as Options,Post Meta,Term Meta or WP Customizer Options.
 * 	Custom fields can easily be added,too.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	beans_register_fields()
 * 	beans_get_fields()
 * 	beans_field()
 * 	_beans_pre_standardize_fields()
*/

	/**
	 * Traits.
	*/
	use _trait_singleton;


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

	}// Method


	/* Method
	_________________________
	*/
	public static function __register(array $fields,$context,$section)
	{
		/**
			[ORIGINAL]
				beans_register_fields()
				https://www.getbeans.io/code-reference/functions/beans_register_fields/
			@access (public)
				Register the given fields.
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
					[Default] array.
				@type (mixed) $default
					The default field value.
					[Default] FALSE.
				@type (array) $fields
					Must only be used for the 'group' field type.
					The array arguments are similar to the $fields arguments.
					{@see beans_register_fields()}
				@type (bool) $db_group
					Must only be used for the 'group' field type.
					It defines whether the group of fields should be saved as a group or as individual entries in the database.
					[Default] FALSE.
			}
			@param (string) $context
				The context in which the fields are used.
				 - 'option' for options/settings pages,
				 - 'post_meta' for post fields,
				 - 'term_meta' for taxonomies fields
				 - 'wp_customize' for WP customizer fields.
			@param (string) $section
				A section ID to define the group of fields.
			@return (bool)
				TRUE on success,FALSE on failure.
			@reference
				[Plugin]/api/field/runtime.php
		*/
		if(empty($fields)){
			return FALSE;
		}
		// Load the class only if this function is called to prevent unnecessary memory usage.
		$class = _beans_runtime_field::__get_instance();
		// $class = new _beans_runtime_field();

		return $class->register($fields,$context,$section);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_setting($context,$section = FALSE)
	{
		/**
			[ORIGINAL]
				beans_get_fields()
				https://www.getbeans.io/code-reference/functions/beans_get_fields/
			@access (public)
				Get the registered fields.
				This function is used to get the previously registered fields in order to display them using{@see beans_field()}.
			@param (string) $context 
				The context in which the fields are used.
				 - 'option' for options/settings pages,
				 - 'post_meta' for post fields,
				 - 'term_meta' for taxonomies fields
				 - 'wp_customize' for WP customizer fields.
			@param (string)|(bool) $section
				[Optional]
				A section ID to define a group of fields. 
				This is mostly used for metaboxes and WP Customizer sections.
			@return (array)|(bool)
				Array of registered fields on success,false on failure.
			@reference
				[Plugin]/api/field/runtime.php
		*/
		return _beans_runtime_field::__get_setting($context,$section);

	}// Method


	/* Method
	_________________________
	*/
	public static function __render(array $field)
	{
		/**
			[ORIGINAL]
				beans_field()
				https://www.getbeans.io/code-reference/functions/beans_field/
			@since 1.5.0
				Moved rendering code out of _Beans_Fields.
			@access (public)
				Render (echo) a field.
				This function echos the field content. Must be used in the loop of fields obtained using {@see beans_get_fields()}.
			@param (array) $field
				The given field to render,obtained using{@see beans_get_fields()}.
			@return (void)
			@reference
				[Plugin]/api/html/beans.php
		*/

		// Load the class only if this function is called to prevent unnecessary memory usage.
		$group_field_type = 'group' === $field['type'];

		/**
		 * @reference (Beans)
		 * 	Echo open markup and attributes registered by ID.
		 * 	https://www.getbeans.io/code-reference/functions/beans_open_markup_e/
		*/
		_beans_html::__open_markup_e('beans_extension_field_wrap','div',array(
			'class' => 'bs-field-wrap bs-' . $field['type'] . ' ' . $field['context'],
		),$field);

		// Set fields loop to cater for groups.
		if($group_field_type){
			$fields = $field['field'];
		}
		else{
			$fields = array($field);
		}

		_beans_html::__open_markup_e('beans_extension_field_inside','div',array(
			'class' => 'bs-field-inside',
		),$fields);

		if($group_field_type){
			_beans_html::__open_markup_e('beans_extension_field_group_fieldset','fieldset',array(
				'class' => 'bs-field-fieldset',
			),$field);
				_beans_html::__open_markup_e('beans_extension_field_group_legend','legend',array(
						'class' => 'bs-field-legend',
					),$field);
					esc_html_e($field['label']);
				_beans_html::__close_markup_e('beans_extension_field_group_legend','legend',$field);
		}

		// Loop through fields.
		foreach($fields as $single_field){
			_beans_html::__open_markup_e('beans_extension_field[_' . $single_field['id'] . ']','div',array(
				'class' => 'bs-field bs-' . $single_field['type'],
			),$single_field);
				if($group_field_type){
					/**
					 * @since 1.0.1
					 * 	Fires the "beans_field_group_label" event to render this field's label.
					 * @param (array)$single_field
					 * 	The given single field.
					 */
					do_action('beans_extension_field_group_label',$single_field);
				}

				/**
				 * @since 1.0.1
				 * 	Fires the "beans_field_{type}" event to render this single field.
				 * @param (array)$single_field
				 * 	The given single field.
				 */
				do_action('beans_extension_field_' . $single_field['type'],$single_field);

			/**
			 * @reference (Beans)
			 * 	Echo close markup registered by ID.
			 * 	https://www.getbeans.io/code-reference/functions/beans_close_markup_e/
			*/
			_beans_html::__close_markup_e('beans_extension_field[_' . $single_field['id'] . ']','div',$single_field);
		}

		if($group_field_type){
			_beans_html::__close_markup_e('beans_extension_field_group_fieldset','fieldset',$field);
		}

			_beans_html::__close_markup_e('beans_extension_field_inside','div',$fields);
		_beans_html::__close_markup_e('beans_extension_field_wrap','div',$field);

	}// Method


	/* Method
	_________________________
	*/
	public static function __standardize(array $fields)
	{
		/**
			[ORIGINAL]
				_beans_pre_standardize_fields()
			@access (public)
				Pre-standardize the fields by keying each field by its ID.
			@param (array) $fields
				An array of fields to be standardized.
			@return (array)
			@reference
				[Plugin]/utility/beans.php
		*/
		$_fields = array();
		foreach($fields as $field){
			$_fields[$field['id']] = $field;
			if('group' === _beans_utility::__get_global_value('type',$field)){
				$_fields[$field['id']]['field'] = self::__standardize($field['field']);
			}
		}
		return $_fields;

	}// Method


}// Class
endif;
_beans_field::__get_instance();
