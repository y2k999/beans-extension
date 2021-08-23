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
if(class_exists('_beans_runtime_image') === FALSE) :
class _beans_runtime_image
{
/**
 * @since 1.0.1
 * 	This class provides the means to edit an image.
 * 
 * [TOC]
 * 	__construct()
 * 	run()
 * 	create_edited()
 * 	get_info()
 * 	rebuild_path()
 * 	check_edited()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $src
			The image source.
		@var (array) $args
			An array of editor arguments.
		@var (bool) $output
			Returned format.
		@var (string) $rebuilt_path
			Rebuilt path.
	*/
	private $src;
	private $args = array();
	private $output = FALSE;
	private $rebuilt_path;


	/* Constructor
	_________________________
	*/
	public function __construct($src,array $args,$output = 'STRING')
	{
		/**
			@access (public)
				_Beans_Image_Editor constructor.
			@param (string) $src
				The image source.
			@param (array) $args
				An array of editor arguments,where the key is the{@see WP_Image_Editor} method name and the value is a numeric array of arguments for the method.
				Make sure to specify all of the arguments the WordPress editor's method requires.
				https://codex.wordpress.org/Class_Reference/WP_Image_Editor#Methods
			@param (string) $output
				[Optional]
				Returned format.
				Accepts STRING,OBJECT,ARRAY_A,or ARRAY_N.
				[Default] STRING.
			@return (void)
			@reference
				[Plugin]/utility/beans.php
		*/

		// Convert internal url to a path.
		$local_source = _beans_utility::__url_to_path($this->src);

		$this->src = file_exists($local_source) ? $local_source : $src;
		$this->args = $args;
		$this->output = $output;
		$this->rebuilt_path = $this->rebuild_path();

	}// Method


	/* Method
	_________________________
	*/
	public function run()
	{
		/**
			[ORIGINAL]
				run()
			@since 1.5.0
				Refactored.
			@access (public)
				Run the editor.
			@return (array)|(object)|(string)
			@reference
				[Plugin]/utility/beans.php
		*/

		// Return the edited image's info packet when the file already exists or we successfully create it.
		if($this->check_edited() || $this->create_edited()){
			// Convert internal path to a url.
			return $this->get_info(_beans_utility::__path_to_url($this->rebuilt_path),TRUE);
		}
		return $this->get_info($this->src);

	}// Method


	/**
		[ORIGINAL]
			create_edited_image()
		@access (private)
			Edit the image and then store it in the rebuilt path.
		@return (bool)
			Returns TRUE when successful; else FALSE is returned.
	*/
	private function create_edited()
	{
		/**
		 * @reference (WP)
		 * 	Returns a WP_Image_Editor instance and loads file into it.
		 * 	https://developer.wordpress.org/reference/functions/wp_get_image_editor/
		*/
		$wp_editor = wp_get_image_editor($this->src);

		/**
		 * @since 1.0.1
		 * 	If an error occurred,bail out.
		 * @reference (WP)
		 * 	Checks whether the given variable is a WordPress Error.
		 * 	https://developer.wordpress.org/reference/functions/is_wp_error/
		*/
		if(is_wp_error($wp_editor)){
			return FALSE;
		}

		// Fire the editing task.
		foreach($this->args as $_method => $args){
			if(is_callable(array($wp_editor,$_method))){
				call_user_func_array([$wp_editor,$_method],(array) $args);
			}
		}

		// Save the "edited" image as a new image.
		$wp_editor->save($this->rebuilt_path);

		// Checks whether the given variable is a WordPress Error.
		return !is_wp_error($wp_editor);

	}// Method


	/**
		[ORIGINAL]
			get_image_info()
		@since 1.5.0
			Returns the image's information in the configured output format.
		@access (private)
		@param (string) $src
			Image's path or URL.
		@param (bool) $edited_image_exists
			When TRUE,include the dimensions.
		@return (array)|(object)
	*/
	private function get_info($src,$edited_image_exists = FALSE)
	{
		if('STRING' === $this->output){
			return $src;
		}

		if($edited_image_exists){
			/* phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case. */
			list($width,$height) = @getimagesize($this->rebuilt_path);
		}
		else{
			$width = NULL;
			$height = NULL;
		}

		if('ARRAY_N' === $this->output){
			return array(
				$src,
				$width,
				$height
			);
		}

		$image_info = array(
			'src'	=> $src,
			'width' => $width,
			'height' => $height,
		);

		if('OBJECT' === $this->output){
			return (object)$image_info;
		}
		return $image_info;

	}// Method


	/**
		[ORIGINAL]
			rebuild_image_path()
		@access (private)
			Rebuild the image's path.
		@return (string)
		@reference
			[Plugin]/api/image/beans.php
	*/
	private function rebuild_path()
	{
		$upload_dir = _beans_image::__get_directory();

		$info = pathinfo(preg_replace('#\?.*#','',$this->src));
		$query = substr(md5(@serialize($this->args)),0,7);
		/* phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged,WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize -- Valid use case. */
		$extension = $info['extension'];
		$filename = str_replace('.' . $extension,'',$info['basename']);

		return "{$upload_dir}{$filename}-{$query}.{$extension}";

	}// Method


	/**
		[ORIGINAL]
			edited_image_exists()
		@since 1.5.0
		@access (private)
			Checks if the edited image exists.
		@return (bool)
	*/
	private function check_edited()
	{
		return file_exists($this->rebuilt_path);

	}// Method


}// Class
endif;
