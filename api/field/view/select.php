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
		@since 1.5.0
			Moved the HTML to a view file.
			https://www.getbeans.io/code-reference/functions/beans_field_select/
		@access (public)
			Echo select field type.
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
			@type (array) $options
				An array used to populate the select options.
				The array key defines option value and the array value defines the option label.
		}
		@return (void)
		@reference
			[Plugin]/api/action/beans.php
			[Plugin]/api/html/beans.php
	*/
	beans_add_smart_action('beans_extension_field_select',__NAMESPACE__ . '\beans_extension_field_select');
	function beans_extension_field_select(array $field)
	{
		if(empty($field['option'])){return;}
		?><select id="<?php echo esc_attr($field['id']); ?>" name="<?php echo esc_attr($field['name']); ?>" <?php echo _beans_html::__esc_attribute($field['attribute']); ?>>
			<!-- phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaping is handled in the function. -->
			<?php foreach($field['option'] as $value => $label){ ?>
				<option value="<?php echo esc_attr($value); ?>"<?php selected($value,$field['value']); ?>>
					<?php echo esc_html($label); ?>
				</option>
			<?php } ?>
		</select>

	<?php
	}// Method
