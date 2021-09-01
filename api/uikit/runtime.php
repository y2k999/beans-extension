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
if(class_exists('_beans_runtime_uikit') === FALSE) :
final class _beans_runtime_uikit
{
/**
 * @since 1.0.1
 * 	This class handles the UIkit components.
 * 	Compile UIkit components.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	compile()
 * 	get_all_component()
 * 	get_autoload_component()
 * 	compile_style()
 * 	compile_script()
 * 	register_less_component()
 * 	register_js_component()
 * 	get_registered_component_path()
 * 	get_less_directory()
 * 	get_js_directory()
 * 	get_component_from_directory()
 * 	get_all_file()
 * 	remove_duplicate_value()
 * 	init_component_dependency()
 * 	convert_to_filename()
 * 	ignore_component()
*/

	/**
		@access (private)
			Class properties.
		@var(array) $ignored_components
			Components to ignore.
		@var(array) $configured_components_dependencies
			The configured components' dependencies.
	*/
	private $ignored_component = array(
		// Under uikit theme folders.
		'uikit-customizer',
		'uikit'
	);
	private $configured_component_dependency;


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
	public function compile()
	{
		/**
			[ORIGINAL]
				compile()
			@access (public)
				Compile enqueued items.
			@return (void)
		*/
		$this->compile_style();
		$this->compile_script();

	}// Method


	/* Method
	_________________________
	*/
	public function get_all_component($type)
	{
		/**
			[ORIGINAL]
				get_all_components()
			@access (public)
				Get all components.
			@param (string) $type
				Type of UIkit components ('core' or 'add-ons').
			@return (array)
				Clean up by removing duplicates and empties.
			@reference
				[Plugin]/utility/beans.php
		*/

		// Fetch all directories.
		$directories = array_merge(
			$this->get_less_directory($type),
			$this->get_js_directory($type)
		);
		$components = array();

		foreach($directories as $dir_path){
			if(!is_dir($dir_path)){continue;}
			// Build an array of component names (i.e. filenames only).
			$component_names = array_map(
				[$this,'convert_to_filename'],
				$this->get_all_file($dir_path)
			);
			// Maybe joins two arrays together without requiring an additional variable assignment upon return.
			_beans_utility::__join_array($components,$component_names);
		}

		// Remove duplicate values from the given array and re-indexes to start at element 0.
		return array_filter(_beans_utility::__array_unique($components));

	}// Method


	/* Method
	_________________________
	*/
	public function get_autoload_component(array $components)
	{
		/**
			[ORIGINAL]
				get_autoload_components()
			@access (public)
				Get all of the required dependencies for the given components.
			@param (array) $components
				The given components to search for dependencies.
			@return (array)
			@reference
				[Plugin]/utility/beans.php
		*/
		$dependencies = array(
			'core' => array(),
			// 'add-ons' => array(),
			'add-on' => array(),
		);
		$this->init_component_dependency();

		// Build dependencies for each component.
		foreach((array)$components as $component){
			$component_dependencies = _beans_utility::__get_global_value($component,$this->configured_component_dependency,array());
			foreach($component_dependencies as $type => $dependency){
				$dependencies[$type] = array_merge($dependencies[$type],$dependency);
			}
		}
		return $this->remove_duplicate_value($dependencies);

	}// Method


	/**
		[ORIGINAL]
			compile_styles()
		@since 1.5.0
		@access (private)
			Compile the styles.
		@return (void)
		@reference
			[Plugin]/utility/beans.php
			[Plugin]/api/compiler/beans.php
	*/
	private function compile_style()
	{
		/**
			@description
				Filter UIkit enqueued style components.
			@param (array) $components
				An array of UIkit style component files.
		*/
		$styles = apply_filters('beans_extension_uikit_euqueued_style',$this->register_less_component());

		// If there are no styles to compile,bail out.
		if(empty($styles)){return;}

		/**
		 * @reference (WP)
		 * 	Filter Uikit style compiler arguments.
		 * @param (array)$components
		 * 	An array of Uikit style compiler arguments.
		*/
		$args = apply_filters('beans_extension_uikit_euqueued_style_args',array());
		// The compiler ID
		$id = apply_filters('beans_extension_uikit_euqueued_style_id','uikit');

		/**
		 * @reference (Beans)
		 * 	Compile LESS fragments, convert to CSS and enqueue compiled file.
		 * 	https://www.getbeans.io/code-reference/functions/beans_compile_less_fragments/
		*/
		_beans_compiler::__fragment_less($id,array_unique($styles),$args);

	}// Method


	/**
		[ORIGINAL]
			compile_scripts()
		@since 1.5.0
		@access (private)
			Compile the scripts.
		@return (void)
		@reference
			[Plugin]/api/compiler/beans.php
	*/
	private function compile_script()
	{
		/**
			@description
				Filter Uikit enqueued script components.
			@param (array)$components
				An array of Uikit script component files.
		*/
		$scripts = apply_filters('beans_extension_uikit_euqueued_script',$this->register_js_component());

		// If there are no scripts to compile,bail out.
		if(empty($scripts)){return;}

		/**
		 * @reference (WP)
		 * 	Filter Uikit script compiler arguments.
		 * @param (array) $components
		 * 	An array of Uikit script compiler arguments.
		*/
		$args = apply_filters('beans_extension_uikit_euqueued_scripts_args',array(
			'dependency' => array('jquery'),
		));
		// The compiler ID
		$id = apply_filters('beans_extension_uikit_euqueued_scripts_id','uikit');

		/**
		 * @reference (Beans)
		 * 	Compile JS fragments and enqueue compiled file.
		 * 	https://www.getbeans.io/code-reference/functions/beans_compile_js_fragments/
		*/
		_beans_compiler::__fragment_js($id,array_unique($scripts),$args);

	}// Method


	/**
		[ORIGINAL]
			register_less_components()
		@access (private)
			Register less components.
		@return (array)
		@reference
			[Plugin]/include/constant.php
	*/
	private function register_less_component()
	{
		$components = $this->get_registered_component_path(array('variables'));
		if(empty($components)){
			return array();
		}

		// Add fixes.
		$components[] = BEANS_EXTENSION_API_PATH['uikit'] . 'asset/fix.less';
		return $components;

	}// Method


	/**
		[ORIGINAL]
			register_js_components()
		@global (array) $_beans_extension_component_setting
			API components setting global.
		@access (private)
			Register JavaScript components.
		@return (array)
			[Plugin]/include/component.php
			[Plugin]/admin/tab/general.php
	*/
	private function register_js_component()
	{
		// Custom global variable.
		global $_beans_extension_component_setting;
		switch($_beans_extension_component_setting['general']['uikit']){
			case 'uikit2' :
				$components = apply_filters('beans_extension_registered_js_component',array(
					'core',
					'utility',
					'touch'
				));
				break;
			case 'uikit3' :
			default :
				$components = apply_filters('beans_extension_registered_js_component',array(
					// 'alert'
				));
				break;
		}
		return $this->get_registered_component_path($components,FALSE);

	}// Method


	/**
		[ORIGINAL]
			get_registered_component_paths()
		@since 1.5.0
		@access (private)
			Get an array of registered component paths,i.e. absolute path to each component file.
		@global (array) $_beans_extension_uikit_enqueued_item
			Enqueued Uikit items global.
		@param (array) $core_components
			Array of core components.
		@param (bool) $is_less...,
			[Optional]
			When TRUE,get the registered LESS components;else,get the registered JavaScript components.
		@return (array)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function get_registered_component_path(array $core_components,$is_less = TRUE)
	{
		// Custom global variable.
		global $_beans_extension_uikit_enqueued_item;

		$components = array();
		foreach($_beans_extension_uikit_enqueued_item['component'] as $type => $items){
			// Add core before the components.
			if('core' === $type){
				$items = array_merge($core_components,$items);
			}

			// Fetch components from directories.
			$component_directories = $this->get_component_from_directory(
				$items,
				$is_less ? $this->get_less_directory($type) : $this->get_js_directory($type),
				$is_less ? 'styles' : 'scripts'
			);
			// Maybe joins two arrays together without requiring an additional variable assignment upon return.
			_beans_utility::__join_array($components,$component_directories);
		}
		return $components;

	}// Method


	/**
		[ORIGINAL]
			get_less_directories()
		@access (private)
			Get LESS directories.
		@global (array) $_beans_extension_uikit_enqueued_item
			Enqueued UIkit items global.
		@param (string) $type
			Type of the UIkit components.
		@return (array)
		@reference
			[Plugin]/include/constant.php
	*/
	private function get_less_directory($type)
	{
		if('add-on' === $type){
			$type = 'component';
		}

		// Custom global variable.
		global $_beans_extension_uikit_enqueued_item;

		// Define the Uikit src directory.
		$directories = array(BEANS_EXTENSION_API_PATH['uikit'] . 'asset/less/' . $type);

		// Add the registered theme directories.
		foreach($_beans_extension_uikit_enqueued_item['theme'] as $id => $directory){
			/**
			 * @reference (WP)
			 * 	Normalize a filesystem path.
			 * 	https://developer.wordpress.org/reference/functions/wp_normalize_path/
			*/
			$directories[] = wp_normalize_path(untrailingslashit($directory));
		}
		return $directories;

	}// Method


	/**
		[ORIGINAL]
			get_js_directories()
		@access (private)
			Get JavaScript directories.
		@param (string) $type
		@return (array)
		@reference
			[Plugin]/include/constant.php
	*/
	private function get_js_directory($type)
	{
		if('add-on' === $type){
			$type = 'component';
		}
		return array(BEANS_EXTENSION_API_PATH['uikit'] . 'asset/js/' . $type);

	}// Method


	/**
		[ORIGINAL]
			get_components_from_directory()
		@access (private)
			Get components from directories.
		@param (array) $components
			Array of Uikit components.
		@param (array) $directories
			Array of directories containing the Uikit components.
		@param (string) $format
			File format.
		@return (array)
	*/
	private function get_component_from_directory(array $components,array $directories,$format)
	{
		if(empty($components)){
			return array();
		}
		$extension = 'styles' === $format ? 'less' : 'min.js';
		$return = array();

		foreach($components as $component){
			// Fetch the components from all directories set.
			foreach($directories as $directory){
				$file = trailingslashit($directory) . $component . '.' . $extension;

				// Make sure the file exists.
				if(is_readable($file)){
					$return[] = $file;
				}
			}
		}
		return $return;

	}// Method


	/**
		[ORIGINAL]
			get_all_files()
		@since 1.5.0
		@access (private)
			Get all of the files and folders from the given directory.
			When on a Linux-based machine,removes the '.' and '..' files.
		@param (string) $directory
			Absolute path to the source directory.
		@return (array)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function get_all_file($directory)
	{
		// List files and directories inside of the specified path.
		return _beans_utility::__scan_directory($directory);

	}// Method


	/**
		[ORIGINAL]
			remove_duplicate_values()
		@since 1.5.0
		@access (private)
			Removes duplicate values from the given source array.
		@param (array) $source
			The given array to iterate and remove duplicate values.
		@return (array)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function remove_duplicate_value(array $source)
	{
		foreach($source as $key => $value){
			if(empty($value) || !is_array($value)){continue;}
			// Remove duplicate values from the given array and re-indexes to start at element 0.
			$source[$key] = _beans_utility::__array_unique($value);
		}
		return $source;

	}// Method


	/**
		[ORIGINAL]
			init_component_dependencies()
		@since 1.5.0
		@access (private)
			Initialize the components' dependencies,by loading from its configuration file when NULL.
		@return (void)
	*/
	private function init_component_dependency()
	{
		if(!is_null($this->configured_component_dependency)){return;}
		$this->configured_component_dependency = require dirname(__FILE__) . '/config/uikit.php';

	}// Method


	/* Method
	_________________________
	*/
	public function convert_to_filename($file)
	{
		/**
			[ORIGINAL]
				to_filename()
			@access (public)
				Convert component to a filename.
			@param (string) $file
				File name.
			@return (NULL)|(string)
				Return the filename without the .min to a(void) duplicates.
		*/
		$pathinfo = pathinfo($file);

		// If the given file is not valid,bail out.
		if(!isset($pathinfo['filename'])){
			return NULL;
		}

		// Stop here if it isn't a valid file or if it should be ignored.
		if($this->ignore_component($pathinfo['filename'])){
			return NULL;
		}
		return str_replace('.min','',$pathinfo['filename']);

	}// Method


	/**
		[ORIGINAL]
			ignore_component()
		@since 1.5.0
		@access (private)
			Checks if the given component's filename should be ignored.
		@param (string) $filename
			The filename to check against the ignored components.
		@return (bool)
	*/
	private function ignore_component($filename)
	{
		return in_array($filename,$this->ignored_component,TRUE);

	}// Method


}// Class
endif;
