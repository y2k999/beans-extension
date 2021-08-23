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
if(class_exists('_beans_html') === FALSE) :
class _beans_html
{
/**
 * @since 1.0.1
 * 	The Beans HTML component contains a powerful set of functions to create flexible and easy overwritable html markup, attributes and content.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	beans_output()
 * 	beans_output_e()
 * 	beans_remove_output()
 * 	beans_open_markup()
 * 	beans_open_markup_e()
 * 	beans_selfclose_markup()
 * 	beans_selfclose_markup_e()
 * 	beans_close_markup()
 * 	beans_close_markup_e()
 * 	beans_modify_markup()
 * 	beans_remove_markup()
 * 	beans_reset_markup()
 * 	beans_wrap_markup()
 * 	beans_wrap_inner_markup()
 * 	beans_add_attributes()
 * 	beans_esc_attributes()
 * 	beans_reset_attributes()
 * 	beans_add_attribute()
 * 	beans_replace_attribute()
 * 	beans_remove_attribute()
 * 	_beans_is_html_dev_mode()
*/

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
	public static function __output($id,$output)
	{
		/**
			[ORIGINAL]
				beans_output()
				https://www.getbeans.io/code-reference/functions/beans_output/
			@access (public)
				Register the output for the given ID.
				This function enables the output to be:
				1. modified by registering a callback to "{$id}_output"
				2. removed by using {@see beans_remove_output()}.
				When in development mode, comments containing the ID are added before and after the output,i.e. making it easier to identify the content ID when inspecting an element in your web browser.
				[NOTE]
				1. Since this function uses, the $id argument may contain sub-hook(s).{@see beans_apply_filters()}
				2. You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
			@param (string) $id
				A unique string used as a reference.
				The $id argument may contain sub-hook(s).
			@param (string) $output
				The given content to output.
			@return (string)|(void)
			@reference
				[Plugin]/api/filter/beans.php
		*/

		// Returns an array comprising a function's argument list.
		$args = func_get_args();
		$args[0] = $id . '_output';

		/**
		 * @since 1.0.1
		 * 	Call a callback with an array of parameters.
		 * @reference (Beans)
		 * 	Call the functions added to a filter hook.
		 * 	https://www.getbeans.io/code-reference/functions/beans_apply_filters/
		*/
		$output = call_user_func_array([__NAMESPACE__ . '\_beans_filter','__apply_filter'],$args);
		if(empty($output)){return;}

		if(self::__is_dev_mode()){
			$id = esc_attr($id);
			$output = "<!-- [output] $id -->" . $output . "<!-- [/output] -->";
		}
		return $output;

	}// Method


	/* Method
	_________________________
	*/
	public static function __output_e($id,$output)
	{
		/**
			[ORIGINAL]
				beans_output_e()
				https://www.getbeans.io/code-reference/functions/beans_output_e/
			@access (public)
				Register and then echo the output for the given ID.
				This function is a wrapper for {@see beans_output()}.
			@param (string) $id
				A unique string used as a reference.
				The $id argument may contain sub-hook(s).
			@param (string) $output
				The given content to output.
			@return (void)
		*/
		$args = func_get_args();
		/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaped in beans_output. */
		echo call_user_func_array([__CLASS__,'__output'],$args);

	}// Method


	/* Method
	_________________________
	*/
	public static function __remove_output($id)
	{
		/**
			[ORIGINAL]
				beans_remove_output()
				https://www.getbeans.io/code-reference/functions/beans_remove_output/
			@access (public)
				Removes the HTML output for the given $_id,meaning the output will not render.
			@param (string) $id
				The output's ID.
			@return (bool)|(_Beans_Anonymous_Filters)
			@reference
				[Plugin]/api/filter/beans.php
		*/

		/**
		 * @reference (Beans)
		 * 	Hooks a function or method to a specific filter action.
		 * 	https://www.getbeans.io/code-reference/functions/beans_add_filter/
		*/
		return _beans_filter::__add_filter($id . '_output',FALSE,99999999);

	}// Method


	/* Method
	_________________________
	*/
	public static function __open_markup($id,$tag,$attributes = array())
	{
		/**
			[ORIGINAL]
				beans_open_markup()
				https://www.getbeans.io/code-reference/functions/beans_open_markup/
			@access (public)
				Build the opening HTML element's markup.
				This function fires 3 separate hooks:
				1. "{$id}_before_markup"
					 - which fires first before the element.
				2. "{$id}_prepend_markup"
					 - which fires after the element when the element is not self-closing.
				3. "{$id}_after_markup"
					 - which fires after the element when the element is self-closing.
				These 3 hooks along with the attributes make it really easy to modify,replace,extend,remove or hook the markup and/or attributes.
				When in development mode,the "data-markup-id" attribute is added to the element,i.e. making it easier to identify the content ID when inspecting an element in your web browser.
				[NOTE]
				1. Since this function uses, the $id argument may contain sub-hook(s).{@see beans_apply_filters()}
				2. You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
			@global (array) $_beans_extension_is_selfclose_markup
				When true, indicates a self-closing element should be built.
			@param (string) $id
				A unique string used as a reference.
				The $id argument may contain sub-hooks(s).
			@param (string)|(bool) $tag
				The HTML tag.
				When set to false or an empty string,the markup tag will not be built, but both action hooks will fire.
				If set to NULL, the function bails out,i.e. the markup tag will not be built and neither action hook fires.
			@param (string)|(array)$attributes
				[Optional]
				Query string or array of attributes.
				The array key defines the attribute name and the array value defines the attribute value.
			@return (string)|(void)
			@reference
				[Plugin]/api/action/beans.php
				[Plugin]/api/filter/beans.php
		*/
		$args = func_get_args();
		$attributes_args = $args;

		// Set markup tag filter id.
		$args[0] = $id . '_markup';

		// If there are attributes, remove them from $args.
		if($attributes){
			unset($args[2]);
		}

		/**
		 * @since 1.0.1
		 * 	Filter the tag.
		 * @reference (Beans)
		 * 	Call the functions added to a filter hook.
		 * 	https://www.getbeans.io/code-reference/functions/beans_apply_filters/
		*/
		$tag = call_user_func_array([__NAMESPACE__ . '\_beans_filter','__apply_filter'],$args);

		// If the tag is set to NULL, bail out.
		if(NULL === $tag){return;}

		// Custom global variable.
		global $_beans_extension_is_selfclose_markup;

		// Remove the $tag argument.
		unset($args[1]);
		unset($attributes_args[1]);

		// Set and then fire the before action hook.
		$args[0] = $id . '_before_markup';

		$output = call_user_func_array([__NAMESPACE__ . '\_beans_action','__render_action'],$args);

		// Build the opening tag when tag is available.
		if($tag){
			$output .= '<' . esc_attr($tag);
			$output .= !empty($attributes_args) ? ' ' : '';
			$output .= call_user_func_array([__CLASS__,'__convert_attribute'],$attributes_args);
			$output .= self::__is_dev_mode() ? ' ' . 'data-markup-id="' . esc_attr($id) . '"' : NULL;
			$output .= $_beans_extension_is_selfclose_markup ? ' /' : '';
			$output .= '>';
		}

		// Set and then fire the after action hook.
		$args[0] = $id . ($_beans_extension_is_selfclose_markup ? '_after_markup' : '_prepend_markup');
		$output .= call_user_func_array([__NAMESPACE__ . '\_beans_action','__render_action'],$args);

		// Reset the global variable to reduce memory usage.
		unset($GLOBALS['_beans_extension_is_selfclose_markup']);

		return $output;

	}// Method


	/* Method
	_________________________
	*/
	public static function __open_markup_e($id,$tag,$attributes = array())
	{
		/**
			[ORIGINAL]
				beans_open_markup_e()
				https://www.getbeans.io/code-reference/functions/beans_open_markup_e/
			@access (public)
				Echo the opening HTML tag's markup.
				This function is a wrapper for {@see beans_open_markup()}.
			@param (string) $id
				A unique string used as a reference.
				The $id argument may contain sub-hooks(s).
			@param (string)|(bool) $tag
				The HTML tag.
				When set to FALSE or an empty string,the markup tag will not be built, but both action hooks will fire.
				If set to NULL, the function bails out,i.e. the markup tag will not be built and neither action hook fires.
			@param (string)|(array) $attributes
				[Optional]
				Query string or array of attributes.
				The array key defines the attribute name and the array value defines the attribute value.
			@return (void)
		*/
		$args = func_get_args();
		/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaped in beans_open_markup(). */
		echo call_user_func_array([__CLASS__,'__open_markup'],$args);

	}// Method


	/* Method
	_________________________
	*/
	public static function __selfclose_markup($id,$tag,$attributes = array())
	{
		/**
			[ORIGINAL]
				beans_selfclose_markup()
				https://www.getbeans.io/code-reference/functions/beans_selfclose_markup/
			@access (public)
				Build the self-closing HTML element's markup.
				This function is shortcut of {@see beans_open_markup()}.
				It should be used for self-closing markup such as images or inputs.
				[NOTE]
				You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
			@global (bool) $_beans_extension_is_selfclose_markup
				When true, indicates a self-closing element should be built.
			@param (string) $id
				A unique string used as a reference.
				The $id argument may contain sub-hooks(s).
			@param (string)|(bool) $tag
				The self-closing HTML tag.
				When set to FALSE or an empty string,the markup tag will not be built, but both action hooks will fire.
				If set to NULL, the function bails out,i.e. the markup tag will not be built and neither action hook fires.
			@param (string)|(array) $attributes
				[Optional]
				Query string or array of attributes.
				The array key defines the attribute name and the array value defines the attribute value.
			@return (string)|(void)
		*/

		// When TRUE, indicates a self-closing element should be built.
		global $_beans_extension_is_selfclose_markup;
		$_beans_extension_is_selfclose_markup = TRUE;

		$args = func_get_args();
		$html = call_user_func_array([__CLASS__,'__open_markup'],$args);

		// Reset the global variable to reduce memory usage.
		unset($GLOBALS['_beans_extension_is_selfclose_markup']);

		return $html;

	}// Method


	/* Method
	_________________________
	*/
	public static function __selfclose_markup_e($id,$tag,$attributes = array())
	{
		/**
			[ORIGINAL]
				beans_selfclose_markup_e()
				https://www.getbeans.io/code-reference/functions/beans_selfclose_markup_e/
			@access (public)
				Echo the self-closing HTML element's markup.
				This function is a wrapper for {@see beans_selfclose_markup()}.
				See{@see beans_selfclose_markup()} for more details.
			@param (string) $id
				A unique string used as a reference.
				The $id argument may contain sub-hooks(s).
			@param (string)|(bool) $tag
				The self-closing HTML tag.
				When set to FALSE or an empty string,the markup tag will not be built, but both action hooks will fire.
				If set to NULL, the function bails out,i.e. the markup tag will not be built and neither action hook fires.
			@param (string)|(array) $attributes
				[Optional]
				Query string or array of attributes.
				The array key defines the attribute name and the array value defines the attribute value.
			@return (void)
		*/
		$args = func_get_args();
		/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaped in beans_open_markup(). */
		echo call_user_func_array([__CLASS__,'__selfclose_markup'],$args);

	}// Method


	/* Method
	_________________________
	*/
	public static function __close_markup($id,$tag)
	{
		/**
			[ORIGINAL]
				beans_close_markup()
				https://www.getbeans.io/code-reference/functions/beans_close_markup/
			@access (public)
				Build the closing HTML element's markup.
				This function fires 2 separate hooks:
				1. "{$id}_append_markup"
					 - which fires first before the closing tag.
				2. "{$id}_after_markup"
					 - which fires after the closing tag.
				[NOTE]
				You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
			@param (string) $id
				Identical to the opening markup ID.
			@param (string) $tag
				The HTML tag.
				When set to FALSE or an empty string,the HTML markup tag will not be built,but both action hooks will fire.
				If set to NULL, the function bails out,i.e. the HTML markup tag will not be built and neither action hook fires.
			@return (string)|(void)
			@reference
				[Plugin]/api/action/beans.php
				[Plugin]/api/filter/beans.php
		*/

		// Filter the tag.
		$tag = _beans_filter::__apply_filter($id . '_markup',$tag);

		// If the tag is set to NULL, bail out.
		if(NULL === $tag){return;}

		$args = func_get_args();

		// Remove the $tag argument.
		unset($args[1]);

		// Set and then fire the append action hook.
		$args[0] = $id . '_append_markup';

		$output = call_user_func_array([__NAMESPACE__ . '\_beans_action','__render_action'],$args);

		// Build the closing tag when tag is available.
		if($tag){
			$output .= '</' . esc_attr($tag) . '>';
		}

		// Set and then fire the after action hook.
		$args[0] = $id . '_after_markup';
		$output .= call_user_func_array([__NAMESPACE__ . '\_beans_action','__render_action'],$args);

		return $output;

	}// Method


	/* Method
	_________________________
	*/
	public static function __close_markup_e($id,$tag)
	{
		/**
			[ORIGINAL]
				beans_close_markup_e()
				https://www.getbeans.io/code-reference/functions/beans_close_markup_e/
			@access (public)
				Echo the closing HTML tag's markup. 
				This function is a wrapper for {@see beans_close_markup()}.
			@param (string) $id
				Identical to the opening markup ID.
			@param (string) $tag
				The HTML tag.
				When set to FALSE or an empty string,the HTML markup tag will not be built,but both action hooks will fire.
				If set to NULL,the function bails out,i.e. the HTML markup tag will not be built and neither action hook fires.
			@return (void)
		*/
		$args = func_get_args();
		/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaped in beans_close_markup(). */
		echo call_user_func_array([__CLASS__,'__close_markup'],$args);

	}// Method


	/* Method
	_________________________
	*/
	public static function __modify_markup($id,$markup,$priority = 10,$args = 1)
	{
		/**
			[ORIGINAL]
				beans_modify_markup()
				https://www.getbeans.io/code-reference/functions/beans_modify_markup/
			@access (public)
				Modify the opening and closing or self-closing HTML tag.
			@param (string) $id
				The target markup's ID.
			@param(string)|(callback) $markup
				The replacement HTML tag. A callback is accepted if conditions need to be applied.
				If arguments are available, then they are passed to the callback.
			@param (int) $priority
				[Optional]
				Used to specify the order in which the functions associated with a particular action are executed.
				[Default] 10
			@param (int) $args...,
				[Optional]
				The number of arguments the callback accepts.
				[Default] 1
			@return (bool)|(_Beans_Anonymous_Filters)
			@reference
				[Plugin]/api/filter/beans.php
		*/

		/**
		 * @reference (Beans)
		 * 	Hooks a function or method to a specific filter action.
		 * 	https://www.getbeans.io/code-reference/functions/beans_add_filter/
		*/
		return _beans_filter::__add_filter($id . '_markup',$markup,$priority,$args);

	}// Method


	/* Method
	_________________________
	*/
	public static function __remove_markup($id,$remove_actions = FALSE)
	{
		/**
			[ORIGINAL]
				beans_remove_markup()
				https://www.getbeans.io/code-reference/functions/beans_remove_markup/
			@access (public)
				Modify the opening and closing or self-closing HTML tag.
			@param (string) $id
				The target markup's ID.
			@param(string)|(callback) $markup
				The replacement HTML tag.
				A callback is accepted if conditions need to be applied.
				If arguments are available, then they are passed to the callback.
			@param (int) $priority
				[Optional]
				Used to specify the order in which the functions associated with a particular action are executed.
				[Default] 10
			@param (int) $args
				[Optional]
				The number of arguments the callback accepts.
				[Default] 1
			@return (bool)|(_Beans_Anonymous_Filters)
			@reference
				[Plugin]/api/filter/beans.php
		*/

		/**
		 * @reference (Beans)
		 * 	Hooks a function or method to a specific filter action.
		 * 	https://www.getbeans.io/code-reference/functions/beans_add_filter/
		*/
		return _beans_filter::__add_filter($id . '_markup',$remove_actions ? NULL : FALSE);

	}// Method


	/* Method
	_________________________
	*/
	public static function __reset_markup($id)
	{
		/**
			[ORIGINAL]
				beans_reset_markup()
				https://www.getbeans.io/code-reference/functions/beans_reset_markup/
			@access (public)
				Reset the given markup's tag.
				This function will automatically reset the opening and closing HTML tag or self-closing tag to its original value.
			@param (string) $id 
				The markup ID.
			@return (void)
		*/

		/**
		 * @reference (WP)
		 * 	Remove all of the hooks from a filter.
		 * 	https://developer.wordpress.org/reference/functions/remove_all_filters/
		*/
		remove_all_filters($id . '_markup');
		remove_all_filters(preg_replace('#(\[|\])#','',$id) . '_markup');

	}// Method


	/* Method
	_________________________
	*/
	public static function __wrap_markup($id,$new_id,$tag,$attributes = array())
	{
		/**
			[ORIGINAL]
				beans_wrap_markup()
				https://www.getbeans.io/code-reference/functions/beans_wrap_markup/
			@access (public)
				Convert an array of attributes into a properly formatted HTML string.
				The attributes are registered in Beans via the given ID.
				Using this ID, we can hook into the filter,i.e."$id_attributes", to modify, replace, extend, or remove one or more of the registered attributes.
				Since this function uses {@see beans_apply_filters()},the $id argument may contain sub-hook(s).
				[NOTE]
				You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
			@param (string) $id
				A unique string used as a reference.
				The $id argument may contain sub-hook(s).
			@param (string)|(array) $attributes
				[Optional]
				Query string or array of attributes.
				The array key defines the attribute name and the array value define the attribute value.
				 - Setting the array value to ''
					will display the attribute value as empty (e.g. class="").
				 - Setting it to 'FALSE'
					will only display the attribute name (e.g. data-example).
				 - Setting it to 'NULL'
					will not display anything.
			@return (string)
				The HTML attributes.
			@reference
				[Plugin]/api/action/beans.php
		*/
		if(!$tag){
			return FALSE;
		}

		$args = func_get_args();
		unset($args[0]);
		_beans_action::__anonymous_action($id . '_before_markup',[[__CLASS__,'__open_markup'],$args],9999);

		unset($args[3]);
		_beans_action::__anonymous_action($id . '_after_markup',[[__CLASS__,'__close_markup'],$args],1);

		return TRUE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __wrap_inner_markup($id,$new_id,$tag,$attributes = array())
	{
		/**
			[ORIGINAL]
				beans_wrap_inner_markup()
				https://www.getbeans.io/code-reference/functions/beans_wrap_inner_markup/
			@since 1.5.0
				Bails out if an empty $tag is given.
			@access (public)
				Register the wrap inner content's markup with Beans using the given markup ID.
				This function registers an anonymous callback to the following action hooks:
				1. `$id_prepend_markup`:
						When this hook fires,{@see beans_open_markup()} is called to build the wrap inner content's HTML markup just after the wrap's opening tag.
				2. `$id_append_markup`:
					When this hook fires,{@see beans_close_markup()} is called to build the wrap inner content's HTML markup just before the wrap's closing tag.
				[NOTE]
				You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
			@param (string) $id
				The wrap markup's ID.
			@param (string) $new_id
				A unique string used as a reference.
				The $id argument may contain sub-hook(s).
			@param (string) $tag
				The wrap inner content's HTML tag.
			@param (string)|(array) $attributes
				[Optional]
				Query string or array of attributes.
				The array key defines the attribute name and the array value define the attribute value.
				 - Setting the array value to ''
					will display the attribute value as empty (e.g. class="").
				 - Setting it to 'FALSE'
					will only display the attribute name (e.g. data-example).
				 - Setting it to 'NULL'
					will not display anything.
			@return (bool)
			@reference
				[Plugin]/api/action/beans.php
		*/
		if(!$tag){
			return FALSE;
		}

		$args = func_get_args();

		unset($args[0]);
		_beans_action::__anonymous_action($id . '_prepend_markup',[[__CLASS__,'__open_markup'],$args],1);

		unset($args[3]);
		_beans_action::__anonymous_action($id . '_append_markup',[[__CLASS__,'__close_markup'],$args],9999);

		return TRUE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __convert_attribute($id,$attributes = array())
	{
		/**
			[ORIGINAL]
				beans_add_attributes()
				https://www.getbeans.io/code-reference/functions/beans_add_attributes/
			@access (public)
				Convert an array of attributes into a properly formatted HTML string.
				The attributes are registered in Beans via the given ID.
				Using this ID, we can hook into the filter,i.e."$id_attributes", to modify, replace, extend, or remove one or more of the registered attributes.
				Since this function uses {@see beans_apply_filters()},the $id argument may contain sub-hook(s).
				[NOTE]
				You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
			@param (string) $id
				A unique string used as a reference.
				The $id argument may contain sub-hook(s).
			@param (string)|(array) $attributes
				[Optional]
				Query string or array of attributes.
				The array key defines the attribute name and the array value define the attribute value.
				 - Setting the array value to '' will display the attribute value as empty (e.g. class="").
				 - Setting it to 'FALSE' will only display the attribute name (e.g. data-example).
				 - Setting it to 'NULL' will not display anything.
			@return (string)
				The HTML attributes.
			@reference
				[Plugin]/api/filter/beans.php
		*/
		$args = func_get_args();
		// $args[0] = $id . '_attributes';
		$args[0] = $id . '_attribute';

		if(empty($args[1])){
			$args[1] = array();
		}

		/**
		 * @reference (WP)
		 * 	Merges user defined arguments into defaults array.
		 * 	https://developer.wordpress.org/reference/functions/wp_parse_args/
		*/
		$args[1] = wp_parse_args($args[1]);

		/**
		 * @reference (Beans)
		 * 	Call the functions added to a filter hook.
		 * 	https://www.getbeans.io/code-reference/functions/beans_apply_filters/
		*/
		$attributes = call_user_func_array([__NAMESPACE__ . '\_beans_filter','__apply_filter'],$args);

		return self::__esc_attribute($attributes);

	}// Method


	/* Method
	_________________________
	*/
	public static function __esc_attribute($attributes)
	{
		/**
			[ORIGINAL]
				beans_esc_attributes()
				https://www.getbeans.io/code-reference/functions/beans_esc_attributes/
			@access (public)
				Sanitize HTML attributes from array to string.
			@param (array) $attributes
				The array key defines the attribute name and the array value define the attribute value.
			@return (string)
				The escaped attributes.
			@reference
				[Plugin]/utility/beans.php
		*/

		/**
		 * @since1.3.1
		 * 	Filter attributes escaping methods.
		 * @reference (WP)
		 * 	For all unspecified selectors, values are automatically escaped using
		 * 	https://developer.wordpress.org/reference/functions/esc_attr/
		 * @param (array) $method
		 * 	Associative array of selectors as keys and escaping method as values.
		*/
		$methods = apply_filters('beans_extension_escape_attribute_method',array(
			'href' => 'esc_url',
			'src' => 'esc_url',
			'itemtype' => 'esc_url',
			'onclick' => 'esc_js',
		));

		$string = '';

		foreach((array)$attributes as $attribute => $value){
			if( NULL === $value ){continue;}
			$method = _beans_utility::__get_global_value($attribute,$methods);
			if($method){
				$value = call_user_func($method,$value);
			}
			else{
				$value = esc_attr($value);
			}
			$string .= $attribute . '="' . $value . '" ';
		}
		return trim($string);

	}// Method


	/* Method
	_________________________
	*/
	public static function __reset_attribute($id)
	{
		/**
			[ORIGINAL]
				beans_reset_attributes()
				https://www.getbeans.io/code-reference/functions/beans_reset_attributes/
			@access (public)
				Reset markup attributes.
				This function will reset the targeted markup attributes to their original values.
				It must be called before the targeted markup is called.
				The "data-markup-id" is added as a HTML attribute if the development mode is enabled.
				This makes it very easy to find the content ID when inspecting an element in a web browser.
			@param (string) $id
				The markup ID.
			@return (void)
		*/

		/**
		 * @reference (WP)
		 * 	Remove all of the hooks from a filter.
		 * 	https://developer.wordpress.org/reference/functions/remove_all_filters/
		*/
		remove_all_filters($id . '_attribute');
		remove_all_filters(preg_replace('#(\[|\])#','',$id) . '_attribute');

	}// Method


	/* Method
	_________________________
	*/
	public static function __add_attribute($id,$attribute,$value)
	{
		/**
			[ORIGINAL]
				beans_add_attribute()
				https://www.getbeans.io/code-reference/functions/beans_add_attribute/
			@since 1.5.0
				Return the object.
			@access (public)
				Add a value to an existing attribute or add a new attribute.
				This function must be called before the targeted markup is called.
				The "data-markup-id" is added as a HTML attribute if the development mode is enabled.
				This makes it very easy to find the content ID when inspecting an element in a web browser.
			@param (string) $id
				The markup ID.
			@param (string) $attribute
				Name of the HTML attribute.
			@param (string) $value
				Value of the HTML attribute.
				If set to '' will display the attribute value as empty (e.g. class="").
				 - Setting it to 'FALSE'
					will only display the attribute name (e.g. data-example).
				 - Setting it to 'NULL'
					will not display anything.
			@return (_Beans_Attribute)
			@reference
				[Plugin]/api/html/attribute.php
		*/
		$attribute = new _beans_attribute_html($id,$attribute,$value);
		return $attribute->init('add');

	}// Method


	/* Method
	_________________________
	*/
	public static function __replace_attribute($id,$attribute,$value,$new_value = NULL)
	{
		/**
			[ORIGINAL]
				beans_replace_attribute()
				https://www.getbeans.io/code-reference/functions/beans_replace_attribute/
			@since 1.5.0
				Return the object.
				Allows replacement of all values.
			@access (public)
				Replace the attribute's value.
				If the attribute does not exist, it is added with the new value.
				This function must be called before the targeted markup is called.
				The "data-markup-id" is added as a HTML attribute if the development mode is enabled.
				This makes it very easy to find the content ID when inspecting an element in a web browser.
			@param (string) $id
				The markup ID.
			@param (string) $attribute
				Name of the HTML attribute to target.
			@param (string) $value
				Value of the HTML attribute to be replaced.
				 - Setting it to an empty (i.e. empty string, FALSE, or NULL) replaces all of the values for this attribute.
			@param (string)|(null) $new_value
				[Optional]
				Replacement (new) value of the HTML attribute.
				 - Setting it to an empty string ('') or NULL will remove the $value (e.g. class="").
				 - Setting it to 'FALSE', the browser will display only the attribute name (e.g. data-example).
			@return (_Beans_Attribute)
			@reference
				[Plugin]/api/html/attribute.php
		*/
		$attribute = new _beans_attribute_html($id,$attribute,$value,$new_value);
		return $attribute->init('replace');

	}// Method


	/* Method
	_________________________
	*/
	public static function __remove_attribute($id,$attribute,$value = NULL)
	{
		/**
			[ORIGINAL]
				beans_remove_attribute()
				https://www.getbeans.io/code-reference/functions/beans_remove_attribute/
			@since 1.5.0
				Return the object.
			@access (public)
				Remove a specific value from the attribute or remove the entire attribute.
				This function must be called before the targeted markup is called.
				The "data-markup-id" is added as a HTML attribute if the development mode is enabled.
				This makes it very easy to find the content ID when inspecting an element in a web browser.
			@param (string) $id
				The markup ID.
			@param (string) $attribute
				Name of the HTML attribute to target.
			@param (string)|(null) $value
				[Optional]
				The attribute value to remove.
				Set it to 'FALSE' or NULL to completely remove the attribute.
			@return (_Beans_Attribute)
			@reference
				[Plugin]/api/html/attribute.php
		*/
		$attribute = new _beans_attribute_html($id,$attribute,$value,'');
		return $attribute->init('remove');

	}// Method


	/**
		[ORIGINAL]
			_beans_is_html_dev_mode()
		@since 1.5.0
		@access (public)
			Check if development mode is enabled taking in consideration legacy constant.
		@return (bool)
		@reference
			[Plugin]/include/constant.php
	*/
	public static function __is_dev_mode()
	{
		if(defined('BEANS_EXTENSION_HTML_DEV_MODE')){
			return (bool)BEANS_EXTENSION_HTML_DEV_MODE;
		}
		return (bool)get_option('beans_extension_dev_mode',FALSE);

	}// Method


}// Class
endif;
_beans_html::__get_instance();
