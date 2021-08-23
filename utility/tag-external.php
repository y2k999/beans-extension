<?php
/**
 * A set of tools to ease building applications.
 * @package Beans_Extension
 * @license GPL3.0+
 * @since 1.0.1
*/

/**
 * Inspired by Beans Framework WordPress Theme
 * @link https://www.getbeans.io
 * @author Thierry Muller
*/


/* Prepare
______________________________
*/

// If this file is called directly,abort.
if(!defined('WPINC')){die;}


/* Exec
______________________________
*/
/**
 * @since 1.0.1
 * 	Custom template tags(functions) for the Beans Framework.
 * @reference (Beans)
 * 	https://www.getbeans.io/code-reference/
*/

	/* Action
	_________________________
	*/
	if(function_exists('beans_add_action') === FALSE) :
	function beans_add_action($id,$hook,$callback,$priority = 10,$args = 1)
	{
		/**
		 * @reference (Beans)
		 * 	Hooks a function on to a specific action.
		 * 	https://www.getbeans.io/code-reference/functions/beans_add_action/
		*/
		return \Beans_Extension\_beans_action::__add_action($id,$hook,$callback,$priority,$args);
	}// Method
	endif;


	if(function_exists('beans_add_smart_action') === FALSE) :
	function beans_add_smart_action($hook,$callback,$priority = 10,$args = 1)
	{
		/**
		 * @reference (Beans)
		 * 	Set beans_add_action() using the callback argument as the action ID.
		 * 	https://www.getbeans.io/code-reference/functions/beans_add_smart_action/
		*/
		return \Beans_Extension\_beans_action::__smart_action($hook,$callback,$priority,$args);
	}// Method
	endif;


	if(function_exists('beans_modify_action') === FALSE) :
	function beans_modify_action($id,$hook = NULL,$callback = NULL,$priority = NULL,$args = NULL)
	{
		/**
		 * @reference (Beans)
		 * 	Modify an action.
		 * 	https://www.getbeans.io/code-reference/functions/beans_modify_action/
		*/
		return \Beans_Extension\_beans_action::__modify_action($id,$hook,$callback,$priority,$args);
	}// Method
	endif;


	if(function_exists('beans_modify_action_hook') === FALSE) :
	function beans_modify_action_hook($id,$hook)
	{
		/**
		 * @reference (Beans)
		 * 	Modify an action hook.
		 * 	https://www.getbeans.io/code-reference/functions/beans_modify_action_hook/
		*/
		return \Beans_Extension\_beans_action::__modify_hook($id,$hook);
	}// Method
	endif;


	if(function_exists('beans_modify_action_callback') === FALSE) :
	function beans_modify_action_callback($id,$callback)
	{
		/**
		 * @reference (Beans)
		 * 	Modify an action callback.
		 * 	https://www.getbeans.io/code-reference/functions/beans_modify_action_callback/
		*/
		return \Beans_Extension\_beans_action::__modify_callback($id,$callback);
	}// Method
	endif;


	if(function_exists('beans_modify_action_priority') === FALSE) :
	function beans_modify_action_priority($id,$priority)
	{
		/**
		 * @reference (Beans)
		 * 	Modify an action priority.
		 * 	https://www.getbeans.io/code-reference/functions/beans_modify_action_priority/
		*/
		return \Beans_Extension\_beans_action::__modify_priority($id,$priority);
	}// Method
	endif;


	if(function_exists('beans_modify_action_arguments') === FALSE) :
	function beans_modify_action_arguments($id,$args)
	{
		/**
		 * @reference (Beans)
		 * 	Modify an action arguments.
		 * 	https://www.getbeans.io/code-reference/functions/beans_modify_action_arguments/
		*/
		return \Beans_Extension\_beans_action::__modify_args($id,$args);
	}// Method
	endif;


	if(function_exists('beans_replace_action') === FALSE) :
	function beans_replace_action($id,$hook = NULL,$callback = NULL,$priority = NULL,$args = NULL)
	{
		/**
		 * @reference (Beans)
		 * 	Replace an action.
		 * 	https://www.getbeans.io/code-reference/functions/beans_replace_action/
		*/
		return \Beans_Extension\_beans_action::__replace_action($id,$hook,$callback,$priority,$args);
	}// Method
	endif;


	if(function_exists('beans_replace_action_hook') === FALSE) :
	function beans_replace_action_hook($id,$hook)
	{
		/**
		 * @reference (Beans)
		 * 	Replace an action hook.
		 * 	https://www.getbeans.io/code-reference/functions/beans_replace_action_hook/
		*/
		return \Beans_Extension\_beans_action::__replace_hook($id,$hook);
	}// Method
	endif;


	if(function_exists('beans_replace_action_callback') === FALSE) :
	function beans_replace_action_callback($id,$callback)
	{
		/**
		 * @reference (Beans)
		 * 	Replace an action callback.
		 * 	https://www.getbeans.io/code-reference/functions/beans_replace_action_callback/
		*/
		return \Beans_Extension\_beans_action::__replace_callback($id,$callback);
	}// Method
	endif;


	if(function_exists('beans_replace_action_priority') === FALSE) :
	function beans_replace_action_priority($id,$priority)
	{
		/**
		 * @reference (Beans)
		 * 	Replace an action priority.
		 * 	https://www.getbeans.io/code-reference/functions/beans_replace_action_priority/
		*/
		return \Beans_Extension\_beans_action::__replace_priority($id,$priority);
	}// Method
	endif;


	if(function_exists('beans_replace_action_arguments') === FALSE) :
	function beans_replace_action_arguments($id,$args)
	{
		/**
		 * @reference (Beans)
		 * 	Replace an action argument.
		 * 	https://www.getbeans.io/code-reference/functions/beans_replace_action_arguments/
		*/
		return \Beans_Extension\_beans_action::__replace_args($id,$args);
	}// Method
	endif;


	if(function_exists('beans_remove_action') === FALSE) :
	function beans_remove_action($id)
	{
		/**
		 * @reference (Beans)
		 * 	Remove an action.
		 * 	https://www.getbeans.io/code-reference/functions/beans_remove_action/
		*/
		return \Beans_Extension\_beans_action::__remove_action($id);
	}// Method
	endif;


	if(function_exists('beans_reset_action') === FALSE) :
	function beans_reset_action($id)
	{
		/**
		 * @reference (Beans)
		 * 	Reset an action.
		 * 	https://www.getbeans.io/code-reference/functions/beans_reset_action/
		*/
		return \Beans_Extension\_beans_action::__reset_action($id);
	}// Method
	endif;


	/* Compiler
	_________________________
	*/
	if(function_exists('beans_compile_css_fragments') === FALSE) :
	function beans_compile_css_fragments($id,$fragments,$args = array())
	{
		/**
		 * @reference (Beans)
		 * 	Compile CSS fragments and enqueue compiled file.
		 * 	https://www.getbeans.io/code-reference/functions/beans_compile_css_fragments/
		*/
		\Beans_Extension\_beans_compiler::__fragment_css($id,$fragments,$args);
	}// Method
	endif;


	if(function_exists('beans_compile_less_fragments') === FALSE) :
	function beans_compile_less_fragments($id,$fragments,$args = array())
	{
		/**
		 * @reference (Beans)
		 * 	Compile LESS fragments, convert to CSS and enqueue compiled file.
		 * 	https://www.getbeans.io/code-reference/functions/beans_compile_less_fragments/
		*/
		\Beans_Extension\_beans_compiler::__fragment_less($id,$fragments,$args);
	}// Method
	endif;


	if(function_exists('beans_compile_js_fragments') === FALSE) :
	function beans_compile_js_fragments($id,$fragments,$args = array())
	{
		/**
		 * @reference (Beans)
		 * 	Compile JS fragments and enqueue compiled file.
		 * 	https://www.getbeans.io/code-reference/functions/beans_compile_js_fragments/
		*/
		\Beans_Extension\_beans_compiler::__fragment_js($id,$fragments,$args);
	}// Method
	endif;


	if(function_exists('beans_compiler_add_fragment') === FALSE) :
	function beans_compiler_add_fragment($id,$fragments,$format)
	{
		/**
		 * @reference (Beans)
		 * 	Add CSS, LESS or JS fragments to a compiler.
		 * 	https://www.getbeans.io/code-reference/functions/beans_compiler_add_fragment/
		*/
		\Beans_Extension\_beans_compiler::__add_fragment($id,$fragments,$format);
	}// Method
	endif;


	if(function_exists('beans_flush_compiler') === FALSE) :
	function beans_flush_compiler($id,$file_format = FALSE,$admin = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Flush cached compiler files.
		 * 	https://www.getbeans.io/code-reference/functions/beans_flush_compiler/
		*/
		\Beans_Extension\_beans_compiler::__flush_cache($id,$file_format,$admin);
	}// Method
	endif;


	if(function_exists('beans_flush_admin_compiler') === FALSE) :
	function beans_flush_admin_compiler($id,$file_format = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Flush admin cached compiler files.
		 * 	https://www.getbeans.io/code-reference/functions/beans_flush_admin_compiler/
		*/
		\Beans_Extension\_beans_compiler::__flush_admin_cache($id,$file_format);
	}// Method
	endif;


	if(function_exists('beans_get_compiler_dir') === FALSE) :
	function beans_get_compiler_dir($is_admin = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Get beans compiler directory.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_compiler_dir/
		*/
		return \Beans_Extension\_beans_compiler::__get_directory($is_admin);
	}// Method
	endif;


	if(function_exists('beans_get_compiler_url') === FALSE) :
	function beans_get_compiler_url($is_admin = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Get beans compiler url.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_compiler_url/
		*/
		return \Beans_Extension\_beans_compiler::__get_url($is_admin);
	}// Method
	endif;


	/* Customizer
	_________________________
	*/
	if(function_exists('beans_register_wp_customize_options') === FALSE) :
	function beans_register_wp_customize_options(array $fields,$section,$args = array())
	{
		/**
		 * @reference (Beans)
		 * 	Register WP Customize Options.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_wp_customize_options/
		*/
		return \Beans_Extension\_beans_customizer::__register($fields,$section,$args);
	}// Method
	endif;


	/* Field
	_________________________
	*/
	if(function_exists('beans_register_fields') === FALSE) :
	function beans_register_fields(array $fields,$context,$section)
	{
		/**
		 * @reference (Beans)
		 * 	Register fields.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_fields/
		*/
		return \Beans_Extension\_beans_field::__register($fields,$context,$section);
	}// Method
	endif;


	if(function_exists('beans_get_fields') === FALSE) :
	function beans_get_fields($context,$section = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Get registered fields.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_fields/
		*/
		return \Beans_Extension\_beans_field::__get_setting($context,$section);
	}// Method
	endif;


	if(function_exists('beans_field') === FALSE) :
	function beans_field(array $field)
	{
		/**
		 * @reference (Beans)
		 * 	Echo a field.
		 * 	https://www.getbeans.io/code-reference/functions/beans_field/
		*/
		\Beans_Extension\_beans_field::__render($field);
	}// Method
	endif;


	/* Filter
	_________________________
	*/
	if(function_exists('beans_add_filter') === FALSE) :
	function beans_add_filter($hook,$callback_or_value,$priority = 10,$args = 1)
	{
		/**
		 * @reference (Beans)
		 * 	Hooks a function or method to a specific filter action.
		 * 	https://www.getbeans.io/code-reference/functions/beans_add_filter/
		*/
		return \Beans_Extension\_beans_filter::__add_filter($hook,$callback_or_value,$priority,$args);
	}// Method
	endif;


	if(function_exists('beans_apply_filters') === FALSE) :
	function beans_apply_filters($id,$value)
	{
		/**
		 * @reference (Beans)
		 * 	Call the functions added to a filter hook.
		 * 	https://www.getbeans.io/code-reference/functions/beans_apply_filters/
		*/
		return \Beans_Extension\_beans_filter::__apply_filter($id,$value);
	}// Method
	endif;


	if(function_exists('beans_has_filters') === FALSE) :
	function beans_has_filters($id,$callback = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Check if any filter has been registered for a hook.
		 * 	https://www.getbeans.io/code-reference/functions/beans_has_filters/
		*/
		return \Beans_Extension\_beans_filter::__has_filter($id,$callback);
	}// Method
	endif;


	/* Html
	_________________________
	*/
	if(function_exists('beans_output') === FALSE) :
	function beans_output($id,$output)
	{
		/**
		 * @reference (Beans)
		 * 	Register output by ID.
		 * 	https://www.getbeans.io/code-reference/functions/beans_output/
		*/
		return \Beans_Extension\_beans_html::__output($id,$output);
	}// Method
	endif;


	if(function_exists('beans_output_e') === FALSE) :
	function beans_output_e($id,$output)
	{
		/**
		 * @reference (Beans)
		 * 	Echo output registered by ID.
		 * 	https://www.getbeans.io/code-reference/functions/beans_output_e/
		*/
		\Beans_Extension\_beans_html::__output_e($id,$output);
	}// Method
	endif;


	if(function_exists('beans_remove_output') === FALSE) :
	function beans_remove_output($id)
	{
		/**
		 * @reference (Beans)
		 * 	Remove output.
		 * 	https://www.getbeans.io/code-reference/functions/beans_remove_output/
		*/
		return \Beans_Extension\_beans_html::__remove_output($id);
	}// Method
	endif;


	if(function_exists('beans_open_markup') === FALSE) :
	function beans_open_markup($id,$tag,$attributes = array())
	{
		/**
		 * @reference (Beans)
		 * 	Register open markup and attributes by ID.
		 * 	https://www.getbeans.io/code-reference/functions/beans_open_markup/
		*/
		return \Beans_Extension\_beans_html::__open_markup($id,$tag,$attributes);
	}// Method
	endif;


	if(function_exists('beans_open_markup_e') === FALSE) :
	function beans_open_markup_e($id,$tag,$attributes = array())
	{
		/**
		 * @reference (Beans)
		 * 	Echo open markup and attributes registered by ID.
		 * 	https://www.getbeans.io/code-reference/functions/beans_open_markup_e/
		*/
		\Beans_Extension\_beans_html::__open_markup_e($id,$tag,$attributes);
	}// Method
	endif;


	if(function_exists('beans_selfclose_markup') === FALSE) :
	function beans_selfclose_markup($id,$tag,$attributes = array())
	{
		/**
		 * @reference (Beans)
		 * 	Register self-close markup and attributes by ID.
		 * 	https://www.getbeans.io/code-reference/functions/beans_selfclose_markup/
		*/
		return \Beans_Extension\_beans_html::__selfclose_markup($id,$tag,$attributes);
	}// Method
	endif;


	if(function_exists('beans_selfclose_markup_e') === FALSE) :
	function beans_selfclose_markup_e($id,$tag,$attributes = array())
	{
		/**
		 * @reference (Beans)
		 * 	Echo self-close markup and attributes registered by ID.
		 * 	https://www.getbeans.io/code-reference/functions/beans_selfclose_markup_e/
		*/
		\Beans_Extension\_beans_html::__selfclose_markup_e($id,$tag,$attributes);
	}// Method
	endif;


	if(function_exists('beans_close_markup') === FALSE) :
	function beans_close_markup($id,$tag)
	{
		/**
		 * @reference (Beans)
		 * 	Register close markup by ID.
		 * 	https://www.getbeans.io/code-reference/functions/beans_close_markup/
		*/
		return \Beans_Extension\_beans_html::__close_markup($id,$tag);
	}// Method
	endif;


	if(function_exists('beans_close_markup_e') === FALSE) :
	function beans_close_markup_e($id,$tag)
	{
		/**
		 * @reference (Beans)
		 * 	Echo close markup registered by ID.
		 * 	https://www.getbeans.io/code-reference/functions/beans_close_markup_e/
		*/
		\Beans_Extension\_beans_html::__close_markup_e($id,$tag);
	}// Method
	endif;


	if(function_exists('beans_modify_markup') === FALSE) :
	function beans_modify_markup($id,$markup,$priority = 10,$args = 1)
	{
		/**
		 * @reference (Beans)
		 * 	Modify opening and closing HTML tag. Also works for self-closed markup.
		 * 	https://www.getbeans.io/code-reference/functions/beans_modify_markup/
		*/
		return \Beans_Extension\_beans_html::__modify_markup($id,$markup,$priority,$args);
	}// Method
	endif;


	if(function_exists('beans_remove_markup') === FALSE) :
	function beans_remove_markup($id,$remove_actions = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Remove markup.
		 * 	https://www.getbeans.io/code-reference/functions/beans_remove_markup/
		*/
		return \Beans_Extension\_beans_html::__remove_markup($id,$remove_actions);
	}// Method
	endif;


	if(function_exists('beans_reset_markup') === FALSE) :
	function beans_reset_markup($id)
	{
		/**
		 * @reference (Beans)
		 * 	Reset markup.
		 * 	https://www.getbeans.io/code-reference/functions/beans_reset_markup/
		*/
		\Beans_Extension\_beans_html::__reset_markup($id);
	}// Method
	endif;


	if(function_exists('beans_wrap_markup') === FALSE) :
	function beans_wrap_markup($id,$new_id,$tag,$attributes = array())
	{
		/**
		 * @reference (Beans)
		 * 	Wrap markup.
		 * 	https://www.getbeans.io/code-reference/functions/beans_wrap_markup/
		*/
		return \Beans_Extension\_beans_html::__wrap_markup($id,$new_id,$tag,$attributes);
	}// Method
	endif;


	if(function_exists('beans_wrap_inner_markup') === FALSE) :
	function beans_wrap_inner_markup($id,$new_id,$tag,$attributes = array())
	{
		/**
		 * @reference (Beans)
		 * 	Wrap markup inner content.
		 * 	https://www.getbeans.io/code-reference/functions/beans_wrap_inner_markup/
		*/
		return \Beans_Extension\_beans_html::__wrap_inner_markup($id,$new_id,$tag,$attributes);
	}// Method
	endif;


	if(function_exists('beans_add_attributes') === FALSE) :
	function beans_add_attributes($id,$attributes = array())
	{
		/**
		 * @reference (Beans)
		 * 	Register attributes by ID.
		 * 	https://www.getbeans.io/code-reference/functions/beans_add_attributes/
		*/
		return \Beans_Extension\_beans_html::__convert_attribute($id,$attributes);
	}// Method
	endif;


	if(function_exists('beans_esc_attributes') === FALSE) :
	function beans_esc_attributes($attributes)
	{
		/**
		 * @reference (Beans)
		 * 	Sanitize HTML attributes from array to string.
		 * 	https://www.getbeans.io/code-reference/functions/beans_esc_attributes/
		*/
		return \Beans_Extension\_beans_html::__esc_attribute($attributes);
	}// Method
	endif;


	if(function_exists('beans_reset_attributes') === FALSE) :
	function beans_reset_attributes($id)
	{
		/**
		 * @reference (Beans)
		 * 	Reset markup attributes.
		 * 	https://www.getbeans.io/code-reference/functions/beans_reset_attributes/
		*/
		\Beans_Extension\_beans_html::__reset_attribute($id);
	}// Method
	endif;


	if(function_exists('beans_add_attribute') === FALSE) :
	function beans_add_attribute($id,$attribute,$value)
	{
		/**
		 * @reference (Beans)
		 * 	Add attribute to markup.
		 * 	https://www.getbeans.io/code-reference/functions/beans_add_attribute/
		*/
		return \Beans_Extension\_beans_html::__add_attribute($id,$attribute,$value);
	}// Method
	endif;


	if(function_exists('beans_replace_attribute') === FALSE) :
	function beans_replace_attribute($id,$attribute,$value,$new_value = NULL)
	{
		/**
		 * @reference (Beans)
		 * 	Replace attribute to markup.
		 * 	https://www.getbeans.io/code-reference/functions/beans_replace_attribute/
		*/
		return \Beans_Extension\_beans_html::__replace_attribute($id,$attribute,$value,$new_value);
	}// Method
	endif;


	if(function_exists('beans_remove_attribute') === FALSE) :
	function beans_remove_attribute($id,$attribute,$value = NULL)
	{
		/**
		 * @reference (Beans)
		 * 	Remove markup attribute.
		 * 	https://www.getbeans.io/code-reference/functions/beans_remove_attribute/
		*/
		return \Beans_Extension\_beans_html::__remove_attribute($id,$attribute,$value);
	}// Method
	endif;


	if(function_exists('beans_build_skip_links') === FALSE) :
	function beans_build_skip_links()
	{
		/**
		 * @reference (Beans)
		 * 	[Plugin]/asset/accessibility.php
		*/
		\Beans_Extension\_beans_accessibility::__render();
	}// Method
	endif;


	/* Image
	_________________________
	*/
	if(function_exists('beans_edit_image') === FALSE) :
	function beans_edit_image($src,array $args,$output = 'STRING')
	{
		/**
		 * @reference (Beans)
		 * 	Edit image size and/or quality.
		 * 	https://www.getbeans.io/code-reference/functions/beans_edit_image/
		*/
		return \Beans_Extension\_beans_image::__configure($src,$args,$output);
	}// Method
	endif;


	if(function_exists('beans_get_post_attachment') === FALSE) :
	function beans_get_post_attachment($post_id,$size = 'full')
	{
		/**
		 * @reference (Beans)
		 * 	Get attachment data.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_post_attachment/
		*/
		return \Beans_Extension\_beans_image::__get_setting($post_id,$size);
	}// Method
	endif;


	if(function_exists('beans_edit_post_attachment') === FALSE) :
	function beans_edit_post_attachment($post_id,$args = array())
	{
		/**
		 * @reference (Beans)
		 * 	Edit post attachment.
		 * 	https://www.getbeans.io/code-reference/functions/beans_edit_post_attachment/
		*/
		return \Beans_Extension\_beans_image::__edit($post_id,$args);
	}// Method
	endif;


	if(function_exists('beans_get_images_dir') === FALSE) :
	function beans_get_images_dir()
	{
		/**
		 * @reference (Beans)
		 * 	Get edited images directory.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_images_dir/
		*/
		return \Beans_Extension\_beans_image::__get_directory();
	}// Method
	endif;


	/* Layout
	_________________________
	*/
	if(function_exists('beans_get_default_layout') === FALSE) :
	function beans_get_default_layout()
	{
		/**
		 * @reference (Beans)
		 * 	Filter the default layout id.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_default_layout/
		*/
		return \Beans_Extension\_beans_layout::__get_default();
	}// Method
	endif;


	if(function_exists('beans_get_layout') === FALSE) :
	function beans_get_layout()
	{
		/**
		 * @reference (Beans)
		 * 	Get the current layout.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_layout/
		*/
		return \Beans_Extension\_beans_layout::__get_current();
	}// Method
	endif;


	if(function_exists('beans_get_layout_class') === FALSE) :
	function beans_get_layout_class($id)
	{
		/**
		 * @reference (Beans)
		 * 	Get the current layout.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_layout_class/
		*/
		return \Beans_Extension\_beans_layout::__get_page_layout($id);
	}// Method
	endif;


	if(function_exists('beans_get_layouts_for_options') === FALSE) :
	function beans_get_layouts_for_options($add_default = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Generate layout elements used by Beans 'imageradio' option type.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_layouts_for_options/
		*/
		return \Beans_Extension\_beans_layout::__get_setting($add_default);
	}// Method
	endif;


	if(function_exists('beans_has_primary_sidebar') === FALSE) :
	function beans_has_primary_sidebar($layout)
	{
		/**
		 * @reference (Beans)
		 * 	Echo primary sidebar template part.
		 * 	https://www.getbeans.io/code-reference/functions/beans_sidebar_primary_template/
		*/
		return \Beans_Extension\_beans_layout::__has_primary_sidebar($layout);
	}// Method
	endif;


	if(function_exists('beans_has_secondary_sidebar') === FALSE) :
	function beans_has_secondary_sidebar($layout)
	{
		/**
		 * @reference (Beans)
		 * 	Echo secondary sidebar template part.
		 * 	https://www.getbeans.io/code-reference/functions/beans_sidebar_secondary_template/
		*/
		return \Beans_Extension\_beans_layout::__has_secondary_sidebar($layout);
	}// Method
	endif;


	if(function_exists('beans_set_sidebar_layout_callbacks') === FALSE) :
	function beans_set_sidebar_layout_callbacks()
	{
		/**
		 * @reference (Beans)
		 * 	Custom function of this plugin.
		 * 	https://github.com/Bowriverstudio/beans-frontend-framework-uikit3
		*/
		return \Beans_Extension\_beans_layout::__get_sidebar_layout_callback($layout);
	}// Method
	endif;


	if(function_exists('beans_get_reduced_grid') === FALSE) :
	function beans_get_reduced_grid(string $numberOfColumns_grid)
	{
		/**
		 * @reference (Beans)
		 * 	Custom function of this plugin.
		 * 	https://github.com/Bowriverstudio/beans-frontend-framework-uikit3
		*/
		return \Beans_Extension\_beans_layout::__get_reduced_grid($numberOfColumns_grid);
	}// Method
	endif;


	/* Option
	_________________________
	*/
	if(function_exists('beans_register_options') === FALSE) :
	function beans_register_options(array $fields,$menu_slug,$section,$args = array())
	{
		/**
		 * @reference (Beans)
		 * 	Register options.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_options/
		*/
		return \Beans_Extension\_beans_option::__register($fields,$menu_slug,$section,$args);
	}// Method
	endif;


	if(function_exists('beans_options') === FALSE) :
	function beans_options($menu_slug)
	{
		/**
		 * @reference (Beans)
		 * 	Echo the registered options.
		 * 	https://www.getbeans.io/code-reference/functions/beans_options/
		*/
		return \Beans_Extension\_beans_option::__render($menu_slug);
	}// Method
	endif;


	/* Post meta
	_________________________
	*/
	if(function_exists('beans_register_post_meta') === FALSE) :
	function beans_register_post_meta($fields,$conditions,$section,$args)
	{
		/**
		 * @reference (Beans)
		 * 	Register Post Meta.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_post_meta/
		*/
		return \Beans_Extension\_beans_post_meta::__register($fields,$conditions,$section,$args);
	}// Method
	endif;


	if(function_exists('beans_get_post_meta') === FALSE) :
	function beans_get_post_meta($meta_key,$default = FALSE,$post_id = '')
	{
		/**
		 * @reference (Beans)
		 * 	Get the current post meta value.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_post_meta/
		*/
		return \Beans_Extension\_beans_post_meta::__get_setting($meta_key,$default,$post_id);
	}// Method
	endif;


	/* Template
	_________________________
	*/
	if(function_exists('beans_load_document') === FALSE) :
	function beans_load_document()
	{
		/**
		 * @reference (Beans)
		 * 	Load the entire document.
		 * 	https://www.getbeans.io/code-reference/functions/beans_load_document/
		*/
		\Beans_Extension\_beans_template::__load_document();
	}// Method
	endif;


	if(function_exists('beans_load_default_template') === FALSE) :
	function beans_load_default_template($file,$path = '')
	{
		/**
		 * @reference (Beans)
		 * 	Load Beans secondary template file.
		 * 	https://www.getbeans.io/code-reference/functions/beans_load_default_template/
		*/
		return \Beans_Extension\_beans_template::__load_template($file,$path);
	}// Method
	endif;


	if(function_exists('beans_load_fragment_file') === FALSE) :
	function beans_load_fragment_file($fragment,$path = '')
	{
		/**
		 * @reference (Beans)
		 * 	Load fragment file.
		 * 	https://www.getbeans.io/code-reference/functions/beans_load_fragment_file/
		*/
		return \Beans_Extension\_beans_template::__load_fragment($fragment,$path);
	}// Method
	endif;


	if(function_exists('beans_comment_callback') === FALSE) :
	function beans_comment_callback($comment,array $args,$depth)
	{
		/**
		 * @reference (Beans)
		 * 	wp_list_comments callback function.
		 * 	https://www.getbeans.io/code-reference/functions/beans_comment_callback/
		*/
		\Beans_Extension\_beans_template::__comment_callback($comment,$args,$depth);
	}// Method
	endif;


	/* Term meta
	_________________________
	*/
	if(function_exists('beans_register_term_meta') === FALSE) :
	function beans_register_term_meta(array $fields,$taxonomies,$section)
	{
		/**
		 * @reference (Beans)
		 * 	Register Term Meta.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_term_meta/
		*/
		return \Beans_Extension\_beans_term_meta::__register($fields,$taxonomies,$section);
	}// Method
	endif;


	if(function_exists('beans_get_term_meta') === FALSE) :
	function beans_get_term_meta($field_id,$default = FALSE,$term_id = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Get the current term meta value.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_term_meta/
		*/
		return \Beans_Extension\_beans_term_meta::__get_setting($field_id,$default,$term_id);
	}// Method
	endif;


	/* Uikit
	_________________________
	*/
	if(function_exists('beans_uikit_enqueue_components') === FALSE) :
	function beans_uikit_enqueue_components($components,$type = 'core',$autoload = TRUE)
	{
		/**
		 * @reference (Beans)
		 * 	Enqueue UIkit components.
		 * 	https://www.getbeans.io/code-reference/functions/beans_uikit_enqueue_components/
		*/
		\Beans_Extension\_beans_uikit::__enqueue_component($components,$type,$autoload);
	}// Method
	endif;


	if(function_exists('beans_uikit_dequeue_components') === FALSE) :
	function beans_uikit_dequeue_components($components,$type = 'core')
	{
		/**
		 * @reference (Beans)
		 * 	Dequeue a UIkit theme.
		 * 	https://www.getbeans.io/code-reference/functions/beans_uikit_dequeue_theme/
		*/
		\Beans_Extension\_beans_uikit::__dequeue_component($components,$type);
	}// Method
	endif;


	if(function_exists('beans_uikit_register_theme') === FALSE) :
	function beans_uikit_register_theme($id,$path)
	{
		/**
		 * @reference (Beans)
		 * 	Register a UIkit theme.
		 * 	https://www.getbeans.io/code-reference/functions/beans_uikit_register_theme/
		*/
		return \Beans_Extension\_beans_uikit::__register_theme($id,$path);
	}// Method
	endif;


	if(function_exists('beans_uikit_enqueue_theme') === FALSE) :
	function beans_uikit_enqueue_theme($id,$path = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Enqueue a UIkit theme.
		 * 	https://www.getbeans.io/code-reference/functions/beans_uikit_enqueue_theme/
		*/
		return \Beans_Extension\_beans_uikit::__enqueue_theme($id,$path);
	}// Method
	endif;


	if(function_exists('beans_uikit_dequeue_theme') === FALSE) :
	function beans_uikit_dequeue_theme($id)
	{
		/**
		 * @reference (Beans)
		 * 	Dequeue a UIkit theme.
		 * 	https://www.getbeans.io/code-reference/functions/beans_uikit_dequeue_theme/
		*/
		\Beans_Extension\_beans_uikit::__dequeue_theme($id);
	}// Method
	endif;


	if(function_exists('beans_uikit_get_all_components') === FALSE) :
	function beans_uikit_get_all_components($type = 'core')
	{
		return \Beans_Extension\_beans_uikit::__get_all_component($type);
	}// Method
	endif;


	if(function_exists('beans_uikit_get_all_dependencies') === FALSE) :
	function beans_uikit_get_all_dependencies($components)
	{
		return \Beans_Extension\_beans_uikit::__get_all_dependency($components);
	}// Method
	endif;


	/* Widget
	_________________________
	*/
	if(function_exists('beans_register_widget_area') === FALSE) :
	function beans_register_widget_area($args = array())
	{
		/**
		 * @reference (Beans)
		 * 	Register a widget area.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_widget_area/
		*/
		return \Beans_Extension\_beans_widget::__register_widget_area($args);
	}// Method
	endif;


	if(function_exists('beans_deregister_widget_area') === FALSE) :
	function beans_deregister_widget_area($id)
	{
		/**
		 * @reference (Beans)
		 * 	Remove a registered widget area.
		 * 	https://www.getbeans.io/code-reference/functions/beans_deregister_widget_area/
		*/
		\Beans_Extension\_beans_widget::__deregister_widget_area($id);
	}// Method
	endif;


	if(function_exists('beans_is_active_widget_area') === FALSE) :
	function beans_is_active_widget_area($id)
	{
		/**
		 * @reference (Beans)
		 * 	Check whether a widget area is in use.
		 * 	https://www.getbeans.io/code-reference/functions/beans_is_active_widget_area/
		*/
		return \Beans_Extension\_beans_widget::__is_active_widget_area($id);
	}// Method
	endif;


	if(function_exists('beans_has_widget_area') === FALSE) :
	function beans_has_widget_area($id)
	{
		/**
		 * @reference (Beans)
		 * 	Check whether a widget area is registered.
		 * 	https://www.getbeans.io/code-reference/functions/beans_has_widget_area/
		*/
		return \Beans_Extension\_beans_widget::__has_widget_area($id);
	}// Method
	endif;


	if(function_exists('beans_get_widget_area_output') === FALSE) :
	function beans_get_widget_area_output($id)
	{
		return \Beans_Extension\_beans_widget::__render_widget_area($id);
	}// Method
	endif;


	if(function_exists('beans_get_widget_area') === FALSE) :
	function beans_get_widget_area($needle = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Retrieve data from the current widget area in use.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_widget_area/
		*/
		return \Beans_Extension\_beans_widget::__get_widget_area($needle);
	}// Method
	endif;


	if(function_exists('beans_widget_area_shortcodes') === FALSE) :
	function beans_widget_area_shortcodes($content)
	{
		/**
		 * @reference (Beans)
		 * 	Search content for shortcodes and filter shortcodes through their hooks.
		 * 	https://www.getbeans.io/code-reference/functions/beans_widget_area_shortcodes/
		*/
		return \Beans_Extension\_beans_widget::__widget_area_shortcode($content);
	}// Method
	endif;


	if(function_exists('beans_have_widgets') === FALSE) :
	function beans_have_widgets()
	{
		/**
		 * @reference (Beans)
		 * 	Whether there are more widgets available in the loop.
		 * 	https://www.getbeans.io/code-reference/functions/beans_have_widgets/
		*/
		return \Beans_Extension\_beans_widget::__has_widget();
	}// Method
	endif;


	if(function_exists('beans_setup_widget') === FALSE) :
	function beans_setup_widget()
	{
		/**
		 * @reference (Beans)
		 * 	Sets up the current widget.
		 * 	https://www.getbeans.io/code-reference/functions/beans_setup_widget/
		*/
		return \Beans_Extension\_beans_widget::__setup_widget();
	}// Method
	endif;


	if(function_exists('beans_get_widget') === FALSE) :
	function beans_get_widget($needle = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Retrieve data from the current widget in use.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_widget/
		*/
		return \Beans_Extension\_beans_widget::__get_widget($needle);
	}// Method
	endif;


	if(function_exists('beans_widget_shortcodes') === FALSE) :
	function beans_widget_shortcodes($content)
	{
		/**
		 * @reference (Beans)
		 * 	Search content for shortcodes and filter shortcodes through their hooks.
		 * 	https://www.getbeans.io/code-reference/functions/beans_widget_shortcodes/
		*/
		return \Beans_Extension\_beans_widget::__widget_shortcode($content);
	}// Method
	endif;


	/* Utility
	_________________________
	*/
	if(function_exists('beans_render_function') === FALSE) :
	function beans_render_function($callback,$param = '')
	{
		/**
		 * @reference (Beans)
		 * 	Calls function given by the first parameter and passes the remaining parameters as arguments.
		 * 	https://www.getbeans.io/code-reference/functions/beans_render_function/
		*/
		return \Beans_Extension\_beans_utility::__render_function($callback,$param);
	}// Method
	endif;


	if(function_exists('beans_render_function_array') === FALSE) :
	function beans_render_function_array($callback,$params = array())
	{
		/**
		 * @reference (Beans)
		 * 	Calls function given by the first parameter and passes the remaining parameters as arguments.
		 * 	https://www.getbeans.io/code-reference/functions/beans_render_function_array/
		*/
		return \Beans_Extension\_beans_utility::__render_function_array($callback,$params);
	}// Method
	endif;


	if(function_exists('beans_remove_dir') === FALSE) :
	function beans_remove_dir($dir_path)
	{
		/**
		 * @reference (Beans)
		 * 	Remove a directory and its files.
		 * 	https://www.getbeans.io/code-reference/functions/beans_remove_dir/
		*/
		return \Beans_Extension\_beans_utility::__remove_directory($dir_path);
	}// Method
	endif;


	if(function_exists('beans_scandir') === FALSE) :
	function beans_scandir($dir_path)
	{
		return \Beans_Extension\_beans_utility::__scan_directory($dir_path);
	}// Method
	endif;


	if(function_exists('beans_str_ends_with') === FALSE) :
	function beans_str_ends_with($haystack,$needles)
	{
		return \Beans_Extension\_beans_utility::__str_end_with($haystack,$needles);
	}// Method
	endif;


	if(function_exists('beans_str_starts_with') === FALSE) :
	function beans_str_starts_with($haystack,$needles)
	{
		return \Beans_Extension\_beans_utility::__str_start_with($haystack,$needles);
	}// Method
	endif;


	if(function_exists('beans_path_to_url') === FALSE) :
	function beans_path_to_url($path,$force_rebuild = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Convert internal path to a url.
		 * 	https://www.getbeans.io/code-reference/functions/beans_path_to_url/
		*/
		return \Beans_Extension\_beans_utility::__path_to_url($path,$force_rebuild);
	}// Method
	endif;


	if(function_exists('beans_url_to_path') === FALSE) :
	function beans_url_to_path($url,$force_rebuild = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Convert internal url to a path.
		 * 	https://www.getbeans.io/code-reference/functions/beans_url_to_path/
		*/
		return \Beans_Extension\_beans_utility::__url_to_path($url,$force_rebuild);
	}// Method
	endif;


	if(function_exists('beans_sanitize_path') === FALSE) :
	function beans_sanitize_path($path)
	{
		/**
		 * @reference (Beans)
		 * 	Sanitize path.
		 * 	https://www.getbeans.io/code-reference/functions/beans_sanitize_path/
		*/
		return \Beans_Extension\_beans_utility::__sanitize_path($path);
	}// Method
	endif;


	if(function_exists('beans_get') === FALSE) :
	function beans_get($needle,$haystack = FALSE,$default = NULL)
	{
		/**
		 * @reference (Beans)
		 * 	Get value from $_GET or defined $haystack.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get/
		*/
		return \Beans_Extension\_beans_utility::__get_global_value($needle,$haystack,$default);
	}// Method
	endif;


	if(function_exists('beans_post') === FALSE) :
	function beans_post($needle,$default = NULL)
	{
		/**
		 * @reference (Beans)
		 * 	Get value from $_POST.
		 * 	https://www.getbeans.io/code-reference/functions/beans_post/
		*/
		return \Beans_Extension\_beans_utility::__post_global_value($needle,$default);
	}// Method
	endif;


	if(function_exists('beans_get_or_post') === FALSE) :
	function beans_get_or_post($needle,$default = NULL)
	{
		/**
		 * @reference (Beans)
		 * 	Get value from $_GET or $_POST superglobals.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_or_post/
		*/
		return \Beans_Extension\_beans_utility::__get_or_post_global_value($needle,$default);
	}// Method
	endif;


	if(function_exists('beans_in_multi_array') === FALSE) :
	function beans_in_multi_array($needle,$haystack,$strict = FALSE)
	{
		/**
		 * @reference (Beans)
		 * 	Checks if a value exists in a multi-dimensional array.
		 * 	https://www.getbeans.io/code-reference/functions/beans_in_multi_array/
		*/
		return \Beans_Extension\_beans_utility::__in_multi_array($needle,$haystack,$strict);
	}// Method
	endif;


	if(function_exists('beans_multi_array_key_exists') === FALSE) :
	function beans_multi_array_key_exists($needle,array $haystack)
	{
		/**
		 * @reference (Beans)
		 * 	Checks if a key or index exists in a multi-dimensional array.
		 * 	https://www.getbeans.io/code-reference/functions/beans_multi_array_key_exists/
		*/
		return \Beans_Extension\_beans_utility::__multi_array_key_exist($needle,$haystack);
	}// Method
	endif;


	if(function_exists('beans_array_shortcodes') === FALSE) :
	function beans_array_shortcodes($content,$haystack)
	{
		/**
		 * @reference (Beans)
		 * 	Search content for shortcodes and filter shortcodes through their hooks.
		 * 	https://www.getbeans.io/code-reference/functions/beans_array_shortcodes/
		*/
		return \Beans_Extension\_beans_utility::__array_shortcode($content,$haystack);
	}// Method
	endif;


	if(function_exists('beans_admin_menu_position') === FALSE) :
	function beans_admin_menu_position($position)
	{
		/**
		 * @reference (Beans)
		 * 	Make sure the menu position is valid.
		 * 	https://www.getbeans.io/code-reference/functions/beans_admin_menu_position/
		*/
		return \Beans_Extension\_beans_utility::__admin_menu_position($position);
	}// Method
	endif;


	if(function_exists('beans_join_arrays') === FALSE) :
	function beans_join_arrays(array &$array1,array $array2)
	{
		\Beans_Extension\_beans_utility::__join_array($array1,$array2);
	}// Method
	endif;


	if(function_exists('beans_array_unique') === FALSE) :
	function beans_array_unique(array $array)
	{
		return \Beans_Extension\_beans_utility::__array_unique($array);
	}// Method
	endif;


	if(function_exists('beans_join_arrays_clean') === FALSE) :
	function beans_join_arrays_clean(array $array1,array $array2,$reindex = TRUE)
	{
		return \Beans_Extension\_beans_utility::__join_array_clean($array1,$array2,$reindex);
	}// Method
	endif;


	/* Component
	_________________________
	*/
	if(function_exists('beans_get_component_support') === FALSE) :
	function beans_get_component_support($feature)
	{
		return \Beans_Extension\_beans_component::__get_support($feature);
	}// Method
	endif;


	if(function_exists('beans_remove_api_component_support') === FALSE) :
	function beans_remove_api_component_support($feature)
	{
		return \Beans_Extension\_beans_component::__remove_support($feature);
	}// Method
	endif;


	/* Misc
	_________________________
	*/
	if(function_exists('beans_set_post_view') === FALSE) :
	function beans_set_post_view($post_id)
	{
		/**
		 * @param (int) $post_id
		 * 	WordPress post id.
		 * @reference (Beans)
		 * Custom function of this plugin for updating post view count.
		 * @reference
		 * 	[Plugin]/admin/tab/app/column.php
		*/
		\Beans_Extension\_beans_admin_column_app::__set_post_view($post_id);
	}// Method
	endif;
