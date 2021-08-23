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
if(class_exists('_beans_runtime_term_meta') === FALSE) :
final class _beans_runtime_term_meta
{
/**
 * @since 1.0.1
 * 	Handle the Beans Term Meta workflow.
 * 	Class to handle the Beans Term Meta Workflow.
 * 
 * [TOC]
 * 	__construct()
 * 	set_hook()
 * 	invoke_hook()
 * 	do_once()
 * 	render()
 * 	nonce()
 * 	save()
 * 	delete()
 */

	/**
		@access (private)
			Class properties.
		@var (string) $_class
			Name/Identifier with Prefix.
		@var (array) $hook
			The collection of hooks that is being registered (that is, actions or filters).
		@var (string) $section
	*/
	private static $_class = '';
	private $hook = array();
	private $section;

	/**
	 * Traits.
	*/
	use _trait_hook;


	/* Method
	_________________________
	 */
	public function __construct($section)
	{
		/**
			@access (public)
				Constructor.
			@param (string) $section
				Field section.
			@return (void)
		*/

		// Init properties.
		self::$_class = __utility_get_class(get_class($this));

		$this->section = $section;
		$this->do_once();

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
			'render' => array(
				'tag' => 'add_action',
				'hook' => _beans_utility::__get_global_value('taxonomy') . '_edit_form_fields'
			),
		));

	}// Method


	/**
		[ORIGINAL]
			do_once()
			Trigger actions only once.
		@access (private)
			Fires after a term has been updated, but before the term cache has been cleaned.
			https://developer.wordpress.org/reference/hooks/edit_term/
			Fires after a term is deleted from the database and the cache is cleaned.
			https://developer.wordpress.org/reference/hooks/delete_term/
		@return (void)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function do_once()
	{
		static $did_once = FALSE;
		if($did_once){return;}

		// Register hooks.
		add_action(_beans_utility::__get_global_value('taxonomy') . '_edit_form',[$this,'nonce']);
		add_action('edit_term',[$this,'save']);
		add_action('delete_term',[$this,'delete'],10,3);

		$did_once = TRUE;

	}// Method


	/* Hook
	_________________________
	 */
	public function render()
	{
		/**
			[ORIGINAL]
				render_fields()
			@access (public)
				Render fields content.
			@return (void)
			@reference
				[Plugin]/api/action/beans.php
				[Plugin]/api/html/beans.php
				[Plugin]/api/field/beans.php
		*/

		/**
		 * @reference (Beans)
		 * 	Remove an action.
		 * 	https://www.getbeans.io/code-reference/functions/beans_remove_action/
		 * 	Modify an action hook.
		 * 	https://www.getbeans.io/code-reference/functions/beans_modify_action_hook/
		*/
		_beans_action::__remove_action('beans_extension_field_label');
		_beans_action::__modify_hook('beans_extension_field_description','beans_field_wrap_after_markup');

		/**
		 * @reference (Beans)
		 * 	Modify opening and closing HTML tag. Also works for self-closed markup.
		 * 	https://www.getbeans.io/code-reference/functions/beans_modify_markup/
		 * 	Add attribute to markup.
		 * 	https://www.getbeans.io/code-reference/functions/beans_add_attribute/
		*/
		_beans_html::__modify_markup('beans_extension_field_description','p');
		_beans_html::__add_attribute('beans_extension_field_description','class','description');

		/**
		 * @reference (Beans)
		 * 	Get registered fields.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_fields/
		*/
		foreach(_beans_field::__get_setting('term_meta',$this->section) as $field){
			include dirname(__FILE__) . '/view/field.php';
		}

	}// Method


	/* Hook
	_________________________
	 */
	public function nonce()
	{
		/**
			[ORIGINAL]
				render_nonce()
			@access (public)
				Render term meta nonce.
			@return (void)
		*/
		include dirname(__FILE__) . '/view/nonce.php';

	}// Method


	/* Hook
	_________________________
	 */
	public function save($term_id)
	{
		/**
			[ORIGINAL]
				save()
			@access (public)
				Save term meta.
				Fires after a term has been updated, but before the term cache has been cleaned.
				https://developer.wordpress.org/reference/hooks/edit_term/
			@param (int) $term_id
			@return (null)|(int)
				Null on success or Term ID on fail.
			@reference
				[Plugin]/utility/beans.php
		*/

		// Checks if WP is doing ajax.
		if(_beans_utility::__doing_ajax()){
			return $term_id;
		}

		/**
		 * @reference (WP)
		 * 	Verifies that a correct security nonce was used with time limit.
		 * 	https://developer.wordpress.org/reference/functions/wp_verify_nonce/
		*/
		if(!wp_verify_nonce(_beans_utility::__get_global_value('beans_extension_term_meta_nonce'),'beans_extension_term_meta_nonce')){
			return $term_id;
		}

		$fields = _beans_utility::__post_global_value('beans_extension_field');
		if(!$fields){
			return $term_id;
		}

		foreach($fields as $field => $value){
			/**
			 * @reference (WP)
			 * 	Navigates through an array, object, or scalar, and removes slashes from the values.
			 * 	https://developer.wordpress.org/reference/functions/stripslashes_deep/
			*/
			update_option("beans_extension_term_{$term_id}_{$field}",stripslashes_deep($value));
		}

	}// Method


	/* Hook
	_________________________
	 */
	public function delete($term_id)
	{
		/**
			[ORIGINAL]
				delete()
			@access (public)
				Delete term meta.
				Fires after a term is deleted from the database and the cache is cleaned.
				https://developer.wordpress.org/reference/hooks/delete_term/
			@global (wpdb) $wpdb
				WordPress database access abstraction class.
				https://developer.wordpress.org/reference/classes/wpdb/
			@param (int) $term_id
			@return (void)
		*/

		// WP global.
		global $wpdb;

		$wpdb->query($wpdb->prepare(
			"DELETE FROM $wpdb->options WHERE option_name LIKE %s",
			"beans_extension_term_{$term_id}_%"
		));

	}// Method


}// Class
endif;
