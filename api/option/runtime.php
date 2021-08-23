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
if(class_exists('_beans_runtime_option') === FALSE) :
final class _beans_runtime_option
{
/**
 * @since 1.0.1
 * 	Handle the Beans Options workflow.
 * 	This class provides the means to handle the Beans Options workflow.
 * 
 * [TOC]
 * 	__construct()
 * 	register()
 * 	enqueue_assets()
 * 	register_metabox()
 * 	render_metabox()
 * 	render_page()
 * 	process_actions()
 * 	save()
 * 	reset()
 * 	render_save_notice()
 * 	render_reset_notice()
*/

	/**
		@access(private)
			Class properties.
		@var (array) $_args
			Metabox arguments.
		@var (bool) $_success
			Form submission status.
		@var (string) $_section
			Field section.
	*/
	private $args = array();
	private $success = FALSE;
	private $section;


	/* Constructor
	_________________________
	*/
	public function __construct()
	{
		/**
			@access (public)
				Class constructor.
			@return (void)
		*/

	}// Method


	/* Method
	_________________________
	*/
	public function register($section,$args)
	{
		/**
			[ORIGINAL]
				register()
			@access (public)
				Register options.
			@param (string) $section
				Section of the field.
			@param(array) $args
				Arguments of the option.
			@return (void)
		*/
		$defaults = array(
			'title' => esc_html__('Undefined','beans-extension'),
			'context' => 'normal',
		);
		$this->section = $section;
		$this->args = array_merge($defaults,$args);

		// Register hooks.
		add_action('admin_enqueue_scripts',[$this,'enqueue_postbox']);

		$this->add_meta_box();

	}// Method


	/* Hook
	_________________________
	*/
	public function enqueue_postbox()
	{
		/**
			[ORIGINAL]
				enqueue_assets()
			@access (public)
				Enqueue scripts for all admin pages.
				https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
			@return (void)
		*/
		wp_enqueue_script('postbox');

	}// Method


	/**
		[ORIGINAL]
			register_metabox()
		@access (private)
			Register the Metabox with WordPress.
			https://developer.wordpress.org/reference/functions/add_meta_box/
		@return (void)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function add_meta_box()
	{
		add_meta_box(
			$this->section,
			$this->args['title'],
			[$this,'do_meta_boxes'],
			_beans_utility::__get_global_value('page'),
			$this->args['context'],
			'default'
		);

	}// Method


	/* Method
	_________________________
	*/
	public function do_meta_boxes()
	{
		/**
			[ORIGINAL]
				render_metabox()
			@access (public)
				Render the metabox's content.
				https://developer.wordpress.org/reference/functions/do_meta_boxes/
				The callback is fired by WordPress.
			@return (void)
			@reference
				[Plugin]/api/field/beans.php
		*/

		/**
		 * @reference (Beans)
		 * 	Get registered fields.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_fields/
		*/
		$fields = _beans_field::__get_setting('option',$this->section);
		if(empty($fields)){return;}

		foreach($fields as $field){
			/**
			 * @reference (Beans)
			 * 	Echo a field.
			 * 	https://www.getbeans.io/code-reference/functions/beans_field/
			*/
			_beans_field::__render($field);
		}

	}// Method


	/* Method
	_________________________
	*/
	public function render($page)
	{
		/**
			[ORIGINAL]
				render_page()
			@access (public)
				Render the page's (screen's) content.
			@global (wp_meta_boxes) $wp_meta_boxes
				https://developer.wordpress.org/apis/handbook/dashboard-widgets/
			@param (string)|(WP_Screen $page)
				The given page.
			@return (void)
			@reference
				[Plugin]/utility/beans.php
		*/
		global $wp_meta_boxes;
		$boxes = _beans_utility::__get_global_value($page,$wp_meta_boxes);
		if(!$boxes){return;}

		// Only add a column class if there is more than 1 metabox.
		$column_class = _beans_utility::__get_global_value('column',$boxes,array()) ? ' column' : FALSE;
		include dirname(__FILE__) . '/view/form.php';

	}// Method


	/* Method
	_________________________
	*/
	public function process()
	{
		/**
			[ORIGINAL]
				process_actions()
			@access (public)
				Prints admin screen notices.
				https://developer.wordpress.org/reference/hooks/admin_notices/
			@return (void)
			@reference
				[Plugin]/utility/beans.php
		*/
		if(_beans_utility::__post_global_value('beans_extension_save_option')){
			$this->save();
			add_action('admin_notices',[$this,'save_notice']);
		}

		if(_beans_utility::__post_global_value('beans_extension_reset_option')){
			$this->reset();
			add_action('admin_notices',[$this,'reset_notice']);
		}

	}// Method


	/**
		[ORIGINAL]
			save()
		@access (public)
			Save options.
			Updates the value of an option that was already added.
			https://developer.wordpress.org/reference/functions/update_option/
		@return (bool)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function save()
	{
		/**
		 * @reference (WP)
		 * 	Verifies that a correct security nonce was used with time limit.
		 * 	https://developer.wordpress.org/reference/functions/wp_verify_nonce/
		*/
		if(!wp_verify_nonce(_beans_utility::__post_global_value('beans_extension_option_nonce'),'beans_extension_option_nonce')){
			return FALSE;
		}

		$fields = _beans_utility::__post_global_value('beans_extension_field');
		if(!$fields){
			return FALSE;
		}

		foreach($fields as $field => $value){
			/**
			 * @reference (WP)
			 * 	Navigates through an array, object, or scalar, and removes slashes from the values.
			 * 	https://developer.wordpress.org/reference/functions/stripslashes_deep/
			*/
			update_option($field,stripslashes_deep($value));
		}
		$this->success = TRUE;

	}// Method


	/**
		[ORIGINAL]
			reset()
		@access (private)
			Reset options.
		@return (bool)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function reset()
	{
		/**
		 * @reference (WP)
		 * 	Verifies that a correct security nonce was used with time limit.
		 * 	https://developer.wordpress.org/reference/functions/wp_verify_nonce/
		*/
		if(!wp_verify_nonce(_beans_utility::__post_global_value('beans_extension_option_nonce'),'beans_extension_option_nonce')){
			return FALSE;
		}

		$fields = _beans_utility::__post_global_value('beans_extension_field');
		if(!$fields){
			return FALSE;
		}

		foreach($fields as $field => $value){
			/**
			 * @reference (WP)
			 * 	Removes option by name. Prevents removal of protected WordPress options.
			 * 	https://developer.wordpress.org/reference/functions/delete_option/
			*/
			delete_option($field);
		}
		$this->success = TRUE;

	}// Method


	/* Method
	_________________________
	*/
	public function save_notice()
	{
		/**
			[ORIGINAL]
				render_save_notice()
			@access (public)
				Render the save notice.
				https://developer.wordpress.org/reference/hooks/admin_notices/
			@return (void)
		*/
		if($this->success){
			include dirname(__FILE__) . '/view/notice-save-success.php';
			return;
		}
		include dirname(__FILE__) . '/view/notice-save-error.php';

	}// Method


	/* Method
	_________________________
	*/
	public function reset_notice()
	{
		/**
			[ORIGINAL]
				render_reset_notice()
			@access (public)
				Render the reset notice.
				https://developer.wordpress.org/reference/hooks/admin_notices/
			@return (void)
		*/
		if($this->success){
			include dirname(__FILE__) . '/view/notice-reset-success.php';
			return;
		}
		include dirname(__FILE__) . '/view/notice-reset-error.php';

	}// Method


}// Class
endif;
