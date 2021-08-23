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
if(class_exists('_beans_term_meta') === FALSE) :
class _beans_term_meta
{
/**
 * @since 1.0.1
 * 	Functions for Beans Term Meta.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	beans_register_term_meta()
 * 	_beans_is_admin_term()
 * 	beans_get_term_meta()
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
	public static function __register(array $fields,$taxonomies,$section)
	{
		/**
			[ORIGINAL]
				beans_register_term_meta()
				https://www.getbeans.io/code-reference/functions/beans_register_term_meta/
			@access (public)
				Register Term Meta.
				This function should only be invoked through the 'admin_init' action.
			@param (array) $fields{
				Array of fields to register.
				@type (string) $id
					A unique id used for the field.
					This id will also be used to save the value in the database.
				@type (string) $type
					The type of field to use.
					Please refer to the Beans core field types for more information.
					Custom field types are accepted here.
				@type (string) $label
					The field label.
					[Default] FALSE
				@type (string) $description
					The field description.
					The description can be truncated using <!--more--> as a delimiter.
					[Default] FALSE
				@type (array) $attributes
					An array of attributes to add to the field.
					The array key defines the attribute name and the array value defines the attribute value.
					[Default] array
				@type (mixed) $default
					The default field value.
					[Default] FALSE
				@type (array) $fields
					Must only be used for the 'group' field type.
					The array arguments are similar to the{@see beans_register_fields()} $fields arguments.
				@type (bool) $db_group
					Must only be used for 'group' field type.
					Defines whether the group of fields registered should be saved as a group in the database or as individual entries.
					[Default] FALSE
			}
			@param (string)|(array) $taxonomies
				Array of taxonomies for which the term meta should be registered.
			@param (string) $section
				A section id to define the group of fields.
			@return (bool)
				TRUE on success,FALSE on failure.
			@reference
				[Plugin]/api/field/beans.php
		*/

		/**
		 * @reference (Beans)
		 * 	Filter the term meta fields.
		 * 	The dynamic portion of the hook name,$section,refers to the section id which defines the group of fields.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_term_meta_fields_section/
		 * @param (array) $fields
		 * 	An array of term meta fields.
		*/
		$fields = apply_filters("beans_extension_term_meta_field_{$section}",_beans_field::__standardize($fields));

		/**
		 * @reference (Beans)
		 * 	Filter the taxonomies used to define whether the fields set should be displayed or not.
		 * 	The dynamic portion of the hook name,$section,refers to the section id which defines the group of fields.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_term_meta_taxonomies_section/
		 * @param (string)|(array) $taxonomies
		 * 	Taxonomies used to define whether the fields set should be displayed or not.
		*/
		$taxonomies = apply_filters("beans_extension_term_meta_taxonomy_{$section}",(array)$taxonomies);

		// Stop here if the current page isn't concerned.
		if(!self::__is_admin($taxonomies) || !is_admin()){
			return FALSE;
		}

		/**
		 * @since 1.0.1
		 * 	Stop here if the field can't be registered.
		 * @reference (Beans)
		 * 	Register fields.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_fields/
		*/
		if(!_beans_field::__register($fields,'term_meta',$section)){
			return FALSE;
		}

		// Load the class only if this function is called to prevent unnecessary memory usage.
		new _beans_runtime_term_meta($section);

		return TRUE;

	}// Method


	/**
		[ORIGINAL]
			_beans_is_admin_term()
		@access (private)
			Check if the current screen is a given term.
		@param (array)|(bool) $taxonomies
			Array of taxonomies or TRUE for all taxonomies.
		@return (bool)
		@reference
			[Plugin]/utility/beans.php
	*/
	private static function __is_admin($taxonomies)
	{
		if(TRUE === $taxonomies){
			return TRUE;
		}

		$taxonomy = _beans_utility::__get_or_post_global_value('taxonomy');
		if(empty($taxonomy)){
			return FALSE;
		}
		return in_array($taxonomy,(array)$taxonomies,TRUE);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_setting($field_id,$default = FALSE,$term_id = FALSE)
	{
		/**
			[ORIGINAL]
				beans_get_term_meta()
				https://www.getbeans.io/code-reference/functions/beans_get_term_meta/
			@access (public)
				Get the current term meta value.
			@param (string) $field_id
				The term meta id searched.
			@param (mixed) $default
				[Optional]
				The default value to return if the term meta value doesn't exist.
			@param (int) $term_id
				[Optional]
				Overwrite the current term id.
			@return (mixed)
				Save data if the term meta value exists,otherwise set the default value.
			@reference
				[Plugin]/utility/beans.php
		*/
		if(!$term_id){
			/**
			 * @reference (WP)
			 * 	Retrieves the currently queried object.
			 * 	https://developer.wordpress.org/reference/functions/get_queried_object/
			*/
			$term_id = _beans_utility::__get_global_value('term_id',get_queried_object());
			$term_id = $term_id ? $term_id : _beans_utility::__get_global_value('tag_ID');
		}
		return $term_id ? get_option("beans_extension_term_{$term_id}_{$field_id}",$default) : $default;

	}// Method


}// Class
endif;
// new _beans_term_meta();
_beans_term_meta::__get_instance();
