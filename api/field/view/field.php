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
			Render the field's label.
		@param (array) $field{
			Array of data.
			@type (string) $label
				The field label.
				[Default]FALSE.
		}
		@return (void)
		@reference
			[Plugin]/utility/beans.php
			[Plugin]/api/action/beans.php
			[Plugin]/api/html/beans.php
	*/
	beans_add_smart_action('beans_extension_field_group_label',__NAMESPACE__ . '\beans_extension_field_label');
	beans_add_smart_action('beans_extension_field_wrap_prepend_markup',__NAMESPACE__ . '\beans_extension_field_label');
	function beans_extension_field_label(array $field)
	{
		// These field types do not use a label,as they are using fieldsets with legends.
		if(in_array($field['type'],array('radio','group','activation'),TRUE)){return;}

		$label = _beans_utility::__get_global_value('label',$field);
		if(!$label){return;}

		$id = 'beans_extension_field_label[_' . $field['id'] . "]";
		$tag = 'label';
		$args = array(
			'for' => $field['id']
		);

		_beans_html::__open_markup_e($id,$tag,$args);
			echo esc_html($field['label']);
		_beans_html::__close_markup_e($id,$tag);

	}// Method


	/**
		@since 1.5.0
			Moved the HTML to a view file.
		@access (public)
			Render the field's description.
		@param (array) $field{
			Array of data.
			@type (string) $description
				The field description.
				The description can be truncated using <!--more--> as a delimiter.
				[Default] false.
		}
		@return (void)
		@reference
			[Plugin]/utility/beans.php
			[Plugin]/api/action/beans.php
			[Plugin]/api/html/beans.php
	*/
	beans_add_smart_action('beans_extension_field_wrap_append_markup',__NAMESPACE__ . '\beans_extension_field_description');
	function beans_extension_field_description(array $field)
	{
		$description = _beans_utility::__get_global_value('description',$field);
		if(!$description){return;}

		/**
		 * @since 1.0.1
		 * 	Escape the description here.
		 * @reference (WP)
		 * 	Sanitizes content for allowed HTML tags for post content.
		 * 	https://developer.wordpress.org/reference/functions/wp_kses_post/
		*/
		$description = wp_kses_post($description);

		// If the description has <!--more-->,split it.
		if(preg_match('#<!--more-->#',$description,$matches)){
			list($description,$extended) = explode($matches[0],$description,2);
		}
		_beans_html::__open_markup_e('beans_extension_field_description[_' . $field['id'] . "]",'div',array('class' => 'bs-field-description'));
			/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- To optimize,escaping is handled above. */
			echo $description;

		if(isset($extended)){
			?><br />
			<a class="bs-read-more" href="#"><?php esc_html_e('More...','beans-extension'); ?></a>
			<div class="bs-extended-content" style="display: none;"><?php echo $extended; ?></div><?php
		}
		_beans_html::__close_markup_e('beans_extension_field_description[_' . $field['id'] . "]",'div');

	}// Method

