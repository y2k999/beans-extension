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
			Enqueued assets required by the beans slider field.
		@return (void)
		@reference
			[Plugin]/api/action/beans.php
	*/
	beans_add_smart_action('beans_extension_field_enqueue_scripts_slider',__NAMESPACE__ . '\beans_extension_field_slider_assets');
	function beans_extension_field_slider_asset()
	{
		wp_enqueue_script('jquery-ui-slider');

	}// Method


	/**
		@since 1.5.0
			Moved the HTML to a view file.
			https://www.getbeans.io/code-reference/functions/beans_field_slider/
		@access (public)
			Render the slider field.
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
			@type (int)|(float) $default
				The default value.
			@type (string) $min
				The slider's minimum value.
				[Default] 0.
			@type (string) $max
				The slider's maximum value.
				[Default] 100.
			@type (string) $interval
				The slider's interval.
				[Default] 1.
			@type (string) $unit
				The slider's units,which is displayed after the current value.
				[Default] NULL.
			}
		@return (void)
		@reference
			[Plugin]/api/action/beans.php
			[Plugin]/api/html/beans.php
	*/
	beans_add_smart_action('beans_extension_field_slider',__NAMESPACE__ . '\beans_extension_field_slider');
	function beans_extension_field_slider(array $field)
	{
		$defaults = array(
			'min' => 0,
			'max' => 100,
			'interval' => 1,
			'unit' => NULL,
		);
		$field = array_merge($defaults,$field);
		?><div class="bs-slider-wrap" slider_min="<?php echo (int)$field['min']; ?>" slider_max="<?php echo (int)$field['max']; ?>" slider_interval="<?php echo (int)$field['interval']; ?>">
			<input id="<?php echo esc_attr($field['id']); ?>" type="text" value="<?php echo esc_attr($field['value']); ?>" name="<?php echo esc_attr($field['name']); ?>" style="display: none;" <?php echo _beans_html::__esc_attribute($field['attribute']); ?>
			/>
			<!-- phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaping is handled in the function. -->
		</div>
		<span class="bs-slider-value"><?php echo esc_html($field['value']); ?></span>
		<?php if($field['unit']) : ?>
			<span class="bs-slider-unit"><?php echo esc_html($field['unit']); ?></span>
		<?php endif; ?>

	<?php
	}// Method
