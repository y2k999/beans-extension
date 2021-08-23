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
if(class_exists('_beans_uikit') === FALSE) :
class _beans_uikit
{
/**
 * @since 1.0.1
 * 	The Beans UIkit component integrates the awesome.
 * 	{@link https://getuikit.com/v2/ UIkit 2 framework}
 * 	Only the selected components are compiled into a single cached file and can be different on a per page basis.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_hook()
 * 	invoke_hook()
 * 	beans_uikit_enqueue_components()
 * 	beans_uikit_dequeue_components()
 * 	beans_uikit_register_theme()
 * 	beans_uikit_enqueue_theme()
 * 	beans_uikit_dequeue_theme()
 * 	beans_uikit_get_all_components()
 * 	beans_uikit_get_all_dependencies()
 * 	_beans_uikit_autoload_dependencies()
 * 	_beans_uikit_get_registered_theme()
 * 	_beans_uikit_enqueue_assets()
 * 	_beans_uikit_enqueue_admin_assets()
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
			@global (array) $_beans_extension_uikit_registered_item
				Registerd UIkit items global.
			@global (array) $_beans_extension_uikit_enqueued_item
				Enqueued UIkit items global.
			@return (void)
			@reference
				[Plugin]/trait/singleton.php
				[Plugin]/include/constant.php
		*/

		// Init properties.
		self::$_class = __utility_get_class(get_class($this));

		// Initialize registered UIkit items global.
		global $_beans_extension_uikit_registered_item;
		if(!isset($_beans_extension_uikit_registered_item)){
			$_beans_extension_uikit_registered_item = array(
				'theme' => array(
					'default' => BEANS_EXTENSION_API_PATH['uikit'] . 'asset/theme/default',
					// 'flat' => BEANS_EXTENSION_API_PATH['uikit'] . 'asset/theme/flat',
					// 'gradient' => API['uikit'] . 'src/theme/gradient',
				),
			);
		}

		// Initialize enqueued UIkit items global.
		global $_beans_extension_uikit_enqueued_item;
		if(!isset($_beans_extension_uikit_enqueued_item)){
			$_beans_extension_uikit_enqueued_item = array(
				'component' => array(
					'core' => array(),
					'add-on' => array(),
				),
				'theme' => array(),
			);
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
			@return (array)
				The collection of hooks that is being registered (that is, actions or filters).
			@reference
				[Plugin]/trait/hook.php
		*/
		return $this->set_parameter_callback(array(
			'enqueue_admin_asset' => array(
				'tag' => 'add_action',
				'hook' => 'admin_enqueue_scripts',
				'priority' => 7
			),
			'enqueue_asset' => array(
				'tag' => 'add_action',
				'hook' => 'wp_enqueue_scripts',
				'priority' => 7
			),
		));

	}// Method


	/* Method
	_________________________
	*/
	public static function __enqueue_component($components,$type = 'core',$autoload = TRUE)
	{
		/**
			[ORIGINAL]
				beans_uikit_enqueue_components()
				https://www.getbeans.io/code-reference/functions/beans_uikit_enqueue_components/
			@access (public)
				Enqueue UIkit components.
				Enqueued components will be compiled into a single file.
				https://getuikit.com/v2/
				When development mode is enabled,file changes will automatically be detected.
				This makes it very easy to style UIkit themes using LESS.
				This function must be called in the 'beans_uikit_enqueue_scripts' action hook.
			@global (array) $_beans_extension_uikit_enqueued_item
				Enqueued UIkit items global.
			@param (string)|(array)|(bool) $components
				Name of the component(s) to include as an indexed array.
				The name(s) must be the UIkit component filename without the extension (e.g. 'grid').
				Set to TRUE to load all components.
			@param (string) $type...,
				[Optional]
				Type of UIkit components ('core' or 'add-ons').
			@param (bool) $autoload...,
				[Optional]
				Automatically include components dependencies.
			@return (void)
			@reference
				[Plugin]/utility/beans.php
		*/

		// Custom global variable.
		global $_beans_extension_uikit_enqueued_item;

		// Get all uikit components.
		if(TRUE === $components){
			$components = self::__get_all_component($type);
		}
		elseif($autoload){
			self::__autoload_dependency($components);
		}

		// Add components into the registry.
		$_beans_extension_uikit_enqueued_item['component'][$type] = _beans_utility::__join_array_clean(
			(array)$_beans_extension_uikit_enqueued_item['component'][$type],
			(array)$components
		);

	}// Method


	/* Method
	_________________________
	*/
	public static function __dequeue_component($components,$type = 'core')
	{
		/**
			[ORIGINAL]
				beans_uikit_dequeue_components()
				https://www.getbeans.io/code-reference/functions/beans_uikit_dequeue_theme/
			@access (public)
				Dequeue UIkit components.
				Dequeued components are removed from the UIkit compiler.
				https://getuikit.com/v2/
				When development mode is enabled,file changes will automatically be detected.
				This makes it very easy to style UIkit themes using LESS.
				This function must be called in the 'beans_uikit_enqueue_scripts' action hook.
			@global (array) $_beans_extension_uikit_enqueued_item
				Enqueued UIkit items global.
			@param (string)|(array) $components
				Name of the component(s) to exclude as an indexed array.
				The name(s) must be the UIkit component filename without the extention (e.g. 'grid').
				Set to TRUE to exclude all components.
			@param (string) $type
				[Optional]
				Type of UIkit components ('core' or 'add-ons').
			@return (void)
		*/

		// Custom global variable.
		global $_beans_extension_uikit_enqueued_item;

		// When TRUE,remove all of the components from the registry.
		if(TRUE === $components){
			$_beans_extension_uikit_enqueued_item['component'][$type] = array();
			return;
		}

		// Remove components.
		$_beans_extension_uikit_enqueued_item['component'][$type] = array_diff(
			(array)$_beans_extension_uikit_enqueued_item['component'][$type],
			(array)$components
		);

	}// Method


	/* Method
	_________________________
	*/
	public static function __register_theme($id,$path)
	{
		/**
			[ORIGINAL]
				beans_uikit_register_theme()
				https://www.getbeans.io/code-reference/functions/beans_uikit_register_theme/
			@access (public)
				Register a UIkit theme.
				The theme must not contain sub-folders.
				Component files in the theme will be automatically combined to the UIkit compiler if that component is used.
				This function must be called in the 'beans_uikit_enqueue_scripts' action hook.
			@global (array) $_beans_extension_uikit_registered_item
				Registerd UIkit items global.
			@param (string) $id
				A unique string used as a reference.
				Similar to the WordPress scripts $handle argument.
			@param (string) $path
				Absolute path to the UIkit theme folder.
			@return (bool)
				FALSE on error,TRUE on success.
			@reference
				[Plugin]/utility/beans.php
		*/

		// Stop here if already registered.
		global $_beans_extension_uikit_registered_item;
		if(_beans_utility::__get_global_value($id,$_beans_extension_uikit_registered_item['theme'])){
			return TRUE;
		}

		if(!$path){
			return FALSE;
		}

		// Check if the given string starts with the given substring(s).
		if(_beans_utility::__str_start_with($path,'http')){
			// Convert internal url to a path.
			$path = _beans_utility::__url_to_path($path);
		}

		$_beans_extension_uikit_registered_item['theme'][$id] = trailingslashit($path);
		return TRUE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __enqueue_theme($id,$path = FALSE)
	{
		/**
			[ORIGINAL]
				beans_uikit_enqueue_theme()
				https://www.getbeans.io/code-reference/functions/beans_uikit_enqueue_theme/
			@access (public)
				Enqueue a UIkit theme.
				The theme must not contain sub-folders.
				Component files in the theme will be automatically combined to the UIkit compiler if that component is used.
				This function must be called in the 'beans_uikit_enqueue_scripts' action hook.
			@global (array) $_beans_extension_uikit_enqueued_item
				Enqueued UIkit items global.
			@param (string) $id
				A unique string used as a reference.
				Similar to the WordPress scripts $handle argument.
			@param (string) $path...,
				[Optional]
				Path to the UIkit theme folder if the theme isn't yet registered.
			@return (bool)
				FALSE on error,TRUE on success.
		*/

		// Make sure it is registered,if not,try to do so.
		if(!self::__register_theme($id,$path)){
			return FALSE;
		}

		// Custom global variable.
		global $_beans_extension_uikit_enqueued_item;
		$_beans_extension_uikit_enqueued_item['theme'][$id] = self::__get_registered_theme($id);
		return TRUE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __dequeue_theme($id)
	{
		/**
			[ORIGINAL]
				beans_uikit_dequeue_theme()
				https://www.getbeans.io/code-reference/functions/beans_uikit_dequeue_theme/
			@access (public)
				Dequeue a UIkit theme.
				This function must be called in the 'beans_uikit_enqueue_scripts' action hook.
			@global (array) $_beans_extension_uikit_enqueued_item
				Enqueued UIkit items global.
			@param (string) $id
				The id of the theme to dequeue.
			@return (void)
		*/

		// Custom global variable.
		global $_beans_extension_uikit_enqueued_item;
		unset($_beans_extension_uikit_enqueued_item['theme'][$id]);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_all_component($type = 'core')
	{
		/**
			[ORIGINAL]
				beans_uikit_get_all_components()
			@since 1.5.0
			@access (public)
				Get all of the UIkit components for the given type,i.e. for core or add-ons.
			@param (string) $type...,
				[Optional]
				Type of UIkit components ('core' or 'add-ons').
			@return (array)
			@reference
				[Plugin]/api/uikit/runtime.php
		*/
		$uikit = _beans_runtime_uikit::__get_instance();
		// $uikit = new _beans_runtime_uikit();

		return $uikit->get_all_component($type);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_all_dependency($components)
	{
		/**
			[ORIGINAL]
				beans_uikit_get_all_dependencies()
			@since 1.5.0
			@access (public)
				Get all of the UIkit dependencies for the given component(s).
			@param (string)|(array) $components
				Name of the component(s) to process.
				The name(s) must be the UIkit component filename without the extension (e.g. 'grid').
			@return (array)
			@reference
				[Plugin]/api/uikit/runtime.php
		*/
		$uikit = _beans_runtime_uikit::__get_instance();
		// $uikit = new _beans_runtime_uikit();

		return $uikit->get_autoload_component((array)$components);

	}// Method


	/**
		[ORIGINAL]
			_beans_uikit_autoload_dependencies()
		@since 1.5.0
		@access (private)
			Autoload all the component dependencies.
		@param (string)|(array) $components
			Name of the component(s) to include as an indexed array.
			The name(s) must be the UIkit component filename without the extension (e.g. 'grid').
		@return (void)
	*/
	private static function __autoload_dependency($components)
	{
		foreach(self::__get_all_dependency($components) as $type => $autoload){
			self::__enqueue_component($autoload,$type,FALSE);
		}

	}// Method


	/**
		[ORIGINAL]
			_beans_uikit_get_registered_theme()
		@access (private)
			Get the path for the given theme ID,if the theme is registered.
		@global (array) $_beans_extension_uikit_registered_item
			Registerd UIkit items global.
		@param (string) $id
			The theme ID to get.
		@return (string)|(bool)
			Returns FALSE if the theme is not registered.
		@reference
			[Plugin]/utility/beans.php
	*/
	private static function __get_registered_theme($id)
	{
		// Custom global variable.
		global $_beans_extension_uikit_registered_item;
		return _beans_utility::__get_global_value($id,$_beans_extension_uikit_registered_item['theme'],FALSE);

	}// Method


	/* Hook
	_________________________
	*/
	public function enqueue_asset()
	{
		/**
			[ORIGINAL]
				_beans_uikit_enqueue_assets()
			@access (public)
				Enqueue UIkit assets.
				Fires when scripts and styles are enqueued.
				https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
			@return (void)
			@reference
				[Plugin]/api/uikit/runtime.php
		*/
		if(!has_action('beans_extension_uikit_enqueue_script')){return;}

		/**
		 * @reference (Beans)
		 * 	Fires when UIkit scripts and styles are enqueued.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_uikit_enqueue_scripts/
		*/
		do_action('beans_extension_uikit_enqueue_script');

		// Compile everything.
		$uikit = _beans_runtime_uikit::__get_instance();
		// $uikit = new _beans_runtime_uikit();
		$uikit->compile();

	}// Method


	/* Hook
	_________________________
	*/
	public function enqueue_admin_asset()
	{
		/**
			[ORIGINAL]
				_beans_uikit_enqueue_admin_assets()
			@access (public)
				Enqueue UIkit admin assets.
				Enqueue scripts for all admin pages.
				https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
			@return (void)
			@reference
				[Plugin]/api/uikit/runtime.php
		*/
		if(!has_action('beans_extension_uikit_admin_enqueue_script')){return;}

		/**
		 * @reference (Beans)
		 * 	Fires when admin UIkit scripts and styles are enqueued.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_uikit_admin_enqueue_scripts/
		*/
		do_action('beans_extension_uikit_admin_enqueue_script');

		// Compile everything.
		$uikit = _beans_runtime_uikit::__get_instance();
		// $uikit = new _beans_runtime_uikit();
		$uikit->compile();

	}// Method


}// Class
endif;
// new _beans_uikit();
_beans_uikit::__get_instance();
