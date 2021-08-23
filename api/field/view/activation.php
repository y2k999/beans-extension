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
		@since1.5.0
			Moved the HTML to a view file.
			https://www.getbeans.io/code-reference/functions/beans_field_activation/
		@access (public)
			Render the activation field.
		@param (array) $field{
			For best practices, pass the array of data obtained using {@see beans_get_fields()}.
			@type (mixed) $value
				The field's current value.
			@type (string) $name
				The field's "name" value.
			@type (array) $attributes
				An array of attributes to add to the field.
				The array's key defines the attribute name and the array's value defines the attribute value.
				[Default] an empty array.
			@type (int) $default
				The default value.
		@return (void)
		@reference
			[Plugin]/api/action/beans.php
			[Plugin]/api/html/beans.php
	*/
	beans_add_smart_action('beans_extension_field_activation',__NAMESPACE__ . '\beans_extension_field_activation');
	function beans_extension_field_activation(array $field)
	{
		?><input type="hidden" value="0" name="<?php echo esc_attr($field['name']); ?>" />

		<input id="<?php echo esc_attr($field['id']); ?>" type="checkbox" name="<?php echo esc_attr($field['name']); ?>" value="1"<?php checked($field['value'],1); ?> <?php echo _beans_html::__esc_attribute($field['attribute']); ?> />
		<!-- phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaping is handled in the function. -->
		<label for="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html($field['label']); ?></label><?php

	}// Method
