<?php
/**
 * Fired during plugin activation.
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
if(class_exists('_beans_component') === FALSE) :
class _beans_component
{
/**
 * @since 1.0.1
 * 	Prepare and initialize the Beans framework.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_api()
 * 	set_common()
 * 	set_admin()
 * 	set_dependency()
 * 	loadup()
 * 	get_api_file()
 * 	register_support()
 * 	__get_support()
 * 	__remove_support()
 * 	__get_setting()
*/

	/**
		@access (private)
			Class properties.
		@var (array) $api
			Beans API.
		@var (array) $common
			General component paths.
		@var (array) $admin
			Admin component paths.
		@var (array) $dependency
		@var (array) $loaded
			Casched components.
	*/
	private $api = array();
	private $common = array();
	private $admin = array();
	private $dependency = array();
	private $loaded = array();

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
			@global (array) $_beans_extension_component_support
				API components support global.
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@return (void)
			@reference
				[Plugin]/beans_extension.php
				[Plugin]/trait/singleton.php
		*/

		// Initialize API component support globals.
		global $_beans_extension_component_support;
		if(!isset($_beans_extension_component_support)){
			$_beans_extension_component_support = array();
		}

		// Initialize API component setting globals.
		global $_beans_extension_component_setting;
		$_beans_extension_component_setting = $this->set_setting();

		// Init properties.
		$this->api = $this->set_api();
		$this->common = $this->set_common();
		$this->admin = $this->set_admin();
		$this->dependency = $this->set_dependency();

		// Register hooks.
		add_action('beans_extension_init',[$this,'loadup'],-1);

	}// Method


	/* Setter
	_________________________
	*/
	private function set_setting()
	{
		/**
			@access (private)
				Update API settings global.
			@return (array)
			@reference
				[Plugin]/include/constant.php
				[Plugin]/admin/tab/general.php
		*/

		//
		// General Tab
		//
		$option_general = get_option(BEANS_EXTENSION_PREFIX['option'] . 'general');

		/**
		 * @since 1.0.1
		 * 	Uikit (CSS framework) version.
		 * @reference (Uikit)
		 * 	https://getuikit.com/v2/
		 * 	https://getuikit.com/
		*/
		$uikit = BEANS_EXTENSION_PREFIX['setting'] . 'general_uikit_version';
		$return['general']['uikit'] = isset($option_general[$uikit]) && ($option_general[$uikit] === 'uikit2') ? 'uikit2' : 'uikit3';

		/**
		 * @since 1.0.1
		 * 	Stop some Beans API components.
		 * @reference (Beans)
		 * 	https://www.getbeans.io/documentation/api/
		*/
		$image = BEANS_EXTENSION_PREFIX['setting'] . 'general_stop_beans_image';
		$return['general']['stop_image'] = isset($option_general[$image]) && $option_general[$image] ? TRUE : FALSE;

		$widget = BEANS_EXTENSION_PREFIX['setting'] . 'general_stop_beans_widget';
		$return['general']['stop_widget'] = isset($option_general[$widget]) && $option_general[$widget] ? TRUE : FALSE;

		$customizer = BEANS_EXTENSION_PREFIX['setting'] . 'general_stop_beans_customizer';
		$return['general']['stop_customizer'] = isset($option_general[$customizer]) && $option_general[$customizer] ? TRUE : FALSE;

		/**
		 * @since 1.0.1
		 * 	Use Beans legacy (relevant to Uikit2).
		 * @reference (Beans)
		 * 	https://www.getbeans.io/documentation/layout/
		 * @reference
		 * 	[Plugin]/asset/accessibility.php
		*/
		$layout = BEANS_EXTENSION_PREFIX['setting'] . 'general_beans_legacy_layout';
		$return['general']['legacy_layout'] = isset($option_general[$layout]) && $option_general[$layout] ? TRUE : FALSE;

		$accessibility = BEANS_EXTENSION_PREFIX['setting'] . 'general_beans_accessibility';
		$return['general']['accessibility'] = isset($option_general[$accessibility]) && $option_general[$accessibility] ? TRUE : FALSE;

		/**
		 * @since 1.0.1
		 * 	Beans template & fragment files path.
		 * @reference (Beans)
		 * 	https://www.getbeans.io/code-reference/structure/
		 * 	https://www.getbeans.io/code-reference/fragments/
		*/
		$template = BEANS_EXTENSION_PREFIX['setting'] . 'general_theme_template_path';
		$return['general']['template'] = isset($option_general[$template]) ? $option_general[$template] : 'lib/templates/';

		$structure = BEANS_EXTENSION_PREFIX['setting'] . 'general_theme_structure_path';
		$return['general']['structure'] = isset($option_general[$structure]) ? $option_general[$structure] : 'lib/templates/structure/';

		$fragment = BEANS_EXTENSION_PREFIX['setting'] . 'general_theme_fragment_path';
		$return['general']['fragment'] = isset($option_general[$fragment]) ? $option_general[$fragment] : 'lib/templates/fragments/';

		//
		// Image Tab
		//
		$option_image = get_option(BEANS_EXTENSION_PREFIX['option'] . 'image');

		/**
		 * @since 1.0.1
		 * 	Uploaded images.
		*/
		$profile = BEANS_EXTENSION_PREFIX['setting'] . 'image_profile';
		$return['image']['profile'] = isset($option_image[$profile]) ? $option_image[$profile] : '';

		$nopost = BEANS_EXTENSION_PREFIX['setting'] . 'image_nopost';
		$return['image']['nopost'] = isset($option_image[$nopost]) ? $option_image[$nopost] : '';


		//
		// Layout Tab
		//
		$option_layout = get_option(BEANS_EXTENSION_PREFIX['option'] . 'layout');

		/**
		 * @since 1.0.1
		 * 	Display layout options on meta pages.
		*/
		$post_meta = BEANS_EXTENSION_PREFIX['setting'] . 'layout_post_meta';
		$return['layout']['post_meta'] = isset($option_layout[$post_meta]) && $option_layout[$post_meta] ? TRUE : FALSE;

		$term_meta = BEANS_EXTENSION_PREFIX['setting'] . 'layout_term_meta';
		$return['layout']['term_meta'] = isset($option_layout[$term_meta]) && $option_layout[$term_meta] ? TRUE : FALSE;

		/**
		 * @since 1.0.1
		 * 	Single posts/pages layout.
		*/
		$single = BEANS_EXTENSION_PREFIX['setting'] . 'layout_single';
		$return['layout']['single'] = isset($option_layout[$single]) && ($option_layout[$single] === 'c') ? 'c' : 'c_sp';

		$page = BEANS_EXTENSION_PREFIX['setting'] . 'layout_page';
		$return['layout']['page'] = isset($option_layout[$page]) && ($option_layout[$page] === 'c') ? 'c' : 'c_sp';

		/**
		 * @since 1.0.1
		 * 	Archive list layout.
		*/
		$home = BEANS_EXTENSION_PREFIX['setting'] . 'layout_home';
		$return['layout']['home'] = isset($option_layout[$home]) && ($option_layout[$home] === 'card') ? 'card' : 'list';

		$archive = BEANS_EXTENSION_PREFIX['setting'] . 'layout_archive';
		$return['layout']['archive'] = isset($option_layout[$archive]) && ($option_layout[$archive] === 'list') ? 'list' : 'card';

		return $return;

	}// Method


	/* Setter
	_________________________
	*/
	private function set_api()
	{
		/**
			@access (private)
				Framework componets list.
				https://www.getbeans.io/documentation/api/
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@return (array)
			@reference
				[Plugin]/admin/tab/api.php
				[Plugin]/api/xxx/beans.php
		*/

		// The list of Beans default API components.
		$return = array(
			'action',
			'filter',
			'html',
			'term-meta',
			'post-meta',
			'image',
			'customizer',
			'compiler',
			'uikit',
			'template',
			'layout',
			'widget',
		);

		// Custom global variable.
		global $_beans_extension_component_setting;

		// Check if Beans image component is inactive.
		if(isset($_beans_extension_component_setting['general']['stop_image']) && ($_beans_extension_component_setting['general']['stop_image'])){
			unset($return['image']);
		}

		// Check if Beans widget component is inactive.
		if(isset($_beans_extension_component_setting['general']['stop_widget']) && ($_beans_extension_component_setting['general']['stop_widget'])){
			unset($return['widget']);
		}

		// Check if Beans widget component is inactive.
		if(isset($_beans_extension_component_setting['general']['stop_customizer']) && ($_beans_extension_component_setting['general']['stop_customizer'])){
			unset($return['customizer']);
		}

		return $return;

	}// Method


	/* Setter
	_________________________
	*/
	private function set_common()
	{
		/**
			@access (private)
				Set Beans API components paths.
				https://www.getbeans.io/documentation/api/
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@return (array)
			@reference
				[Plugin]/api/xxx/beans.php
				[Plugin]/admin/tab/general.php
				[Plugin]/include/constant.php
		*/
		$return = array();

		$return['html'] = array(
			BEANS_EXTENSION_API_PATH['html'] . 'beans.php',
			BEANS_EXTENSION_API_PATH['html'] . 'attribute.php',
		);
		$return['action'] = array(
			'action' => BEANS_EXTENSION_API_PATH['action'] . 'beans.php',
		);
		$return['filter'] = array(
			'filter' => BEANS_EXTENSION_API_PATH['filter'] . 'beans.php',
		);
		$return['customizer'] = array(
			'customizer' => BEANS_EXTENSION_API_PATH['customizer'] . 'beans.php',
		);
		$return['field'] = array(
			'field' => BEANS_EXTENSION_API_PATH['field'] . 'beans.php',
		);
		$return['image'] = array(
			'image' => BEANS_EXTENSION_API_PATH['image'] . 'beans.php',
		);
		$return['compiler'] = array(
			BEANS_EXTENSION_API_PATH['compiler'] . 'beans.php',
			BEANS_EXTENSION_API_PATH['compiler'] . 'runtime.php',
			BEANS_EXTENSION_API_PATH['compiler'] . 'optimize.php',
		);
		$return['uikit'] = array(
			BEANS_EXTENSION_API_PATH['uikit'] . 'beans.php',
			BEANS_EXTENSION_API_PATH['uikit'] . 'runtime.php',
		);
		$return['layout'] = array(
			BEANS_EXTENSION_API_PATH['layout'] . 'beans.php',
		);
		$return['template'] = array(
			BEANS_EXTENSION_API_PATH['template'] . 'beans.php',
		);
		$return['widget'] = array(
			BEANS_EXTENSION_API_PATH['widget'] . 'beans.php',
		);

		// Custom global variable.
		global $_beans_extension_component_setting;

		// Check if Beans image component is inactive.
		if(isset($_beans_extension_component_setting['general']['stop_image']) && ($_beans_extension_component_setting['general']['stop_image'])){
			unset($return['image']);
		}

		// Check if Beans widget component is inactive.
		if(isset($_beans_extension_component_setting['general']['stop_widget']) && ($_beans_extension_component_setting['general']['stop_widget'])){
			unset($return['widget']);
		}

		// Check if Beans customizer component is inactive.
		if(isset($_beans_extension_component_setting['general']['stop_customizer']) && ($_beans_extension_component_setting['general']['stop_customizer'])){
			unset($return['customizer']);
		}

		// Check if Beans accessibility component is inactive.
		if(isset($_beans_extension_component_setting['general']['accessibility']) && ($_beans_extension_component_setting['general']['accessibility'])){
			$return['asset'][] = BEANS_EXTENSION_API_PATH['asset'] . 'accessibility.php';
		}

		return $return;

	}// Method


	/* Setter
	_________________________
	*/
	private function set_admin()
	{
		/**
			@access (private)
				Set Beans API admin components paths.
				Only load admin fragments if is_admin() is true.
				https://www.getbeans.io/documentation/api/
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@return (array)
			@reference
				[Plugin]/api/xxx/beans.php
				[Plugin]/admin/tab/general.php
				[Plugin]/include/constant.php
		*/

		/**
		 * @since 1.0.1
		 * 	Admin components and functions are automatically wrapped in an is_admin() check.
		 * @reference (WP)
		 * 	Determines whether the current request is for an administrative interface page.
		 * 	https://developer.wordpress.org/reference/functions/is_admin/
		*/
		if(!is_admin()){return array();}

		$return = array(
			'option' => BEANS_EXTENSION_API_PATH['option'] . 'beans.php',
			'post-meta' => BEANS_EXTENSION_API_PATH['post-meta'] . 'beans.php',
			'term-meta' => BEANS_EXTENSION_API_PATH['term-meta'] . 'beans.php',
			'compiler' => BEANS_EXTENSION_API_PATH['compiler'] . 'control.php',
			'image' => BEANS_EXTENSION_API_PATH['image'] . 'control.php',
			'admin' => BEANS_EXTENSION_API_PATH['admin'] . 'beans.php',
		);

		// Custom global variable.
		global $_beans_extension_component_setting;

		// Check if Beans image component is inactive.
		if(isset($_beans_extension_component_setting['general']['stop_image']) && ($_beans_extension_component_setting['general']['stop_image'])){
			unset($return['image']);
		}
		return $return;

	}// Method


	/* Setter
	_________________________
	*/
	private function set_dependency()
	{
		/**
			@access (private)
				Load the required dependencies for this plugin.
				https://www.getbeans.io/documentation/api/
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@return (array)
			@reference
				[Plugin]/api/xxx/
				[Plugin]/admin/tab/general.php
		*/
		$return['html'] = array(
			'admin',
			'filter',
		);
		$return['field'] = array(
			'action',
			'html',
		);
		$return['option'] = array(
			'field',
		);
		$return['post-meta'] = array(
			'field',
		);
		$return['term-meta'] = array(
			'field',
		);
		$return['customizer'] = array(
			'field',
		);
		$return['layout'] = array(
			'field',
		);
		$return['image'] = array(
			'admin',
		);
		$return['compiler'] = array(
			'admin',
		);
		$return['uikit'] = array(
			'compiler',
		);
		$return['admin'] = array(
			'option',
		);

		// Custom global variable.
		global $_beans_extension_component_setting;

		// Check if Beans image component is inactive.
		if(isset($_beans_extension_component_setting['general']['stop_image']) && ($_beans_extension_component_setting['general']['stop_image'])){
			unset($return['image']);
		}

		// Check if Beans image component is inactive.
		if(isset($_beans_extension_component_setting['general']['stop_customizer']) && ($_beans_extension_component_setting['general']['stop_customizer'])){
			unset($return['customizer']);
		}

		return $return;

	}// Method


	/* Hook
	_________________________
	*/
	public function loadup()
	{
		/**
			@access (public)
				Include framework files.
			@return (void)
		*/

		// Mode.
		if(!defined('SCRIPT_DEBUG')){
			/* @phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound */
			define('SCRIPT_DEBUG',FALSE);
		}

		// Assets.
		define('BEANS_EXTENSION_MIN_CSS',SCRIPT_DEBUG ? '' : '.min');
		define('BEANS_EXTENSION_MIN_JS',SCRIPT_DEBUG ? '' : '.min');

		// Load dependencies here,as these are used further down.
		$this->get_api_file($this->api);

		// Add third party styles and scripts compiler support.
		$this->register_support('wp_style_beans_extension_compiler');
		$this->register_support('wp_script_beans_extension_compiler');

		/**
		 * @reference (Beans)
		 * 	Fires after Beans API loads.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_after_load_api/
		*/
		do_action('beans_extension_after_load_component');

	}// Method


	/**
		@access (private)
			Load Beans API components.
			This function loads Beans API components.
			Components are only loaded once,even if they are called many times.
			Admin components and functions are automatically wrapped in an is_admin() check.
		@param (string)|(array) $components
			Name of the API component(s) to include as and indexed array.
			The name(s) must be the Beans API component folder.
		@return (bool)
			Will always return TRUE.
	*/
	private function get_api_file($component)
	{
		foreach((array)$component as $item){

			// Stop here if the component is already loaded or doesn't exist.
			if(in_array($item,$this->loaded,TRUE) || (!isset($this->common[$item]) && !isset($this->admin[$item]))){continue;}

			// Cache loaded component before calling dependencies.
			$this->loaded[] = $item;

			// Load dependencies.
			if(array_key_exists($item,$this->dependency)){
				$this->get_api_file($this->dependency[$item]);
			}
			$loaded_component = array();

			// Add admin components.
			if(isset($this->common[$item])){
				$loaded_component = (array)$this->common[$item];
			}

			// Add admin components.
			if(isset($this->admin[$item])){
				$loaded_component = array_merge((array)$loaded_component,(array)$this->admin[$item]);
			}

			// Load components.
			foreach($loaded_component as $loaded_component_path){
				require_once $loaded_component_path;
			}

			/**
			 * @reference (Beans)
			 * 	Fires when an API component is loaded.
			 * 	The dynamic portion of the hook name,$component,refers to the name of the API component loaded.
			 * 	https://www.getbeans.io/code-reference/hooks/beans_loaded_api_component_component/
			*/
			do_action('beans_extension_loaded_component_' . $item);
		}
		return TRUE;

	}// Method


	/* Method
	_________________________
	*/
	private function register_support($feature)
	{
		/**
			[Original]
				beans_add_api_component_support
				https://www.getbeans.io/code-reference/functions/beans_add_api_component_support/
			@access (private)
				Register API component support.
			@global (array) $_beans_extension_component_support
				API components support global.
			@param (string) $feature
				The feature to register.
			@return (bool)
				Will always return TRUE.
		*/

		// Custom global variable.
		global $_beans_extension_component_support;

		// Returns an array comprising a function's argument list.
		$args = func_get_args();

		// Returns the number of arguments passed to the function.
		if(1 === func_num_args()){
			$args = TRUE;
		}
		else{
			$args = array_slice($args,1);
		}
		$_beans_extension_component_support[$feature] = $args;

		return TRUE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_support($feature)
	{
		/**
			[Original]
				beans_get_component_support()
				https://www.getbeans.io/code-reference/functions/beans_get_component_support/
			@access (public)
				Gets the API component support argument(s).
			@global (array) $_beans_extension_component_support
				API components support global.
			@param (string) $feature
				The feature to check.
			@return (mixed)
				The argument(s) passed.
		*/

		// Custom global variable.
		global $_beans_extension_component_support;

		if(!isset($_beans_extension_component_support[$feature])){
			return FALSE;
		}
		return $_beans_extension_component_support[$feature];

	}// Method


	/* Method
	_________________________
	*/
	public static function __remove_support($feature)
	{
		/**
			[Original]
				beans_remove_api_component_support
				https://www.getbeans.io/code-reference/functions/beans_remove_api_component_support/
			@access (public)
				Remove API component support.
			@global (array) $_beans_extension_component_support
				API components support global.
			@param (string) $feature
				The feature to remove.
			@return (bool)
				Will always return TRUE.
		*/

		// Custom global variable.
		global $_beans_extension_component_support;
		unset($_beans_extension_component_support[$feature]);
		return TRUE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_setting($group = '',$needle = '')
	{
		/**
			@access (public)
				Returns the registerd settings.
			@param (string) $group
				Accepts 
				 - general
				 - image
				 - layout
			@param (string) $needle
				Name of setting.
			@return (mixed)|(array)
		*/
		if(!isset($group)){return;}

		// Custom global.
		global $_beans_extension_component_setting;

		switch($group){
			case 'general' :
				if(isset($needle) && array_key_exists($needle,$_beans_extension_component_setting['general'])){
					return $_beans_extension_component_setting['general'][$needle];
				}
				else{
					return $_beans_extension_component_setting['general'];
				}
				break;
			case 'image' :
				if(isset($needle) && array_key_exists($needle,$_beans_extension_component_setting['image'])){
					return $_beans_extension_component_setting['image'][$needle];
				}
				else{
					return $_beans_extension_component_setting['image'];
				}
				break;
			case 'layout' :
				if(isset($needle) && array_key_exists($needle,$_beans_extension_component_setting['layout'])){
					return $_beans_extension_component_setting['layout'][$needle];
				}
				else{
					return $_beans_extension_component_setting['layout'];
				}
				break;
		}

	}// Method


}// Class
endif;
// new _beans_component();
_beans_component::__get_instance();
