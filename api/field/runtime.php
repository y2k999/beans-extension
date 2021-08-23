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
if(class_exists('_beans_runtime_field') === FALSE) :
final class _beans_runtime_field
{
/**
 * @since 1.0.1
 * 	The Beans Fields' Container.
 * 	Handles standardizing and registering the fields into the Beans Fields' Container.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	register()
 * 	add()
 * 	standardize()
 * 	set_type()
 * 	do_once()
 * 	load_fields()
 * 	load_fields_assets_hook()
 * 	enqueue_global_assets()
 * 	get_field_value()
 * 	get_fields()
*/

	/**
		@access (private)
			Class properties.
		@var (array) $fields
		@var (array) $field_types
		@var (string) $context
			Context in which the fields are used.
		@var (string) $section
			Field section.
		@var (array) $field_types_loaded
			Field types loaded.
		@var (array) $field_assets_hook_loaded
			Field assets hook loaded.
		@var (array) $registered
			Registered fields.
	*/
	private $fields = array();
	private $field_types = array();
	private $context;
	private $section;
	private $field_type_loaded = array();
	private $field_asset_hook_loaded = array();

	private static $_registered = array(
		'option' => array(),
		'post_meta' => array(),
		'term_meta' => array(),
		'wp_customize' => array(),
	);


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
	public function register(array $fields,$context,$section)
	{
		/**
			[ORIGINAL]
				register()
			@access (public)
				Register the given fields.
			@param (array) $fields
				Array of fields to register.
			@param (string) $context
				The context in which the fields are used.
				 - 'option' for options/settings pages,
				 - 'post_meta' for post fields,
				 - 'term_meta' for taxonomies fields
				 - 'wp_customize' for WP customizer fields.
			@param (string) $section
				A section ID to define the group of fields.
			@return (bool)
		*/
		$this->fields = $fields;
		$this->context = $context;
		$this->section = $section;

		$this->add();
		$this->do_once();
		$this->load();

		// Register hooks.
		add_action('admin_enqueue_scripts',[$this,'load_hook_point']);
		add_action('customize_controls_enqueue_scripts',[$this,'load_hook_point']);

		return TRUE;

	}// Method


	/**
		[ORIGINAL]
			add()
		@access (private)
			Register the field.
		@return (void)
	*/
	private function add()
	{
		$fields = array();

		foreach($this->fields as $field){
			$fields[] = $this->standardize($field);
			$this->set_type($field);
		}
		// Register fields.
		self::$_registered[$this->context][$this->section] = $fields;

	}// Method


	/**
		[ORIGINAL]
			standardize_field()
		@access (private)
			Standardize the field to include the default configuration parameters and fetching the current value.
		@param (array) $field
			The given field to be standardized.
		@return (array)
	*/
	private function standardize(array $field)
	{
		$field = array_merge(array(
			'label' => FALSE,
			'description' => FALSE,
			'default' => FALSE,
			'context' => $this->context,
			'attribute' => array(),
			'db_group' => FALSE,
		),$field);

		// Set the field's name.
		$field['name'] = 'wp_customize' === $this->context ? $field['id'] : 'beans_extension_field[' . $field['id'] . ']';

		if('group' === $field['type']){
			foreach($field['field'] as $index => $_field){
				if($field['db_group']){
					$_field['name'] = $field['name'] . '[' . $_field['id'] . ']';
				}
				$field['field'][$index] = $this->standardize($_field);
			}
		}
		else{
			// Add value after standardizing the field.
			$field['value'] = $this->get_value($field['id'],$field['context'],$field['default']);
		}

		// Add required attributes for wp_customizer.
		if('wp_customize' === $this->context){
			$field['attribute'] = array_merge($field['attribute'],array(
				'data-customize-setting-link' => $field['name']
			));
		}
		return $field;

	}// Method


	/**
		[ORIGINAL]
			set_type()
		@since 1.5.0
		@access (private)
			Set the type for the given field.
		@param (array) $field
			The given field.
		@return (void)
	*/
	private function set_type(array $field)
	{
		// Set the single field's type.
		if('group' !== $field['type']){
			$this->field_types[$field['type']] = $field['type'];
			return;
		}
		foreach($field['field'] as $_field){
			$this->field_types[$_field['type']] = $_field['type'];
		}

	}// Method


	/**
		[ORIGINAL]
			do_once()
		@access (private)
			Trigger actions only once.
		@return (void)
		@reference
			[Plugin]/include/constant.php
	*/
	private function do_once()
	{
		static $once = FALSE;
		if($once){return;}

		// Register hooks.
		add_action('admin_enqueue_scripts',[$this,'enqueue_asset']);
		add_action('customize_controls_enqueue_scripts',[$this,'enqueue_asset']);

		// Load the field label and description handler.
		require_once BEANS_EXTENSION_API_PATH['field'] . 'view/field.php';
		$once = TRUE;

	}// Method


	/**
		[ORIGINAL]
			load_fields()
		@access (private)
			Load the field type PHP file for each of the fields.
		@return (void)
		@reference
			[Plugin]/include/constant.php
	*/
	private function load()
	{
		foreach($this->field_types as $type){
			// Stop here if the field type has already been loaded.
			if(in_array($type,$this->field_type_loaded,TRUE)){continue;}

			$path = BEANS_EXTENSION_API_PATH['field'] . "view/{$type}.php";
			if(file_exists($path)){
				require_once $path;
			}
			// Set a flag that the field is loaded.
			$this->field_type_loaded[$type] = $type;
		}

	}// Method


	/* Hook
	_________________________
	*/
	public function load_hook_point()
	{
		/**
			[ORIGINAL]
				load_fields_assets_hook()
				Enqueue scripts for all admin pages.
				https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
				Enqueue Customizer control scripts.
				https://developer.wordpress.org/reference/hooks/customize_controls_enqueue_scripts/
			@access (public)
				Load the field's assets hook.
				This hook can then be used to load custom assets for the field.
			@return (void)
		*/
		foreach($this->field_types as $type){
			// Stop here if the field type has already been loaded.
			if(in_array($type,$this->field_asset_hook_loaded,TRUE)){continue;}

			do_action("beans_extension_field_enqueue_script_{$type}");

			// Set a flag that the field is loaded.
			$this->field_asset_hook_loaded[$type] = $type;
		}

	}// Method


	/* Method
	_________________________
	*/
	public function enqueue_asset()
	{
		/**
			[ORIGINAL]
				enqueue_global_assets()
				Enqueue scripts for all admin pages.
				https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
				Enqueue Customizer control scripts.
				https://developer.wordpress.org/reference/hooks/customize_controls_enqueue_scripts/
			@access (public)
				Enqueue the default assets for the fields.
			@return (void)
			@reference
				[Plugin]/include/constant.php
		*/
		$css = BEANS_EXTENSION_API_URL['asset'] . 'css/field' . BEANS_EXTENSION_MIN_CSS . '.css';
		$js = BEANS_EXTENSION_API_URL['asset'] . 'js/field' . BEANS_EXTENSION_MIN_JS . '.js';

		wp_enqueue_style(
			'beans-extension-field',
			$css,
			FALSE,
			BEANS_EXTENSION_VERSION
		);
		wp_enqueue_script(
			'beans-extension-field',
			$js,
			array('jquery'),
			BEANS_EXTENSION_VERSION
		);
		do_action('beans_extension_field_enqueue_script');

	}// Method


	/**
		[ORIGINAL]
			get_field_value()
		@since 1.5.0
			Return the default when the context is not pre-defined.
		@access (private)
			Get the field value.
		@param (string) $field_id
			Field's ID.
		@param (string) $context
			The field's context,
			i.e. "option","post_meta","term_meta",or "wp_customize".
		@param (mixed) $default
			The field's default value.
		@return (mixed)|(string)|(void)
		@reference
			[Plugin]/api/option/beans.php
			[Plugin]/api/post-meta/beans.php
			[Plugin]/api/term-meta/beans.php
	*/
	private function get_value($field_id,$context,$default)
	{
		switch($context){
			case 'option' :
				return get_option($field_id,$default);

			case 'post_meta' :
				return _beans_post_meta::__get_setting($field_id,$default);

			case 'term_meta' :
				return _beans_term_meta::__get_setting($field_id,$default);

			case 'wp_customize' :
				return get_theme_mod($field_id,$default);
		}
		return $default;

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_setting($context,$section)
	{
		/**
			[ORIGINAL]
				get_fields()
			@since 1.5.0
				Changed to static method.
			@access (public)
				Get the registered fields.
			@param (string) $context
				The context in which the fields are used.
				 - 'option' for options/settings pages,
				 - 'post_meta' for post fields,
				 - 'term_meta' for taxonomies fields
				 - 'wp_customize' for WP customizer fields.
			@param (string) $section
				[Optional]
				A section ID to define a group of fields.
				This is mostly used for meta boxes and WP Customizer sections.
			@return (array)|(bool)
				Array of registered fields on success,false on failure.
			@reference
				[Plugin]/utility/beans.php
		*/

		// Get value from $_GET or defined $_haystack.
		$fields = _beans_utility::__get_global_value($section,self::$_registered[$context]);
		if(!$fields){
			return FALSE;
		}
		return $fields;

	}// Method


}// Class
endif;
