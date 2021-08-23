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

// use stdClass;

/* Exec
______________________________
*/
if(class_exists('_beans_image') === FALSE) :
class _beans_image
{
/**
 * @since 1.0.1
 * 	The Beans Image component contains a set of functions to edit images on the fly.
 * 	Edited images are duplicates of the originals.
 * 	All modified images are stored in a shared folder,which makes it easy to delete them without impacting the originals.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_hook()
 * 	invoke_hook()
 * 	beans_edit_image()
 * 	beans_get_post_attachment()
 * 	beans_edit_post_attachment()
 * 	beans_get_images_dir()
 * 	beans_add_image_options_to_settings()
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
				[Plugin]/include/component.php
		*/

		// Merges user defined arguments into defaults array.
		return $this->set_parameter_callback(array(
			'register_image_option' => array(
				'tag' => 'add_action',
				'hook' => 'beans_extension_loaded_component_image'
			),
		));

	}// Method


	/* Method
	_________________________
	*/
	public static function __configure($src,array $args,$output = 'STRING')
	{
		/**
			[ORIGINAL]
				beans_edit_image()
				https://www.getbeans.io/code-reference/functions/beans_edit_image/
			@access (public)
				Edit image size and/or quality.
				Edited images are duplicates of the originals.
				All modified images are stored in a shared folder,which makes it easy to delete them without impacting the originals.
			@param (string) $src
				The image source.
			@param (array) $args
				An array of editor arguments,where the key is the{@see WP_Image_Editor} method name and the value is a numeric array of arguments for the method.
				Make sure to specify all of the arguments the WordPress editor's method requires.
				https://codex.wordpress.org/Class_Reference/WP_Image_Editor#Methods
			@param (string) $output...,
				[Optional]
				Returned format.
				Accepts 
				 - STRING,
				 - OBJECT,
				 - ARRAY_A,
				 - ARRAY_N.
				[Default]STRING.
			@return (string)|(array)
				Image source if output set the STRING,image data otherwise.
			@reference
				[Plugin]/api/image/runtime.php
		*/
		$editor = new _beans_runtime_image($src,$args,$output);
		return $editor->run();

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_setting($post_id,$size = 'full')
	{
		/**
			[ORIGINAL]
				beans_get_post_attachment()
				https://www.getbeans.io/code-reference/functions/beans_get_post_attachment/
			@access (public)
				Get attachment data.
				This function regroups all necessary data about a post attachment into an object.
			@param (string) $post_id
				The post id.
			@param (string) $size
				[Optional]
				The desired attachment size.
				Accepts 
				 - 'thumbnail',
				 - 'medium',
				 - 'large',
				 - 'full'.
			@return (object)
				Post attachment data.
		*/

		/**
		 * @reference (WP)
		 * 	Retrieve post thumbnail ID.
		 * 	https://developer.wordpress.org/reference/functions/get_post_thumbnail_id/
		*/
		$id = get_post_thumbnail_id($post_id);

		/**
		 * @reference (WP)
		 * 	Retrieves post data given a post ID or post object.
		 * 	https://developer.wordpress.org/reference/functions/get_post/
		*/
		$post = get_post($id);

		/**
		 * @reference (WP)
		 * 	Retrieves an image to represent an attachment.
		 * 	https://developer.wordpress.org/reference/functions/wp_get_attachment_image_src/
		*/
		$src = wp_get_attachment_image_src($id,$size);

		if($src){
			$obj = new \stdClass();
			$obj->id = $id;
			$obj->src = $src[0];
			$obj->width = $src[1];
			$obj->height = $src[2];
			/**
			 * @reference (WP)
			 * 	Retrieves a post meta field for the given post ID.
			 * 	https://developer.wordpress.org/reference/functions/get_post_meta/
			*/
			if(!empty(get_post_meta($id,'_wp_attachment_image_alt',TRUE))){
				$obj->alt = trim(strip_tags(get_post_meta($id,'_wp_attachment_image_alt',TRUE)));
			}
			else{
				$obj->alt = $post->post_title;
			}
			$obj->title = $post->post_title;
			$obj->caption = $post->post_excerpt;
			$obj->description = $post->post_content;

			return $obj;
		}

	}// Method


	/* Method
	_________________________
	*/
	public static function __edit($post_id,$args = array())
	{
		/**
			[ORIGINAL]
				beans_edit_post_attachment()
				https://www.getbeans.io/code-reference/functions/beans_edit_post_attachment/
			@access (public)
				Edit post attachment.
				This function is shortcut of{@see beans_edit_image()}.
				It should be used to edit a post attachment.
			@param (int) $post_id
				Post thumbnail ID (which can be 0 if the thumbnail is not set), or false if the post does not exist.
			@param (array) $args
				An array of editor arguments,where the key is the{@see WP_Image_Editor} method name and the value is a numeric array of arguments for the method.
				Make sure to specify all of the arguments the WordPress editor's method requires.
				https://codex.wordpress.org/Class_Reference/WP_Image_Editor#Methods
			@return (object)
				Edited post attachment data.
		*/

		/**
		 * @reference (WP)
		 * 	Determines whether a post has an image attached.
		 * 	https://developer.wordpress.org/reference/functions/has_post_thumbnail/
		*/
		if(!has_post_thumbnail($post_id)){
			return FALSE;
		}

		// Get full size image.
		$attachment = self::__get_setting($post_id,'full');
		$edited = self::__configure($attachment->src,$args,'ARRAY_A');
		if(!$edited){
			return $attachment;
		}
		return (object)array_merge((array)$attachment,$edited);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_directory()
	{
		/**
			[ORIGINAL]
				beans_get_images_dir()
				https://www.getbeans.io/code-reference/functions/beans_get_images_dir/
			@access (public)
				Get the "edited images" storage directory,i.e. where the "edited images" are/will be stored.
			@return (string)
		*/

		/**
		 * @reference (WP)
		 * 	Returns an array containing the current upload directory’s path and URL.
		 * 	https://developer.wordpress.org/reference/functions/wp_upload_dir/
		*/
		$wp_upload_dir = wp_upload_dir();

		/**
		 * @since 1.0.1
		 * 	Filter the edited images directory.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_images_dir/
		 * @param (string)
		 * 	Default path to the Beans' edited images storage directory.
		*/
		$dir = apply_filters('beans_extension_image_directory',trailingslashit($wp_upload_dir['basedir']) . 'beans-extension/image/');

		/**
		 * @reference (WP)
		 * 	Normalize a filesystem path.
		 * 	https://developer.wordpress.org/reference/functions/wp_normalize_path/
		*/
		return wp_normalize_path(trailingslashit($dir));

	}// Method


	/* Hook
	_________________________
	*/
	public function register_image_option()
	{
		/**
			[ORIGINAL]
				beans_add_image_options_to_settings()
			@since 1.5.0
			@access (public)
				Add the "image options" to the Beans Settings page.
			@return (_Beans_Image_Options)|(void)
			@reference
				[Plugin]/api/image/control.php
		*/
		$instance = _beans_control_image::__get_instance();
		// $instance = new _beans_control_image();
		$instance->init();

		return $instance;

	}// Method


}// Class
endif;
_beans_image::__get_instance();
