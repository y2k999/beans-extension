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
if(class_exists('_beans_compiler') === FALSE) :
class _beans_compiler
{
/**
 * @since 1.0.1
 * 	LESS content will automatically be converted to CSS.
 * 	Internal file changes are automatically detected if development mode is enabled.
 * 	Third party enqueued styles and scripts can be compiled and cached into a single file.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_hook()
 * 	invoke_hook()
 * 	beans_compile_css_fragments()
 * 	beans_compile_less_fragments()
 * 	beans_compile_js_fragments()
 * 	_beans_compile_fragments()
 * 	beans_compiler_add_fragment()
 * 	beans_flush_compiler()
 * 	beans_flush_admin_compiler()
 * 	beans_get_compiler_dir()
 * 	beans_get_compiler_url()
 * 	_beans_is_compiler_dev_mode()
 * 	beans_add_compiler_options_to_settings()
 * 	beans_add_page_assets_compiler()
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
			@global (array) $_beans_extension_compiler_added_fragment
				Added fragments global.
			@return (void)
			@reference
				[Plugin]/trait/singleton.php
		*/

		// Init properties.
		self::$_class = __utility_get_class(get_class($this));

		// Initialize added fragments global.
		global $_beans_extension_compiler_added_fragment;
		if(!isset($_beans_extension_compiler_added_fragment)){
			$_beans_extension_compiler_added_fragment = array(
				'css' => array(),
				'less' => array(),
				'js' => array(),
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
				[Plugin]/include/component.php
		*/
		return $this->set_parameter_callback(array(
			'register_compiler_option' => array(
				'tag' => 'add_action',
				'hook' => 'beans_extension_loaded_component_compiler'
			),
			'register_page_compiler' => array(
				'tag' => 'add_action',
				'hook' => 'beans_extension_loaded_component_compiler'
			),
		));

	}// Method


	/* Method
	_________________________
	*/
	public static function __fragment_css($id,$fragments,$args = array())
	{
		/**
			[ORIGINAL]
				beans_compile_css_fragments()
				https://www.getbeans.io/code-reference/functions/beans_compile_css_fragments/
			@access (public)
				Compile CSS fragments and enqueue compiled file.
				This function should be used in a similar fashion to wp_enqueue_script().
				https://developer.wordpress.org/reference/functions/wp_enqueue_script/
				Fragments can be added to the compiler using{@see beans_compiler_add_fragment()}.
			@param (string) $id
				A unique string used as a reference.
				Similar to the WordPress scripts $handle argument.
			@param (string)|(array) $fragments
				File(s) absolute path.
				Internal or external file(s) url accepted but may increase compiling time.
			@param (array) $args{
				Array of arguments used by the compiler.
				[Optional]
				@type (array) $dependencies
					An array of registered handles this script depends on.
					[Default] an empty array
			}
			@return (void)|(bool)
		*/
		if(empty($fragments)){
			return FALSE;
		}
		self::__get_fragment($id,'css',$fragments,$args);

	}// Method


	/* Method
	_________________________
	*/
	public static function __fragment_less($id,$fragments,$args = array())
	{
		/**
			[ORIGINAL]
				beans_compile_less_fragments()
				https://www.getbeans.io/code-reference/functions/beans_compile_less_fragments/
			@access (public)
				Compile LESS fragments,convert to CSS and enqueue compiled file.
				This function should be used in a similar fashion to wp_enqueue_script().
				https://developer.wordpress.org/reference/functions/wp_enqueue_script/
				Fragments can be added to the compiler using{@see beans_compiler_add_fragment()}.
			@param (string) $id
				The compiler ID.
				Similar to the WordPress scripts $handle argument.
			@param (string)|(array) $fragments
				File(s) absolute path.
				Internal or external file(s) url accepted but may increase compiling time.
			@param (array) $args{
				Array of arguments used by the compiler.
				[Optional]
				@type (array) $dependencies
					An array of registered handles this script depends on.
					[Default] an empty array
			}
			@return (void)|(bool)
		*/
		if(empty($fragments)){
			return FALSE;
		}
		self::__get_fragment($id,'less',$fragments,$args);

	}// Method


	/* Method
	_________________________
	*/
	public static function __fragment_js($id,$fragments,$args = array())
	{
		/**
			[ORIGINAL]
				beans_compile_js_fragments()
				https://www.getbeans.io/code-reference/functions/beans_compile_js_fragments/
			@access (public)
				Compile JS fragments and enqueue compiled file.
				This function should be used in a similar fashion to
				https://developer.wordpress.org/reference/functions/wp_enqueue_script/
				Fragments can be added to the compiler using{@see beans_compiler_add_fragment()}.
			@param (string) $id
				The compiler ID. Similar to the WordPress scripts $handle argument.
			@param (string)|(array) $fragments
				File(s) absolute path.
				Internal or external file(s) URL accepted but may increase compiling time.
			@param (array) $args{
				Array of arguments used by the compiler.
				[Optional]
				@type (array) $dependencies
					An array of registered handles this script depends on.
					[Default] FALSE
				@type (bool) $in_footer
					Whether to enqueue the script before </head> or before </body>.
					[Default] FALSE
				@type (bool) $minify_js
					Whether the JavaScript should be minified or not.
					Be aware that minifying the JavaScript can considerably slow down the process of compiling files.
					[Default] FALSE
			}
			@return (void)|(bool)
		*/
		if(empty($fragments)){
			return FALSE;
		}
		self::__get_fragment($id,'js',$fragments,$args,TRUE);

	}// Method


	/**
		[ORIGINAL]
			_beans_compile_fragments()
		@since 1.5.0
		@access (public)
			Compile the given fragments.
		@param(string) $id
			The ID.
		@param (string) $format
			The format type.
		@param (string)|(array) $fragments
			File(s) absolute path.
			Internal or external file(s) URL accepted but may increase compiling time.
		@param (array) $args
			[Optional]
			An array of arguments.
		@param (bool) $is_script
			[Optional]
			When TRUE,the fragment(s) is(are) script(s).
		@return (void)
		@reference
			[Plugin]/api/compiler/runtime.php
	*/
	public static function __get_fragment($id,$format,$fragments,array $args = array(),$is_script = FALSE)
	{
		$config = array(
			'id' => $id,
			'type' => $is_script ? 'script' : 'style',
			'format' => $format,
			'fragment' => (array)$fragments,
		);
		$compiler = new _beans_runtime_compiler($config + $args);
		$compiler->run();

	}// Method


	/* Method
	_________________________
	*/
	public static function __add_fragment($id,$fragments,$format)
	{
		/**
			[ORIGINAL]
				beans_compiler_add_fragment()
				https://www.getbeans.io/code-reference/functions/beans_compiler_add_fragment/
			@access (public)
				Add CSS,LESS or JS fragments to a compiler.
				https://developer.wordpress.org/reference/functions/wp_enqueue_script/
				This function should be used in a similar fashion to wp_enqueue_script().
			@global (array) $_beans_extension_compiler_added_fragment
				Added fragments global.
			@param (string) $id
				The compiler ID.
				Similar to the WordPress scripts $handle argument.
			@param (string)|(array) $fragments
				File(s) absolute path.
				Internal or external file(s) url accepted but may increase compiling time.
			@param (string) $format
				Compiler format the fragments should be added to.
				Accepts 
				 - 'css'
				 - 'less'
				 - 'js'
			@return (void)|(bool)
		*/
		if(empty($fragments)){
			return FALSE;
		}

		// Custom global variable.
		global $_beans_extension_compiler_added_fragment;

		foreach((array)$fragments as $key => $fragment){
			// Stop here if the format isn't valid.
			if(!isset($_beans_extension_compiler_added_fragment[$format])){continue;}

			// Register a new compiler ID if it doesn't exist and add fragment.
			if(!isset($_beans_extension_compiler_added_fragment[$format][$id])){
				$_beans_extension_compiler_added_fragment[$format][$id] = array($fragment);
			}
			else{
				// Add fragment to existing compiler.
				$_beans_extension_compiler_added_fragment[$format][$id][] = $fragment;
			}
		}

	}// Method


	/* Method
	_________________________
	*/
	public static function __flush_cache($id,$file_format = FALSE,$admin = FALSE)
	{
		/**
			[ORIGINAL]
				beans_flush_compiler()
				https://www.getbeans.io/code-reference/functions/beans_flush_compiler/
			@access (public)
				Flush cached compiler files.
				Each compiler has its own folder which contains the cached CSS and JS files.
				The file format of the cached file can be specified if needed.
			@param (string) $id
				The compiler ID.
				Similar to the WordPress scripts $handle argument.
			@param (string)|(bool)$file_format
				[Optional]
				Define which file format(s) should be removed.
				Both CSS and JS files will be removed if set to FALSE.
				Accepts 
				 - 'FALSE'
				 - 'css'
				 - 'js'
			@param (bool) $admin
				[Optional]
				Whether it is an admin compiler or not.
			@return (void)|(bool)
			@reference
				[Plugin]/utility/beans.php
		*/
		static $beans_flushed = FALSE;

		$cache_dir = self::__get_directory($admin);

		// Always flush Beans' global cache.
		if(!$beans_flushed){
			$beans_flushed = TRUE;
			self::__flush_cache('beans-extension',$file_format,$admin);
		}

		// Appends a trailing slash.
		$dir = trailingslashit($cache_dir) . $id;

		// Stop here if directory doesn't exist.
		if(!is_dir($dir)){return;}

		// Remove all file formats.
		if(!$file_format){
			_beans_utility::__remove_directory($dir);
			return;
		}

		// Remove only the specified file format.
		foreach(_beans_utility::__scan_directory($dir) as $item){
			if(_beans_utility::__str_end_with($item,".{$file_format}")){
				/* phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case. */
				@unlink(trailingslashit($dir) . $item);
			}
		}

	}// Method


	/* Method
	_________________________
	*/
	public static function __flush_admin_cache($id,$file_format = FALSE)
	{
		/**
			[ORIGINAL]
				beans_flush_admin_compiler()
				https://www.getbeans.io/code-reference/functions/beans_flush_admin_compiler/
			@access (public)
				Flush admin cached compiler files.
				This function is a shortcut of{@see beans_flush_compiler()}.
			@param (string) $id
				The compiler ID.
				Similar to the WordPress scripts $handle argument.
			@param (string)|(bool) $file_format
				[Optional]
				Define which file formats should be removed.
				Both CSS and JS files will be removed if set to FALSE.
				Accepts 
				 - 'FALSE'
				 - 'css'
				 - 'js'
			@return (void)
		*/
		self::__flush_cache($id,$file_format,TRUE);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_directory($is_admin = FALSE)
	{
		/**
			[ORIGINAL]
				beans_get_compiler_dir()
				https://www.getbeans.io/code-reference/functions/beans_get_compiler_dir/
			@access (public)
				Get absolute path to the Beans' compiler directory.
			@param (bool) $is_admin
				[Optional]
				When TRUE,gets the admin compiler directory.
				[Default] FALSE.
			@return(string)
		*/

		/**
		 * @reference (WP)
		 * 	Returns an array containing the current upload directory’s path and URL.
		 * 	https://developer.wordpress.org/reference/functions/wp_upload_dir/
		*/
		$wp_upload_dir = wp_upload_dir();
		$suffix = $is_admin ? 'beans-extension/admin-compiler/' : 'beans-extension/compiler/';

		/**
		 * @deprecated1.3.0
		 * 	Deprecated. Filter the Beans compiler directory.
		 * 	This filter is deprecated for security and compatibility purposes.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_compiler_dir/
		*/
		apply_filters('beans_extension_compiler_directory',FALSE,$is_admin);

		/**
		 * @reference (WP)
		 * 	Normalize a filesystem path.
		 * 	https://developer.wordpress.org/reference/functions/wp_normalize_path/
		*/
		return wp_normalize_path(trailingslashit($wp_upload_dir['basedir']) . $suffix);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_url($is_admin = FALSE)
	{
		/**
			[ORIGINAL]
				beans_get_compiler_url()
				https://www.getbeans.io/code-reference/functions/beans_get_compiler_url/
			@since 1.3.0
			@access (public)
				Get absolute URL to the Beans' compiler directory.
			@param (bool) $is_admin
				[Optional]
				When TRUE,gets the admin compiler directory.
				[Default] FALSE
			@return (string)
		*/

		/**
		 * @reference (WP)
		 * 	Returns an array containing the current upload directory’s path and URL.
		 * 	https://developer.wordpress.org/reference/functions/wp_upload_dir/
		*/
		$wp_upload_dir = wp_upload_dir();
		$suffix = $is_admin ? 'beans-extension/admin-compiler/' : 'beans-extension/compiler/';

		return trailingslashit($wp_upload_dir['baseurl']) . $suffix;

	}// Method


	/**
		[ORIGINAL]
			_beans_is_compiler_dev_mode()
		@access (public)
			Check if development mode is enabled.
			Takes legacy constant into consideration.
		@return (bool)
		@reference
			[Plugin]/include/constant.php
	*/
	public static function __is_dev_mode()
	{
		if(defined('BEANS_EXTENSION_COMPILER_DEV_MODE')){
			return BEANS_EXTENSION_COMPILER_DEV_MODE;
		}
		return (bool)get_option('beans_extension_dev_mode',FALSE);

	}// Method


	/* Hook
	_________________________
	*/
	public function register_compiler_option()
	{
		/**
			[ORIGINAL]
				beans_add_compiler_options_to_settings()
			@since 1.5.0
			@access (public)
				Add the "compiler options" to the Beans Settings page.
			@return (_Beans_Compiler_Options)|(void)
			@reference
				[Plugin]/action/compiler/control.php
		*/

		// Load the class only if this function is called to prevent unnecessary memory usage.
		$instance = _beans_control_compiler::__get_instance();
		// $instance = new _beans_control_compiler();
		$instance->init();

		return $instance;

	}// Method


	/* Hook
	_________________________
	*/
	public function register_page_compiler()
	{
		/**
			[ORIGINAL]
				beans_add_page_assets_compiler()
			@since 1.5.0
			@access (public)
				Add the page assets' compiler.
			@return (_Beans_Page_Compiler)|(void)
			@reference
				[Plugin]/action/compiler/optimize.php
		*/

		// Load the class only if this function is called to prevent unnecessary memory usage.
		$instance = _beans_optimize_compiler::__get_instance();
		// $instance = new _beans_optimize_compiler();
		$instance->init();

		return $instance;

	}// Method


}// Class
endif;
_beans_compiler::__get_instance();
