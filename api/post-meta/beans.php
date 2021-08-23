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
if(class_exists('_beans_post_meta') === FALSE) :
class _beans_post_meta
{
/**
 * @since 1.0.1
 * 	Functions for post meta.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_hook()
 * 	invoke_hook()
 * 	beans_register_post_meta()
 * 	_beans_is_post_meta_conditions()
 * 	_beans_post_meta_page_template_reload()
 * 	beans_get_post_meta()
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
			@global (array) $_beans_extension_post_meta
				Post meta conditions global.
			@return (void)
			@reference
				[Plugin]/trait/singleton.php
		*/

		// Init properties.
		self::$_class = __utility_get_class(get_class($this));

		// Initialize post meta conditions.
		global $_beans_extension_post_meta;

		if(!isset($_beans_extension_post_meta)){
			$_beans_extension_post_meta = array();
		}

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
				Prints any scripts and data queued for the footer.
				https://developer.wordpress.org/reference/hooks/admin_print_footer_scripts/
			@return (array)
				The collection of hooks that is being registered (that is, actions or filters).
			@reference
				[Plugin]/trait/hook.php
		*/
		return $this->set_parameter_callback(array(
			'page_template_reload' => array(
				'tag' => 'add_action',
				'hook' => 'admin_print_footer_scripts'
			),
		));

	}// Method


	/* Method
	_________________________
	*/
	public static function __register(array $fields,$conditions,$section,$args = array())
	{
		/**
			[ORIGIANL]
				beans_register_post_meta()
				https://www.getbeans.io/code-reference/functions/beans_register_post_meta/
			@access (public)
				Register Post Meta.
				This function should only be invoked through the 'admin_init' action.
			@param (array) $fields{
				Array of fields to register.
				@type (string) $id
					A unique ID used for the field.
					This ID will also be used to save the value in the database.
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
				@type (array)$fields
					Must only be used for the 'group' field type.
					The array arguments are similar to the{@see beans_register_fields()} $fields arguments.
				@type (bool)$db_group
					Must only be used for the 'group' field type.
					Defines whether the group of fields registered should be saved as a group in the database or as individual entries.
					[Default] FALSE
			}
			@param (string)|(array) $conditions
				Array of 'post type ID(s)','post ID(s)' or 'page template slug(s)' for which the post meta should be registered.
				'page template slug(s)' must include the '.php' file extention.
				Set to TRUE to display everywhere.
			@global (array) $_beans_extension_post_meta
				Post meta conditions global.
			@param (string) $section
				A section ID to define the group of fields.
			@param (array) $args{
				[Optional]
					Array of arguments used to register the fields.
				@type (string) $title
					The metabox Title.
					[Default] 'Undefined'
				@type (string) $context
					Where on the page the metabox should be shown ('normal','advanced',or 'side').
					[Default] 'normal'
				@type (int) $priority
					The priority within the context where the boxes should show ('high','core','default' or 'low').
					[Default] 'high'
			}
			@return (bool)
				TRUE on success,FALSE on failure.
			@reference
				[Plugin]/api/field/beans.php
				[Plugin]/api/post-meta/runtime.php
		*/

		// Custom global variable.
		global $_beans_extension_post_meta;
		if(empty($fields)){
			return FALSE;
		}

		/**
		 * @since 1.0.1
		 * 	Filter the post meta fields.
		 * 	The dynamic portion of the hook name,$section,refers to the section ID which defines the group of fields.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_post_meta_fields_section/
		 * @param (array) $fields
		 * 	An array of post meta fields.
		*/
		$fields = apply_filters("beans_extension_post_meta_field_{$section}",_beans_field::__standardize($fields));

		/**
		 * @since 1.0.1
		 * 	Filter the conditions used to define whether the fields set should be displayed or not.
		 * 	The dynamic portion of the hook name,$section,refers to the section ID which defines the group of fields.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_post_meta_post_types_section/
		 * @param (string)|(array) $conditions
		 * 	Conditions used to define whether the fields set should be displayed or not.
		*/
		$conditions = apply_filters("beans_extension_post_meta_post_type_{$section}",$conditions);

		$_beans_extension_post_meta = array_merge($_beans_extension_post_meta,(array)$conditions);

		// Stop here if the current page isn't concerned.
		if(!self::__check_current_screen($conditions) || !is_admin()){
			return FALSE;
		}

		/**
		 * @since 1.0.1
		 * 	Stop here if the field can't be registered.
		 * @reference (Beans)
		 * 	Register fields.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_fields/
		*/
		if(!_beans_field::__register($fields,'post_meta',$section)){
			return FALSE;
		}

		// Load the class only if this function is called to prevent unnecessary memory usage.
		new _beans_runtime_post_meta($section,$args);

		return TRUE;

	}// Method


	/**
		[ORIGIANL]
			_beans_is_post_meta_conditions()
		@access (private)
			Check the current screen conditions.
		@param (array)|(bool) $conditions
			Conditions to show a Post Meta box.
			Array of 
			 - 'post type ID(s)',
			 - 'post ID(s)' or 
			 - 'page template slug(s)' 
			for which the post meta should be registered.
		@return (bool)
			Return TRUE if any condition is met,otherwise FALSE.
		@reference
			[Plugin]/utility/beans.php
	*/
	private static function __check_current_screen($conditions)
	{
		// If user has designated boolean TRUE,it's always TRUE.
		// Nothing more to do here.
		if(TRUE === $conditions){
			return TRUE;
		}

		// Check if it is a new post and treat it as such.
		if(FALSE !== stripos($_SERVER['REQUEST_URI'],'post-new.php')){
			$current_post_type = _beans_utility::__get_global_value('post_type');
			if(!$current_post_type){
				if(in_array('post',(array)$conditions,TRUE)){
					return TRUE;
				}
				else{
					return FALSE;
				}
			}
		}
		else{
			// Try to get ID from $_GET.
			$id_get = _beans_utility::__get_global_value('post');

			// Try to get ID from $_POST.
			$id_post = _beans_utility::__post_global_value('post_ID');
			if($id_get){
				$post_id = $id_get;
			}
			elseif($id_post){
				$post_id = $id_post;
			}

			if(!isset($post_id)){
				return FALSE;
			}

			/**
			 * @reference (WP)
			 * 	Retrieves the post type of the current post or of a given post.
			 * 	https://developer.wordpress.org/reference/functions/get_post_type/
			*/
			$current_post_type = get_post_type($post_id);
		}

		$statements = array(
			// Check post type.
			in_array($current_post_type,(array)$conditions,TRUE),

			// Check post ID.
			isset($post_id) && in_array($post_id,(array)$conditions,TRUE),

			/**
			 * @since 1.0.1
			 * 	Check page template.
			 * @reference (WP)
			 * 	Retrieves a post meta field for the given post ID.
			 * 	https://developer.wordpress.org/reference/functions/get_post_meta/
			*/
			isset($post_id) && in_array(get_post_meta($post_id,'_wp_page_template',TRUE),(array)$conditions,TRUE),
		);
		return in_array(TRUE,$statements,TRUE);

	}// Method


	/* Hook
	_________________________
	*/
	public function page_template_reload()
	{
		/**
			[ORIGIANL]
				_beans_post_meta_page_template_reload()
			@access (public)
				Reload post edit screen on page template change.
				Prints any scripts and data queued for the footer.
				https://developer.wordpress.org/reference/hooks/admin_print_footer_scripts/
			@global (array) $_beans_extension_post_meta
				Post meta conditions global.
			@global (string) $pagenow
				Used in wp-admin.
				https://codex.wordpress.org/Global_Variables
			@return (void)
		*/

		// Custom global variable.
		global $_beans_extension_post_meta;

		// WP global.
		global $pagenow;

		// Stop here if not editing a post object.
		if(!in_array($pagenow,array('post-new.php','post.php'),TRUE)){return;}

		/**
		 * @since 1.0.1
		 * 	Stop here if there is no post meta assigned to page templates.
		 * @reference (WP)
		 * 	Encode a variable into JSON, with some sanity checks.
		 * 	https://developer.wordpress.org/reference/functions/wp_json_encode/
		*/
		if(FALSE === stripos(wp_json_encode($_beans_extension_post_meta),'.php')){return;}
		include dirname(__FILE__) . '/view/script.php';

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_setting($meta_key,$default = FALSE,$post_id = '')
	{
		/**
			[ORIGIANL]
				beans_get_post_meta()
				https://www.getbeans.io/code-reference/functions/beans_get_post_meta/
			@access (public)
				Get the post's meta value. 
				When no post ID is given, get the current post's meta value.
				This function is a shortcut of get_post_meta().
				https://codex.wordpress.org/Function_Reference/get_post_meta
			@param (string) $meta_key
				The post meta ID searched.
			@param (mixed) $default
				[Optional]
				The default value to return of the post meta value doesn't exist.
			@param (int)|(string)$post_id
				[Optional]
				Overwrite the current post ID.
			@return (mixed)
				Returns the meta value, if it exists;else, the default value is returned.
			@reference
				[Plugin]/utility/beans.php
		*/
		if($post_id === 0){
			/**
			 * @reference (WP)
			 * 	Retrieve the ID of the current item in the WordPress Loop.
			 * 	https://developer.wordpress.org/reference/functions/get_the_id/
			*/
			$post_id = get_the_ID();
			if(!$post_id){
				$post_id = (int)_beans_utility::__get_global_value('post');
			}
		}

		if(!$post_id){
			return $default;
		}
		/**
		 * @reference (WP)
		 * 	Retrieves a post meta field for the given post ID.
		 * 	https://developer.wordpress.org/reference/functions/get_post_meta/
		*/
		$post_meta = get_post_meta($post_id);

		if(isset($post_meta[$meta_key])){
			return get_post_meta($post_id,$meta_key,TRUE);
		}
		return $default;

	}// Method


}// Class
endif;
// new _beans_post_meta();
_beans_post_meta::__get_instance();
