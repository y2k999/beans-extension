<?php
/**
 * Handler for rendering the Beans fields.
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

	/**
		@access (public)
			Render the textarea field.
			https://www.getbeans.io/code-reference/functions/beans_field_textarea/
		@param (array) $field{
			For best practices,pass the array of data obtained using{@see beans_get_fields()}.
			@type (mixed) $value
				The field's current value.
			@type (string) $name
				The field's "name" value.
			@type (array) $attributes
				An array of attributes to add to the field.
				The array's key defines the attribute name and the array's value defines the attribute value.
				[Default] an empty array.
			@type (mixed) $default
				The default value.
				[Default] FALSE.
		}
		@return (void)
		@reference
			[Plugin]/api/action/beans.php
			[Plugin]/api/html/beans.php
	*/
	beans_add_smart_action('beans_extension_field_textarea',__NAMESPACE__ . '\beans_extension_field_textarea');
	function beans_extension_field_textarea(array $field)
	{
		printf(
			'<textarea id="%s" name="%s" %s>%s</textarea>',
			esc_attr($field['id']),
			esc_attr($field['name']),
			_beans_html::__esc_attribute($field['attribute']),
			/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaping is handled in the function. */
			esc_textarea($field['value'])
		);

	}// Method
