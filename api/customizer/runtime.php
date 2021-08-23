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

// Customize Manager class.
use WP_Customize_Manager;


/* Exec
______________________________
*/
if(class_exists('_beans_runtime_customizer') === FALSE) :
class _beans_runtime_customizer
{
/**
 * @reference (WP)
 * 	Handle the Beans WP Customize workflow.
 * 	https://developer.wordpress.org/reference/classes/wp_customize_manager/
 * 	This class handles the Beans WP Customize workflow.
 * 
 * [TOC]
 * 	__construct()
 * 	add()
 * 	add_section()
 * 	add_group()
 * 	add_setting()
 * 	add_control()
 * 	sanitize()
*/

	/**
		@access (private)
			Class properties.
		@var(string) $args
			Metabox arguments.
		@var(string) $section
			Field section.
	*/
	private $args = array();
	private $section;


	/* Constructor
	_________________________
	*/
	public function __construct($section,array $args)
	{
		/**
			@access (public)
				Class constructor.
			@param (string) $section
				Field section.
			@param (array) $args
				Metabox arguments.
			@return (void)
			@reference
				[Plugin]/api/html/beans.php
		*/

		// Init properties.
		$defaults = array(
			'title' => esc_html__('Undefined','beans-extension'),
			'priority' => 30,
			'description' => FALSE,
		);
		$this->section = $section;
		$this->args = array_merge($defaults,$args);

		// Add section,settings and controls.
		$this->add();

		/**
		 * @reference (Beans)
		 * 	Add attribute to markup.
		 * 	https://www.getbeans.io/code-reference/functions/beans_add_attribute/
		*/
		_beans_html::__add_attribute('beans_extension_field_label','class','customize-control-title');

	}// Method


	/**
		@access (private)
			Add section,settings and controls.
		@global (WP_Customize_Manager) $wp_customize
			Customize Manager class.
			https://developer.wordpress.org/reference/classes/wp_customize_manager/
		@return (void)
		@reference
			[Plugin]/api/field/beans.php
	*/
	private function add()
	{
		global $wp_customize;

		$this->add_section($wp_customize);

		/**
		 * @reference (Beans)
		 * 	Get registered fields.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_fields/
		*/
		$fields = _beans_field::__get_setting('wp_customize',$this->section);
		foreach($fields as $field){
			$this->add_group($wp_customize,$field);
			$this->add_setting($wp_customize,$field);
			$this->add_control($wp_customize,$field);
		}

	}// Method


	/**
		@access (private)
			Add Section.
			https://developer.wordpress.org/reference/classes/wp_customize_manager/add_section/
		@global (WP_Customize_Manager) $wp_customize
			Customize Manager class.
			https://developer.wordpress.org/reference/classes/wp_customize_manager/
		@param (WP_Customize_Manager) $wp_customize
			WP Customizer Manager object.
		@return (void)
	*/
	private function add_section(WP_Customize_Manager $wp_customize)
	{
		if($wp_customize->get_section($this->section)){return;}

		$wp_customize->add_section($this->section,array(
			'title' => $this->args['title'],
			'priority' => $this->args['priority'],
			'description' => $this->args['description'],
		));

	}// Method


	/**
		@since 1.5.0
		@access (private)
			Add Group setting.
		@global (WP_Customize_Manager) $wp_customize
			Customize Manager class.
			https://developer.wordpress.org/reference/classes/wp_customize_manager/
		@param (WP_Customize_Manager) $wp_customize
			WP Customizer Manager object.
		@param (array) $field
			Metabox settings.
		@return (void)
	*/
	private function add_group(WP_Customize_Manager $wp_customize,array $field)
	{
		if('group' !== $field['type']){return;}

		foreach($field['field'] as $_field){
			$this->add_setting($wp_customize,$_field);
		}

	}// Method


	/**
		@access (private)
			Add setting.
			https://developer.wordpress.org/reference/classes/wp_customize_manager/add_setting/
		@global (WP_Customize_Manager) $wp_customize
			Customize Manager class.
			https://developer.wordpress.org/reference/classes/wp_customize_manager/
		@param (WP_Customize_Manager) $wp_customize
			WP Customizer Manager object.
		@param (array) $field{
			Array of metabox settings.
			@type (string) $db_type
				[Optional]
				Defines how the setting will be saved.
				[Defaults] 'theme_mod'.
			@type (string) $capability
				[Optional]
				Defines the user's permission level needed to see the setting.
				[Defaults] 'edit_theme_options'.
			@type (string) $transport
				[Optional]
				Defines how the live preview is updated.
				[Defaults]'refresh'.
		}
		@return (void)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function add_setting(WP_Customize_Manager $wp_customize,array $field)
	{
		$defaults = array(
			'db_type' => 'theme_mod',
			'capability'	 => 'edit_theme_options',
			'transport' => 'refresh',
		);
		$field = array_merge($defaults,$field);

		$wp_customize->add_setting($field['name'],array(
			'default' => _beans_utility::__get_global_value('default',$field),
			'type' => $field['db_type'],
			'capability' => $field['capability'],
			'transport' => $field['transport'],
			'sanitize_callback' => [$this,'sanitize'],
		));

	}// Method


	/**
		@access (private)
			Add Control.
			https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
		@global (WP_Customize_Manager) $wp_customize
			Customize Manager class.
			https://developer.wordpress.org/reference/classes/wp_customize_manager/
		@param (WP_Customize_Manager) $wp_customize
			WP Customizer Manager object.
		@param (array) $field{
			Metabox settings.
			@type (string) $type
				Field type or WP_Customize control class.
			@type (string) $name
				Name of the control.
			@type (string) $label
				Label of the control.
		}
		@return (void)
		@reference
			[Plugin]/api/customizer/control.php
	*/
	private function add_control(WP_Customize_Manager $wp_customize,array $field)
	{
		$class = __NAMESPACE__ . '\_beans_control_customizer';
		if($field['type'] !== $class && class_exists($field['type'])){
			$class = $field['type'];
		}
		$wp_customize->add_control(new $class($wp_customize,$field['name'],array(
			'label' => $field['label'],
			'section' => $this->section,
		),$field));

	}// Method


	/* Method
	_________________________
	*/
	public function sanitize($value)
	{
		/**
			@access (public)
				Sanitize the value.
			@param (mixed) $value
			@return (mixed)
		*/
		return $value;

	}// Method


}// Class
endif;
