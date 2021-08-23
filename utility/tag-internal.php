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
 * @reference (Beans)
 * 	Custom template tags(functions) for beans framework theme.
 * 	Internal Use only.
 * 	https://www.getbeans.io/code-reference/
*/

	/* Action
	_________________________
	*/
	if(function_exists('_beans_add_anonymous_action') === FALSE) :
	function _beans_add_anonymous_action($hook,array $callback,$priority = 10,$args = 1)
	{
		/**
		 * @since 1.0.1
		 * 	Add anonymous callback using a class since php 5.2 is still supported.
		*/
		return \Beans_Extension\_beans_action::__anonymous_action($hook,$callback,$priority,$args);
	}// Method
	endif;


	if(function_exists('_beans_render_action') === FALSE) :
	function _beans_render_action($hook)
	{
		/**
		 * @since 1.0.1
		 * 	Render action which can therefore be stored in a variable.
		*/
		return \Beans_Extension\_beans_action::__render_action($hook);
	}// Method
	endif;


	if(function_exists('_beans_get_action') === FALSE) :
	function _beans_get_action($id,$status)
	{
		/**
		 * @since 1.0.1
		 * 	Get the action's configuration for the given ID and status. Returns `false` if the action is not registered with Beans.
		*/
		return \Beans_Extension\_beans_action::__get_action($id,$status);
	}// Method
	endif;


	if(function_exists('_beans_set_action') === FALSE) :
	function _beans_set_action($id,$action,$status,$overwrite = FALSE)
	{
		/**
		 * @since 1.0.1
		 * 	Store the action's configuration for the given ID and status.
		*/
		return \Beans_Extension\_beans_action::__set_action($id,$action,$status,$overwrite);
	}// Method
	endif;


	if(function_exists('_beans_unset_action') === FALSE) :
	function _beans_unset_action($id,$status)
	{
		/**
		 * @since 1.0.1
		 * 	Unset the action's configuration for the given ID and status. Returns `false` if there are is no action registered with Beans actions for the given ID and status.
		*/
		return \Beans_Extension\_beans_action::__unset_action($id,$status);
	}// Method
	endif;


	if(function_exists('_beans_merge_action') === FALSE) :
	function _beans_merge_action($id,array $action,$status)
	{
		/**
		 * @since 1.0.1
		 * 	Merge the action's configuration and then store it for the given ID and status.
		*/
		return \Beans_Extension\_beans_action::__merge_action($id,$action,$status);
	}// Method
	endif;


	if(function_exists('_beans_get_current_action') === FALSE) :
	function _beans_get_current_action($id)
	{
		/**
		 * @since 1.0.1
		 * 	Get the current action, meaning get from the "added" and/or "modified" statuses.
		*/
		return \Beans_Extension\_beans_action::__get_current($id);
	}// Method
	endif;


	if(function_exists('_beans_build_action_array') === FALSE) :
	function _beans_build_action_array($hook = NULL,$callback = NULL,$priority = NULL,$args = NULL)
	{
		/**
		 * @since 1.0.1
		 * 	Build the action's array for only the valid given arguments.
		*/
		return \Beans_Extension\_beans_action::__array_filter($hook,$callback,$priority,$args);
	}// Method
	endif;


	if(function_exists('_beans_when_has_action_do_render') === FALSE) :
	function _beans_when_has_action_do_render(array $args,&$output = '')
	{
		/**
		 * @since 1.0.1
		 * 	Render action which can therefore be stored in a variable.
		*/
		return \Beans_Extension\_beans_action::__get_callback($args,$output);
	}// Method
	endif;


	if(function_exists('_beans_unique_action_id') === FALSE) :
	function _beans_unique_action_id($callback)
	{
		/**
		 * @since 1.0.1
		 * 	Make sure the action ID is unique.
		*/
		return \Beans_Extension\_beans_action::__unique_id($callback);
	}// Method
	endif;


	/* Compiler
	_________________________
	*/
	if(function_exists('_beans_compile_fragments') === FALSE) :
	function _beans_compile_fragments($id,$format,$fragments,array $args = array(),$is_script = FALSE)
	{
		/**
		 * @since 1.0.1
		 * 	Compile CSS fragments and enqueue compiled file.
		*/
		\Beans_Extension\_beans_compiler::__get_fragment($id,$format,$fragments,$args,$is_script);
	}// Method
	endif;


	if(function_exists('_beans_is_compiler_dev_mode') === FALSE) :
	function _beans_is_compiler_dev_mode()
	{
		/**
		 * @since 1.0.1
		 * 	Check if development mode is enabled.
		*/
		return \Beans_Extension\_beans_compiler::__is_dev_mode();
	}// Method
	endif;


	/* Field
	_________________________
	*/
	if(function_exists('_beans_pre_standardize_fields') === FALSE) :
	function _beans_pre_standardize_fields(array $fields)
	{
		/**
		 * @since 1.0.1
		 * 	Pre-standardize the fields by keying each field by its ID.
		*/
		return \Beans_Extension\_beans_field::__standardize($fields);
	}// Method
	endif;


	/* Filter
	_________________________
	*/
	if(function_exists('_beans_add_anonymous_filter') === FALSE) :
	function _beans_add_anonymous_filter($hook,$value,$priority = 10,$args = 1)
	{
		/**
		 * @since 1.0.1
		 * 	Hooks a callback (function or method) to a specific filter event.
		*/
		return \Beans_Extension\_beans_filter::__anonymous_filter($hook,$value,$priority,$args);
	}// Method
	endif;


	/* Html
	_________________________
	*/
	if(function_exists('_beans_is_html_dev_mode') === FALSE) :
	function _beans_is_html_dev_mode()
	{
		/**
		 * @since 1.0.1
		 * 	Register the output for the given ID.
		*/
		return \Beans_Extension\_beans_html::__is_dev_mode();
	}// Method
	endif;


	/* Layout
	_________________________
	*/
	if(function_exists('_beans_get_layout_classes') === FALSE) :
	function _beans_get_layout_classes(array $args)
	{
		/**
		 * @since 1.0.1
		 * 	Filter the layout class.
		*/
		return \Beans_Extension\_beans_layout::__get_attribute($args);
	}// Method
	endif;


	/* Post meta
	_________________________
	*/
	if(function_exists('_beans_is_post_meta_conditions') === FALSE) :
	function _beans_is_post_meta_conditions($conditions)
	{
		/**
		 * @since 1.0.1
		 * 	Check the current screen conditions.
		*/
		return \Beans_Extension\_beans_post_meta::__check_current_screen($conditions);
	}// Method
	endif;


	/* Term meta
	_________________________
	*/
	if(function_exists('_beans_is_admin_term') === FALSE) :
	function _beans_is_admin_term($taxonomies)
	{
		/**
		 * @since 1.0.1
		 * 	Check if the current screen is a given term.
		*/
		return \Beans_Extension\_beans_term_meta::__is_admin($taxonomies);
	}// Method
	endif;


	/* Uikit
	_________________________
	*/
	if(function_exists('_beans_uikit_autoload_dependencies') === FALSE) :
	function _beans_uikit_autoload_dependencies($components)
	{
		/**
		 * @since 1.0.1
		 * 	Autoload all the component dependencies.
		*/
		\Beans_Extension\_beans_uikit::__autoload_dependency($components);
	}// Method
	endif;


	if(function_exists('_beans_uikit_get_registered_theme') === FALSE) :
	function _beans_uikit_get_registered_theme($id)
	{
		/**
		 * @since 1.0.1
		 * 	Get the path for the given theme ID, if the theme is registered.
		*/
		return \Beans_Extension\_beans_uikit::__get_registered_theme($id);
	}// Method
	endif;


	/* Widget
	_________________________
	*/
	if(function_exists('_beans_prepare_widget_data') === FALSE) :
	function _beans_prepare_widget_data($id)
	{
		/**
		 * @since 1.0.1
		 * 	Setup widget global data.
		*/
		\Beans_Extension\_beans_widget::__prepare_widget($id);
	}// Method
	endif;


	if(function_exists('_beans_setup_widget_area') === FALSE) :
	function _beans_setup_widget_area($id)
	{
		/**
		 * @since 1.0.1
		 * 	Set up widget area global data.
		*/
		\Beans_Extension\_beans_widget::__setup_widget_area($id);
	}// Method
	endif;


	if(function_exists('_beans_reset_widget_area') === FALSE) :
	function _beans_reset_widget_area()
	{
		/**
		 * @since 1.0.1
		 * 	Reset widget area global data.
		*/
		\Beans_Extension\_beans_widget::__reset_widget_area();
	}// Method
	endif;


	if(function_exists('_beans_widget_area_subfilters') === FALSE) :
	function _beans_widget_area_subfilters()
	{
		/**
		 * @since 1.0.1
		 * 	Build widget area sub-filters.
		*/
		return \Beans_Extension\_beans_widget::__widget_area_subfilter();
	}// Method
	endif;


	if(function_exists('_beans_setup_widgets') === FALSE) :
	function _beans_setup_widgets($widget_area_content)
	{
		/**
		 * @since 1.0.1
		 * 	Setup widget area global widgets data.
		*/
		return \Beans_Extension\_beans_widget::__prepare_widget_area($widget_area_content);
	}// Method
	endif;


	if(function_exists('_beans_widget_subfilters') === FALSE) :
	function _beans_widget_subfilters()
	{
		/**
		 * @since 1.0.1
		 * 	Build widget sub-filters.
		*/
		return \Beans_Extension\_beans_widget::__widget_subfilter();
	}// Method
	endif;


	if(function_exists('_beans_reset_widget') === FALSE) :
	function _beans_reset_widget()
	{
		/**
		 * @since 1.0.1
		 * 	Reset widget global data.
		*/
		\Beans_Extension\_beans_widget::__reset_widget();
	}// Method
	endif;


	/* Utility
	_________________________
	*/
	if(function_exists('_beans_is_uri') === FALSE) :
	function _beans_is_uri($maybe_uri)
	{
		/**
		 * @since 1.0.1
		 * 	Convert internal path to a url.
		*/
		return \Beans_Extension\_beans_utility::__is_uri($maybe_uri);
	}// Method
	endif;


	if(function_exists('_beans_doing_ajax') === FALSE) :
	function _beans_doing_ajax()
	{
		/**
		 * @since 1.0.1
		 * 	Checks if WP is doing ajax.
		*/
		return \Beans_Extension\_beans_utility::__doing_ajax();
	}// Method
	endif;


	if(function_exists('_beans_doing_autosave') === FALSE) :
	function _beans_doing_autosave()
	{
		/**
		 * @since 1.0.1
		 * 	Checks if WP is doing an autosave.
		*/
		return \Beans_Extension\_beans_utility::__doing_autosave();
	}// Method
	endif;
