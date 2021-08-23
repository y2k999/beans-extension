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
if(class_exists('_beans_runtime_post_meta') === FALSE) :
final class _beans_runtime_post_meta
{
/**
 * @since 1.0.1.
 * 	Handle the Beans Post Meta workflow.
 * 	This class provides the means to add Post Meta boxes.
 * 
 * [TOC]
 * 	__construct()
 * 	set_hook()
 * 	invoke_hook()
 * 	do_once()
 * 	render_nonce()
 * 	register_metabox()
 * 	render_metabox_content()
 * 	save()
 * 	save_attachment()
 * 	ok_to_save()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $_class
			Name/Identifier with Prefix.
		@var (array) $hook
			The collection of hooks that is being registered (that is, actions or filters).
		@var(array) $args
			Metabox arguments.
		@var(string) $section
			Fields section.
	*/
	private static $_class = '';
	private $hook = array();
	private $args = array();
	private $section;

	/**
	 * Traits.
	*/
	use _trait_hook;


	/* Constructor
	_________________________
	*/
	public function __construct($section,$args)
	{
		/**
			@access(public)
				Class Constructor.
			@param (string) $section
				Field section.
			@param (array) $args
				Arguments of the field.
			@return (void)
		*/

		// Init properties.
		self::$_class = __utility_get_class(get_class($this));

		$defaults = array(
			'title' => esc_html__('Undefined','beans-extension'),
			'context' => 'normal',
			'priority' => 'high',
		);

		$this->section = $section;
		$this->args = array_merge($defaults,$args);

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
			'register_metabox' => array(
				'tag' => 'add_action',
				'hook' => 'add_meta_boxes'
			),
		));

	}// Method


	/**
		[ORIGINAL]
			do_once()
		@access (private)
			Trigger actions only once.
		@return (void)
	*/
	private function do_once()
	{
		static $did_once = FALSE;
		if($did_once){return;}

		// Register hooks.
		add_action('edit_form_top',[$this,'render_nonce']);
		add_action('save_post',[$this,'save']);
		add_filter('attachment_fields_to_save',[$this,'save_attachment']);

		$did_once = TRUE;

	}// Method


	/* Hook
	_________________________
	*/
	public function render_nonce()
	{
		/**
			[ORIGINAL]
				render_nonce()
			@access (public)
				Render post meta nonce.
				Fires at the beginning of the edit form.
				https://developer.wordpress.org/reference/hooks/edit_form_top/
			@return (void)
		*/
		include dirname(__FILE__) . '/view/nonce.php';

	}// Method


	/* Hook
	_________________________
	*/
	public function register_metabox($post_type)
	{
		/**
			[ORIGINAL]
				register_metabox()
			@access (public)
				Adds a meta box to one or more screens.
				https://developer.wordpress.org/reference/functions/add_meta_box/
			@param (string) $post_type
				Name of the post type.
			@return (void)
		*/
		add_meta_box(
			$this->section,
			$this->args['title'],
			[$this,'do_meta_boxes'],
			$post_type,
			$this->args['context'],
			$this->args['priority']
		);

	}// Method


	/* Method
	_________________________
	*/
	public function do_meta_boxes()
	{
		/**
			[ORIGINAL]
				render_metabox_content()
			@access (public)
				Render metabox content.
				Fires after meta boxes have been added.
				https://developer.wordpress.org/reference/hooks/do_meta_boxes/
			@return (void)
			@reference
				[Plugin]/api/field/beans.php
		*/
		foreach(_beans_field::__get_setting('post_meta',$this->section) as $field){
			/**
			 * @reference (Beans)
			 * 	Echo a field.
			 * 	https://www.getbeans.io/code-reference/functions/beans_field/
			*/
			_beans_field::__render($field);
		}

	}// Method


	/* Hook
	_________________________
	*/
	public function save($post_id)
	{
		/**
			[ORIGINAL]
				save()
			@access (public)
				Save Post Meta.
				Fires once a post has been saved.
				https://developer.wordpress.org/reference/hooks/save_post/
			@param (int) $post_id
				Post ID.
			@return (mixed)
			@reference
				[Plugin]/utility/beans.php
		*/

		// Checks if WP is doing an autosave.
		if(_beans_utility::__doing_autosave()){
			return FALSE;
		}

		$fields = _beans_utility::__post_global_value('beans_field');
		if(!$this->ok_to_save($post_id,$fields)){
			return $post_id;
		}

		foreach($fields as $field => $value){
			update_post_meta($post_id,$field,$value);
		}

	}// Method


	/* Hook
	_________________________
	*/
	public function save_attachment($attachment)
	{
		/**
			[ORIGINAL]
				save_attachment()
			@access (public)
				Save Post Meta for attachment.
				Filters the attachment fields to be saved.
				https://developer.wordpress.org/reference/hooks/attachment_fields_to_save/
			@param (array) $attachment
				Attachment data.
			@return (mixed)
			@reference
				[Plugin]/utility/beans.php
		*/

		// Checks if WP is doing an autosave.
		if(_beans_utility::__doing_autosave()){
			return $attachment;
		}

		$fields = _beans_utility::__post_global_value('beans_field');
		if(!$this->ok_to_save($attachment['ID'],$fields)){
			return $attachment;
		}

		foreach($fields as $field => $value){
			update_post_meta($attachment['ID'],field,$value);
		}
		return $attachment;

	}// Method


	/**
		[ORIGINAL]
			ok_to_save()
		@access (private)
			Check if all criteria are met to safely save post meta.
		@param (int) $id
			The Post Id.
		@param (array) $fields
			The array of fields to save.
		@return (bool)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function ok_to_save($id,$fields)
	{
		/**
		 * @reference (WP)
		 * 	Verifies that a correct security nonce was used with time limit.
		 * 	https://developer.wordpress.org/reference/functions/wp_verify_nonce/
		*/
		if(!wp_verify_nonce(_beans_utility::__post_global_value('beans_extension_post_meta_nonce'),'beans_extension_post_meta_nonce')){
			return FALSE;
		}

		/**
		 * @reference (WP)
		 * 	Returns whether the current user has the specified capability.
		 * 	https://developer.wordpress.org/reference/functions/current_user_can/
		*/
		if(!current_user_can('edit_post',$id)){
			return FALSE;
		}
		return !empty($fields);

	}// Method


}// Class
endif;
