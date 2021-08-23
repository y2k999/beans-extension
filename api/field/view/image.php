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
			Enqueued the assets for the image field.
		@return (void)
		@reference
			[Plugin]/api/action/beans.php
			[Plugin]/include/constant.php
	*/
	beans_add_smart_action('beans_extension_field_enqueue_scripts_image',__NAMESPACE__ . '\beans_extension_field_image_assets');
	function beans_extension_field_image_asset()
	{
		wp_enqueue_media();
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script(
			'beans-field-media',
			BEANS_EXTENSION_API_URL['asset'] . 'js/media' . BEANS_EXTENSION_MIN_CSS . '.js',
			array('jquery'),
			BEANS_EXTENSION_VERSION
		);

	}// Method


	/**
		@since 1.5.0
			Moved the HTML to a view file.
			https://www.getbeans.io/code-reference/functions/beans_field_image/
		@access (public)
			Render the image field,which handles a single image or a gallery of images.
		@param (array) $field{
			For best practices,pass the array of data obtained using {@see beans_get_fields()}.
			@type (mixed) $value
				The image's or images' ID.
			@type (string) $name
				The field's "name" value.
			@type (array) $attributes
				An array of attributes to add to the field.
				The array's key defines the attribute name and the array's value defines the attribute value.
				[Default] an empty array.
			@type (mixed) $default
				The default value.
				[Default] false.
			@type (string) $is_multiple
				Set to true to enable multiple images (gallery).
				[Default] false.
			}
		@return (void)
		@reference
			[Plugin]/utility/beans.php
			[Plugin]/api/action/beans.php
	*/
	beans_add_smart_action('beans_extension_field_image',__NAMESPACE__ . '\beans_extension_field_image');
	function beans_extension_field_image(array $field)
	{
		$images = array_merge((array)$field['value'],array('placeholder'));
		$is_multiple = _beans_utility::__get_global_value('multiple',$field);
		$link_text = _n('Add Image','Add Images',($is_multiple ? 2 : 1),'beans-extension');

		// If this is a single image and it already exists,then hide the "add image" hyperlink.
		$hide_add_link = !$is_multiple && is_numeric($field['value']);

		// Render the view file.
		?><button class="bs-add-image button button-small" type="button" <?php isset($hide_add_link) && $hide_add_link ? 'style="display: none"' : ''; ?>>
			<?php echo esc_html($link_text); ?>
		</button>

		<input id="<?php echo esc_attr($field['id']); ?>" type="hidden" name="<?php echo esc_attr($field['name']); ?>" value="">

		<div class="bs-images-wrap" data-multiple="<?php echo esc_attr($is_multiple); ?>"><?php
			foreach($images as $image_id){
				// Skip this one if the ID is not set.
				if(!$image_id){continue;}
				$attributes = __beans_get_image_id_attributes($image_id,$field,$is_multiple);
				$image_url = __beans_get_image_url($image_id);
				$image_alt = $image_url ? __beans_get_image_alt($image_id) : '';
				?><div class="bs-image-wrap<?php 'placeholder' === $image_id ? ' bs-image-template' : ''; ?>">
					<!-- phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaping is handled in the function. -->
					<input <?php echo _beans_html::__esc_attribute($attributes); ?> />
					<img src="<?php echo $image_url ? esc_url($image_url) : ''; ?>" alt="<?php echo $image_alt ? esc_attr($image_alt) : ''; ?>">
					<div class="bs-toolbar">	<?php
						/**
						 * @since 1.5.0
						 * 	The image toolbar's dashicons' class attributes are deprecated in Beans 1.5.
						 * 	Instead,the .bs-button-{icon}class attributes are used and the icon is defined in the CSS via .bs-button-{icon}:before pseudo-element.
						 * 	The dashicons' class attributes remain in Beans 1.x for backwards compatibility for customs scripts or styling.
						 * 	However, these will be removed in Beans 2.0.
						 * @deprecated
						*/
						if($is_multiple) :
							?><button aria-label="<?php esc_attr_e('Manage Images','beans-extension'); ?>" type="button" class="button bs-button-menu dashicons dashicons-menu"></button>
						<?php endif; ?>
						<button aria-label="<?php esc_attr_e('Edit Image','beans-extension'); ?>" type="button" class="button bs-button-edit dashicons dashicons-edit"></button>
						<button aria-label="<?php esc_attr_e('Delete Image','beans-extension'); ?>" type="button" class="button bs-button-trash dashicons dashicons-post-trash"></button>
					</div>
				</div>
			<?php } ?>
		</div>

	<?php
	}// Method


	/**
		@since1.5.0
		@access (public)
			Get the Image ID's attributes.
		@param (string) $id
			The given image's ID.
		@param (array) $field
			The field's configuration parameters.
		@param (bool) $is_multiple
			Multiple flag.
		@return (array)
	*/
	function __beans_extension_get_image_id_attributes($id,array $field,$is_multiple)
	{
		$attributes = array_merge(
			array(
				'class' => 'image-id',
				'type' => 'hidden',
				// Return single value if not multiple.
				'name' => $is_multiple ? $field['name'] . '[]' : $field['name'],
				'value' => $id,
			),
			$field['attribute']
		);
		if('placeholder' === $id){
			$attributes = array_merge($attributes,array(
				'disabled' => 'disabled',
				'value' => FALSE,
			));
		}
		return $attributes;

	}// Method


	/**
		@since1.5.0
		@access (public)
			Get the image's URL.
			Retrieves an image to represent an attachment.
			https://developer.wordpress.org/reference/functions/wp_get_attachment_image_src/
		@param (mixed) $image_id
			The image's attachment ID.
		@return (string)|(void)
		@reference
			[Plugin]/utility/beans.php
	*/
	function __beans_extension_get_image_url($image_id)
	{
		$image_id = (int)$image_id;

		// If this is not a valid image ID,bail out.
		if($image_id < 1){return;}
		return _beans_utility::__get_global_value(0,wp_get_attachment_image_src($image_id,'thumbnail'));

	}// Method


	/**
		@since1.5.0
		@access (public)
			Get the image's alt description.
		@param (mixed) $image_id
			The image's attachment ID.
		@return (string)|(void)
	*/
	function __beans_extension_get_image_alt($image_id)
	{
		$image_id = (int)$image_id;

		// If this is not a valid image ID,bail out.
		if($image_id < 1){return;}

		$image_alt = get_post_meta($image_id,'_wp_attachment_image_alt',TRUE);

		// If this image does not an "alt" defined,return the default.
		if(!$image_alt){
			return esc_html__('Sorry,no description was given for this image.','beans-extension');
		}
		return $image_alt;

	}// Method
