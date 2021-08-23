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
if(class_exists('_beans_attribute_html') === FALSE) :
final class _beans_attribute_html
{
/**
 * @since 1.0.1
 * 	Control a HTML attribute.
 * 	This class provides the means to add, replace,and remove a HTML attribute and its value(s).
 * 
 * [TOC]
 * 	__construct()
 * 	init()
 * 	add()
 * 	replace()
 * 	remove()
 * 	array_key_exists()
 * 	str_replace()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $id
			The markup ID.
		@var (string) $attribute
			Name of the HTML attribute.
		@var (string) $value
			Value of the HTML attribute.
			(i.e. value to be replaced or removed)
		@var (string) $new_value
			Replacement (new) value of the HTML attribute.
	*/
	private $id;
	private $attribute;
	private $value;
	private $new_value;


	/* Constructor
	_________________________
	*/
	public function __construct($id,$attribute,$value = NULL,$new_value = NULL)
	{
		/**
			@access (public)
				_Beans_Attributes constructor.
			@param (string) $id
				The markup ID.
			@param (string) $attribute
				Name of the HTML attribute.
			@param (string)|(null) $value
				[Optional]
				Value of the HTML attribute (i.e. value to be replaced or removed).
			@param (string)|(null) $new_value
				[Optional]
				Replacement (new) value of the HTML attribute.
			@return (void)
		*/

		// Init properties.
		$this->id = $id;
		$this->attribute = $attribute;
		$this->value = $value;
		$this->new_value = $new_value;

	}// Method


	/* Method
	_________________________
	*/
	public function init($method)
	{
		/**
			[ORIGINAL]
				init()
			@since 1.5.0
				Return self, for chaining and testing.
			@access (public)
				Initialize by registering the attribute filter.
			@param (array) $method
				Method to register as the callback for this filter.
			@return (void)|(self)
				For chaining.
			@reference
				[Plugin]/api/filter/beans.php
		*/
		if(!method_exists($this,$method)){return;}
		/**
		 * @reference (Beans)
		 * 	Hooks a function or method to a specific filter action.
		 * 	https://www.getbeans.io/code-reference/functions/beans_add_filter/
		*/
		_beans_filter::__add_filter($this->id . '_attribute',[$this,$method]);
		return $this;

	}// Method


	/* Method
	_________________________
	*/
	public function add(array $attributes)
	{
		/**
			[ORIGINAL]
				add()
			@access (public)
				Add a value to an existing attribute or add a new attribute.
			@param(array) $attributes
				Array of HTML markup attributes.
			@return (array)
		*/
		if($this->array_key_exists($attributes)){
			$attributes[$this->attribute] .= ' ' . $this->value;
		}
		else{
			$attributes[$this->attribute] = $this->value;
		}
		return $attributes;

	}// Method


	/* Method
	_________________________
	*/
	public function replace(array $attributes)
	{
		/**
			[ORIGINAL]
				replace()
			@since 1.5.0
				Allows replacement of all values.
			@access (public)
				Replace the attribute's value. If the attribute does not exist, it is added with the new value.
			@param (array) $attributes
				Array of HTML markup attributes.
			@return (array)
		*/
		if($this->array_key_exists($attributes) && !empty($this->value)){
			$attributes[$this->attribute] = $this->str_replace($attributes[$this->attribute]);
		}
		else{
			$attributes[$this->attribute] = $this->new_value;
		}
		return $attributes;

	}// Method


	/* Method
	_________________________
	*/
	public function remove(array $attributes)
	{
		/**
			[ORIGINAL]
				remove()
			@access (public)
				Remove a specific value from the attribute or remove the entire attribute.
				When the attribute value to remove is null, the attribute is removed; else, the value is removed.
			@param (array) $attributes
				Array of HTML markup attributes.
			@return (array)
		*/
		if(empty($attributes)){
			return $attributes;
		}

		if(!$this->array_key_exists($attributes)){
			return $attributes;
		}

		if(is_null($this->value)){
			unset($attributes[$this->attribute]);
		}
		else{
			$attributes[$this->attribute] = $this->str_replace($attributes[$this->attribute]);
		}
		return $attributes;

	}// Method


	/**
		[ORIGINAL]
			has_attribute()
		@access (private)
			Checks if the attribute exists in the given attributes.
		@param (array) $attributes
			Array of HTML markup attributes.
		@return (bool)
	*/
	private function array_key_exists(array $attributes)
	{
		return isset($attributes[$this->attribute]);

	}// Method


	/**
		[ORIGINAL]
			replace_value()
		@access (private)
			Replace the attribute's value.
		@param (string) $value
			The current attribute's value.
		@return (string)
	*/
	private function str_replace($value)
	{
		return str_replace($this->value,$this->new_value,$value);

	}// Method


}// Class
endif;
