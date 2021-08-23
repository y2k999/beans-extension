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
if(class_exists('_beans_optimize_compiler') === FALSE) :
final class _beans_optimize_compiler
{
/**
 * @since 1.0.1
 * 	Page assets compiler.
 * 	This class compiles and minifies CSS,LESS and JS on pages.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_handle_not_to_compile()
 * 	init()
 * 	set_hook()
 * 	invoke_hook()
 * 	compile_page_styles()
 * 	do_not_compile_styles()
 * 	compile_page_scripts()
 * 	do_not_compile_scripts()
 * 	compile_enqueued()
 * 	did_handle()
 * 	maybe_add_media_query_to_src()
 * 	do_not_compile_asset()
 * 	get_deps_to_be_compiled()
 * 	dequeue_scripts()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $_class
			Name/Identifier with Prefix.
		@var (array) $hook
			The collection of hooks that is being registered (that is, actions or filters).
		@var (array) $dequeued_scripts
			Compiler dequeued scripts.
		@var (array) $processed_handles
			An array of the handles that have been processed.
		@var (array) $handle_not_to_compile
			An array of assets not to compile.
	 */
	private static $_class = '';
	private $hook = array();
	private $dequeued_script = array();
	private $processed_handle = array();
	private $handle_not_to_compile = array();

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
		$this->handle_not_to_compile = $this->set_handle_not_to_compile();

	}// Method


	/* Setter
	_________________________
	 */
	private function set_handle_not_to_compile()
	{
		/**
			@access (private)
			@return (array)
		*/
		$return = require dirname(__FILE__) . '/config/exclusive.php';
		return wp_parse_args($return,$this->handle_not_to_compile);

	}// Method


	/* Method
	_________________________
	*/
	public function init()
	{
		/**
			@since 1.5.0
			@access (public)
				Initialize the hooks.
			@return (void)
		*/

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
				Fires when scripts and styles are enqueued.
				https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
			@return (array)
				The collection of hooks that is being registered (that is, actions or filters).
			@reference
				[Plugin]/trait/hook.php
				[Plugin]/include/constant.php
		*/
		return $this->set_parameter_callback(array(
			'page_style' => array(
				'tag' => 'add_action',
				'hook' => 'wp_enqueue_scripts',
				'priority' => BEANS_EXTENSION_PRIORITY['max']
			),
			'page_script' => array(
				'tag' => 'add_action',
				'hook' => 'wp_enqueue_scripts',
				'priority' => BEANS_EXTENSION_PRIORITY['max']
			),
		));

	}// Method


	/* Hook
	_________________________
	*/
	public function page_style()
	{
		/**
			[ORIGINAL]
				compile_page_styles()
			@access (public)
				Enqueue the compiled WP styles.
			@return (void)
			@reference
				[Plugin]/api/compiler/beans.php
		*/
		if($this->is_page_style()){return;}

		$this->processed_handle = array();
		$styles = $this->fragment_enqueued_asset('style');
		if(empty($styles)){return;}

		/**
		 * @reference (Beans)
		 * 	Compile CSS fragments and enqueue compiled file.
		 * 	https://www.getbeans.io/code-reference/functions/beans_compile_css_fragments/
		*/
		_beans_compiler::__fragment_css(
			'beans-extension',
			$styles,
			array(
				'version' => NULL
			));

	}// Method


	/**
		[ORIGINAL]
			do_not_compile_styles()
		@since 1.5.0
		@access (private)
			Checks if the page's styles should not be compiled.
		@return (bool)
		@reference
			[Plugin]/include/component.php
	*/
	private function is_page_style()
	{
		return !_beans_component::__get_support('wp_style_beans_extension_compiler') || !get_option('beans_extension_compile_all_style',FALSE) || _beans_compiler::__is_dev_mode();

	}// Method


	/* Hook
	_________________________
	*/
	public function page_script()
	{
		/**
			[ORIGINAL]
				compile_page_scripts()
			@access (public)
				Enqueue the compiled WP scripts.
			@return (void)
			@reference
				[Plugin]/api/compiler/beans.php
				[Plugin]/include/constant.php
		*/
		if($this->is_page_script()){return;}

		$this->processed_handle = array();
		$scripts = $this->fragment_enqueued_asset('script');
		if(empty($scripts)){return;}

		$this->dequeued_script = $scripts;

		/**
		 * @reference (WP)
		 * 	Fires before scripts in the $handles queue are printed.
		 * 	https://developer.wordpress.org/reference/hooks/wp_print_scripts/
		*/
		add_action('wp_print_scripts',[$this,'dequeue_script'],BEANS_EXTENSION_PRIORITY['max']);

		_beans_compiler::__fragment_js(
			'beans-extension',
			$scripts,
			array(
				'in_footer' => 'aggressive' === get_option('beans_extension_compile_all_script_mode','aggressive'),
				'version' => NULL,
			));

	}// Method


	/**
		[ORIGINAL]
			do_not_compile_scripts()
		@since 1.5.0
		@access (private)
			Checks if the page's scripts should not be compiled.
		@return (bool)
		@reference
			[Plugin]/include/component.php
	*/
	private function is_page_script()
	{
		return !_beans_component::__get_support('wp_script_beans_extension_compiler') || !get_option('beans_extension_compile_all_script',FALSE) || _beans_compiler::__is_dev_mode();

	}// Method


	/**
		[ORIGINAL]
			compile_enqueued()
		@access (private)
			Compile all of the enqueued assets,i.e. all assets that are registered with WordPress.
		@param (string) $type
			Type of asset,e.g. style or script.
		@param (string)|(array) $dependencies
			[Optional]
			The asset's dependency(ies).
			[Default] an empty string.
		@return (array)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function fragment_enqueued_asset($type,$dependencies = '')
	{
		$assets = _beans_utility::__get_global_value("wp_{$type}s",$GLOBALS);
		if(!$assets){
			return array();
		}

		if(!$dependencies){
			$dependencies = $assets->queue;
		}
		$fragments = array();

		foreach($dependencies as $handle){
			if($this->is_exclusive($handle)){continue;}
			if($this->is_processed($handle)){continue;}

			$asset = _beans_utility::__get_global_value($handle,$assets->registered);
			if(!$asset){continue;}
			$this->get_dependency($type,$asset,$fragments);

			if(empty($asset->src)){continue;}
			if('style' === $type){
				$this->add_media_query($asset);
				$assets->done[] = $handle;
			}
			$fragments[$handle] = $asset->src;
		}
		return $fragments;

	}// Method


	/**
		[ORIGINAL]
			did_handle()
		@since 1.5.0
		@access (private)
			Checks if the handle has already been processed.
			If no,it stores the handle.
			[NOTE]
			This check eliminates processing dependencies that are in more than one asset.
			For example,if more than one script requires 'jquery',then this check ensures we only process jquery's dependencies once.
		@param (string) $handle
			The asset's handle.
		@return (bool)
	*/
	private function is_processed($handle)
	{
		if(in_array($handle,$this->processed_handle,TRUE)){
			return TRUE;
		}
		$this->processed_handle[] = $handle;

		return FALSE;

	}// Method


	/**
		[ORIGINAL]
			maybe_add_media_query_to_src()
		@since 1.5.0
		@access (private)
		@param (_WP_Dependency) $asset
			The given asset.
			https://developer.wordpress.org/reference/classes/_wp_dependency/
		@return (void)
	*/
	private function add_media_query($asset)
	{
		// Add compiler media query if set.
		if('all' === $asset->args){return;}

		/**
		 * @reference (WP)
		 * 	When the args are not set to "all," adds the media query to the asset's src.
		 * 	Retrieves a modified URL query string.
		 * 	https://developer.wordpress.org/reference/functions/add_query_arg/
		*/
		$asset->src = add_query_arg(array(
			'beans_extension_compiler_media_query' => $asset->args
		),$asset->src);

	}// Method


	/**
		[ORIGINAL]
			do_not_compile_asset()
		@since 1.5.0
		@access (private)
			Checks the given asset's handle to determine if it should not be compiled.
		@param (string) $handle
			The asset handle to check.
		@return (bool)
	*/
	private function is_exclusive($handle)
	{
		return in_array($handle,$this->handle_not_to_compile,TRUE);

	}// Method


	/**
		[ORIGINAL]
			get_deps_to_be_compiled()
		@since 1.5.0
		@access (private)
			Get the asset's dependencies to be compiled.
		@param (string) $type
			Type of asset.
		@param (_WP_Dependency) $asset
			Instance of the asset.
		@param (array) $srcs
			Array of compiled asset srcs to be compiled.
			Passed by reference.
		@return (void)
	*/
	private function get_dependency($type,$asset,array &$srcs)
	{
		if(empty($asset->deps)){return;}

		foreach($this->fragment_enqueued_asset($type,$asset->deps,TRUE) as $dep_handle => $dep_src){
			if(empty($dep_src)){continue;}
			$srcs[$dep_handle] = $dep_src;
		}

	}// Method


	/* Method
	_________________________
	*/
	public function dequeue_script()
	{
		/**
			[ORIGINAL]
				dequeue_scripts()
			@access (public)
				Dequeue scripts which have been compiled,grab localized data and add it inline.
				View file for the inline localization content.
			@global (wp_scripts) $wp_scripts
				Core class used to register scripts.
				https://developer.wordpress.org/reference/classes/wp_scripts/
			@return (void)
			@reference
				[Plugin]/utility/beans.php
		*/
		if(empty($this->dequeued_script)){return;}

		global $wp_scripts;

		$localized = '';

		// Fetch the localized content and dequeue script.
		foreach($this->dequeued_script as $handle => $src){
			$script = _beans_utility::__get_global_value($handle,$wp_scripts->registered);

			if(isset($script->extra['data'])){
				$localized .= $script->extra['data'] . "\n";
			}
			$wp_scripts->done[] = $handle;
		}

		if(empty($localized)){return;}

		// Add the inline localized content since it was removed with dequeue scripts.
		require dirname(__FILE__) . '/view/content-localized.php';

	}// Method


}// Class
endif;
