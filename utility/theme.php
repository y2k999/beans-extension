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
 * 	Helper functions for WordPress theme to get/set settings of the Beans Framework.
 * @reference (Beans)
 * 	https://www.getbeans.io/code-reference/
*/

	/* Misc
	_________________________
	*/
	if(function_exists('beans_set_post_view') === FALSE) :
	function beans_set_post_view($post_id)
	{
		/**
		 * @access (Public)
		 * 	Count up the post view when the post was visited.
		 * 	You can use this function in single.php (asides the WP the_content() tag) in your theme.
		 * @param (int) $post_id
		 * 	WordPress post id.
		 * @reference
		 * 	[Plugin]/admin/tab/app/column.php
		 * 	[Windmill]/controller/structure/single.php
		*/
		if(!isset($post_id) || ($post_id === 0)){return;}

		\Beans_Extension\_beans_admin_column_app::__set_post_view($post_id);
	}// Method
	endif;


	/* Misc
	_________________________
	*/
	if(function_exists('beans_get_general_setting') === FALSE) :
	function beans_get_general_setting($needle = '')
	{
		/**
		 * @access (Public)
		 * 	Return the general tab settings of the Beans Extension plugin.
		 * 	 - Stop Beans Image API or not
		 * 	 - Stop Beans Widget API or not
		 * 	 - Stop Beans Customizer API or not
		 * 	 - Use Beans Legacy Layout or not
		 * 	 - Use Beans Accessibility (Skip to Link) HTML or not
		 * 	 - Use BEANS_TEMPLATES_PATH (Beans Template File Path) constant or not
		 * 	 - Use BEANS_STRUCTURE_PATH (Beans Structure File Path) constant or not
		 * 	 - Use BEANS_FRAGMENTS_PATH (Beans Fragment File Path) constant or not
		 * 	 - Select Uikit Version
		 * @param (string) $needle
		 * 	Name of setting.
		 * @reference
		 * 	[Plugin]/admin/tab/general.php
		 * 	[Windmill]/inc/env/enqueue.php
		 * 	[Windmill]/inc/utility/theme.php
		*/
		return \Beans_Extension\_beans_component::__get_setting('general',$needle);

	}// Method
	endif;


	/* Misc
	_________________________
	*/
	if(function_exists('beans_get_image_setting') === FALSE) :
	function beans_get_image_setting($needle = '')
	{
		/**
		 * @access (Public)
		 * 	Return the image tab settings of the Beans Extension plugin.
		 * 	 - Uploaded Author/Profile Image.
		 * 	 - Uploaded No Post Image.
		 * @param (string) $needle
		 * 	Name of setting.
		 * @reference
		 * 	[Plugin]/admin/tab/image.php
		 * 	[Windmill]/inc/customizer/default.php
		*/
		return \Beans_Extension\_beans_component::__get_setting('image',$needle);

	}// Method
	endif;


	/* Misc
	_________________________
	*/
	if(function_exists('beans_get_layout_setting') === FALSE) :
	function beans_get_layout_setting($needle = '')
	{
		/**
		 * @access (Public)
		 * 	Return the layout tab settings of the Beans Extension plugin.
		 * 	 - Display layouts on post meta screen or not.
		 * 	 - Display layouts on term meta screen or not.
		 * 	 - Layout for Single Posts(single.php)
		 * 	 - Layout for Single Pages(page.php)
		 * 	 - Layout for Blog Page(home.php)
		 * 	 - Layout for Archive Page(archive.php)
		 * @param (string) $needle
		 * 	Name of setting.
		 * @reference
		 * 	[Plugin]/admin/beans.php
		 * 	[Plugin]/admin/tab/layout.php
		 * 	[Windmill]/controller/template.php
		 * 	[Windmill]/controller/render/column.php
		*/
		return \Beans_Extension\_beans_component::__get_setting('layout',$needle);

	}// Method
	endif;

