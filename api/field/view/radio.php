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
			https://www.getbeans.io/code-reference/functions/beans_field_radio/
		@access (public)
			Render the radio field.
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
			@type (mixed)$default
				The default value.
				[Default] false.
			@type (array) $options
				An array used to populate the radio options.
				The array's key defines the radio value.
				The array's value defines the radio's label,image source (src),or an array to define the image's src,alt,and screen text reader values.
			}
		@return (void)
		@reference
			[Plugin]/api/action/beans.php
			[Plugin]/api/html/beans.php
	*/
	beans_add_smart_action('beans_extension_field_radio',__NAMESPACE__ . '\beans_extension_field_radio');
	function beans_extension_field_radio(array $field)
	{
		if(empty($field['option'])){return;}

		$field['default'] = key($field['option']);
		?><fieldset class="bs-field-fieldset">
			<legend class="bs-field-legend"><?php echo esc_html($field['label']); ?></legend>
			<?php
			// Clean the field's ID prefix once before we start the loop.
			$id_prefix = esc_attr($field['id'] . '_');

			foreach($field['option'] as $value => $radio) :
				$is_image = __beans_extension_is_radio_image($radio);

				// Clean the value here to avoid calling esc_attr() again and again for the same value.
				$clean_value = esc_attr($value);
				$clean_id = $id_prefix . $clean_value;
				?>
				<label class="<?php echo $is_image ? 'bs-has-image' : ''; ?>" for="<?php echo $clean_id; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaped above. */ ?>">
					<?php
					if($is_image) :
						$image = __beans_extension_standardize_radio_image($value,$radio);
						?><span class="screen-reader-text"><?php echo esc_html($image['screen_reader_text']); ?></span>
						<img src="<?php echo esc_url($image['src']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
						<input id="<?php echo $clean_id; ?>" class="screen-reader-text" 	type="radio" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo $clean_value; ?>"<?php checked($value,$field['value'],1); ?><?php echo _beans_html::__esc_attribute($field['attribute']); ?> />
						<!-- phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- The variable is escaped above. beans_esc_attributes is escaped by the function. -->
					<?php endif; ?>

					<?php if(!$is_image) : ?>
						<input id="<?php echo $clean_id; ?>" type="radio" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo $clean_value; ?>"<?php checked($value,$field['value'],1); ?><?php echo _beans_html::__esc_attribute($field['attribute']); ?> /> 
						<!-- phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- The variable is escaped above. beans_esc_attributes is escaped by the function. -->
						<?php echo wp_kses_post($radio); ?>
					<?php endif; ?>
				</label>
			<?php endforeach; ?>
		</fieldset>

	<?php
	}// Method


	/**
		@since 1.5.0
		@access (public)
			Checks if the radio is an image.
		@param (string) | (array) $radio
			The given radio to check.
		@return (bool)
		@reference
			[Plugin]/utility/beans.php
	*/
	function __beans_extension_is_radio_image($radio)
	{
		if(is_array($radio)){
			return TRUE;
		}

		// Else,check the fallback.
		return in_array(
			_beans_utility::__get_global_value('extension',pathinfo($radio)),array(
				'jpg',
				'jpeg',
				'jpe',
				'gif',
				'png',
				'bmp',
				'tif',
				'tiff',
				'ico'
			),TRUE);

	}// Method


	/**
		@since 1.5.0
		@access (public)
			Standardize the radio image parameters.
		@param (string) $value
			Value for the radio.
		@param (string) | (array) $radio
			The given radio image.
		@return (array)
	*/
	function __beans_extension_standardize_radio_image($value,$radio)
	{
		// Format when only the image's src is provided.
		if(!is_array($radio)){
			return array(
				'src'	=> $radio,
				'alt' => esc_attr("Option for{$value}"),
				'screen_reader_text' => esc_attr("Option for{$value}"),
			);
		}
		$radio = array_merge(array(
			'src' => '',
			'alt' => '',
			'screen_reader_text' => '',
		),$radio);

		if($radio['screen_reader_text'] && $radio['alt']){
			return $radio;
		}

		// Use the "alt" attribute when the "screen_reader_text" is not set.
		if(!$radio['screen_reader_text'] && $radio['alt']){
			$radio['screen_reader_text'] = $radio['alt'];
			return $radio;
		}

		// Use the "screen_reader_text" attribute when the "alt" is not set.
		if(!$radio['alt'] && $radio['screen_reader_text']){
			$radio['alt'] = $radio['screen_reader_text'];
			return $radio;
		}

		// Set the default accessibility values.
		$radio['alt'] = esc_attr("Option for{$value}");
		$radio['screen_reader_text'] = esc_attr("Option for{$value}");

		return $radio;

	}// Method

