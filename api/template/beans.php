<?php
/**
 * Define Beans API classes.
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
if(class_exists('_beans_template') === FALSE) :
class _beans_template
{
/**
 * @since 1.0.1
 * 	The Templates API allows to load Beans template files as well as loading the entire document.
 * 	Load and render the entire document (web page).
 * 	This function is the root of the Beans' framework hierarchy.
 * 	Therefore,when calling it,Beans runs,building the web page's HTML markup and rendering it out to the browser.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	beans_load_document()
 * 	beans_load_default_template()
 * 	beans_load_fragment_file()
 * 	beans_comment_callback()
*/


	/**
	 * Traits.
	*/
	use _trait_singleton;


	/* Constructor
	_________________________
	*/
	private function __construct()
	{
		/**
			@access (private)
				Class constructor.
				This is only called once, since the only way to instantiate this is with the get_instance() method in trait (singleton.php).
			@return (void)
			@reference
				[Plugin]/trait/singleton.php
		*/

	}// Method


	/* Method
	_________________________
	*/
	public static function __load_document()
	{
		/**
			[ORIGINAL]
				beans_load_document()
				https://www.getbeans.io/code-reference/functions/beans_load_document/
			@access (public)
				Load and render the entire document (web page).
				This function is the root of the Beans' framework hierarchy.
				Therefore,when calling it,Beans runs,building the web page's HTML markup and rendering it out to the browser.
				Here are some guidelines for calling this function:
				 - Call it from a primary template file,e.g. single.php,page.php,home.php,archive.php,etc.
				 - Do all modifications and customizations before calling this function.
				 - Put this function on the last line of code in the template file.
			@return (void)
		*/

		/**
		 * @reference (WP)
		 * 	Gets a WP_Theme object for a theme.
		 * 	https://developer.wordpress.org/reference/functions/wp_get_theme/
		*/
		if(in_array(wp_get_theme()->template,array('bex-uikit2','bex-uikit3'),TRUE)){
			/**
			 * @reference (Beans)
			 * 	Fires before the document is loaded.
			 * 	https://www.getbeans.io/code-reference/hooks/beans_before_load_document/
			*/
			do_action('beans_before_load_document');

			/**
			 * @reference (Beans)
			 * 	Fires when the document loads.
			 * 	This hook is the root of Beans's framework hierarchy. It all starts here!
			 * 	https://www.getbeans.io/code-reference/hooks/beans_load_document/
			*/
			do_action('beans_load_document');

			/**
			 * @reference (Beans)
			 * 	Fires after the document is loaded.
			 * 	https://www.getbeans.io/code-reference/hooks/beans_after_load_document/
			*/
			do_action('beans_after_load_document');
		}
		else{
			do_action('beans_extension_before_load_document');
			do_action('beans_extension_load_document');
			do_action('beans_extension_load_document');
		}

	}// Method


	/* Method
	_________________________
	*/
	public static function __load_template($file,$path = BEANS_EXTENSION_STRUCTURE_PATH)
	{
		/**
			[ORIGINAL]
				beans_load_default_template()
				https://www.getbeans.io/code-reference/functions/beans_load_default_template/
			@access (public)
				Loads a secondary template file.
				This function loads Beans's default template file.
				It must be called from a secondary template file(e.g. comments.php) and must be the last function to be called.
				All modifications must be done before calling this function.

				This includes modifying markup,attributes,fragments,etc.
				The default template files contain the hook on which the fragments are attached to.
				By passing this function will completely remove the default content.
			@param (string) $file
				The filename of the secondary template files. __FILE__ is usually to argument to pass.
			@return (bool)
				TRUE on success,FALSE on failure.
			@reference
				[Plugin]/include/constant.php
		*/
		$file = $path . basename($file);

		if(!file_exists($file)){
			return FALSE;
		}
		require_once $file;

		return TRUE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __load_fragment($fragment,$path = BEANS_EXTENSION_FRAGMENT_PATH)
	{
		/**
			[ORIGINAL]
				beans_load_fragment_file()
				https://www.getbeans.io/code-reference/functions/beans_load_fragment_file/
			@access (public)
				Load the fragment file.
				This function can be short-circuited using the filter event "beans_pre_load_fragment_".
			@param (string) $fragment
				The fragment to load. 
				This is its filename without the extension.
			@return (bool)
				True on success,false on failure.
			@reference
				[Plugin]/include/constant.php
		*/

		/**
		 * @since 1.0.1
		 * 	Filter to allow the child theme or plugin to short-circuit this function by passing back a `TRUE` or truthy value.
		 * 	The hook's name is "beans_pre_load_fragment_" + the fragment's filename (without its extension).
		 * 	For example,the header fragment's hook name is "beans_pre_load_fragment_header".
		 * 	https://www.getbeans.io/code-reference/hooks/beans_pre_load_fragment_slug/
		 * @param (bool)
		 * 	Set to `TRUE` to short-circuit this function.
		 * 	The default is `FALSE`.
		*/
		if(apply_filters('beans_pre_load_fragment_' . $fragment,FALSE)){
			return FALSE;
		}

		if(!file_exists($path . $fragment . '.php')){
			return FALSE;
		}
		require_once $path . $fragment . '.php';
		return TRUE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __comment_callback($comment,array $args,$depth)
	{
		/**
			[ORIGINAL]
				beans_comment_callback()
				https://www.getbeans.io/code-reference/functions/beans_comment_callback/
			@access (public)
				Render the current comment's HTML markup.
				This function is a callback that is registered to{@see wp_list_comments()}.
				It adds the args and depth to the global comment,renders the opening <li> tag,and fires the "beans_comment" event to render the comment.
			@see wp_list_comments()
				https://developer.wordpress.org/reference/functions/wp_list_comments/
			@param (WP_Comment) $comment
				Instance of the current comment,i.e. which is also the global comment.
				https://developer.wordpress.org/reference/classes/wp_comment/
			@param (array) $args
				Array of arguments.
			@param (int) $depth
				Depth of the comment in reference to its parents.
			@return (void)
		*/

		// To give us access,add the args and depth as public properties on the comment's global instance.
		global $comment;
		if(!empty($args)){
			$comment->args = $args;
		}
		if(isset($depth)){
			$comment->depth = $depth;
		}

		// Render the opening <li> tag.
		$comment_class = empty($args['has_children']) ? '' : 'parent';
		printf(
			'<li id="comment-%d" %s>',
			/**
			 * @reference (WP)
			 * 	Retrieves the comment ID of the current comment.
			 * 	https://developer.wordpress.org/reference/functions/get_comment_id/
			*/
			(int)get_comment_ID(),
			/**
			 * @reference (WP)
			 * 	Generates semantic classes for each comment element.
			 * 	https://developer.wordpress.org/reference/functions/comment_class/
			*/
			comment_class($comment_class,$comment,NULL,FALSE)
		);

		/**
		 * @reference (Beans)
		 * 	Fires in comment structural HTML.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_comment/
		*/
		if(in_array(wp_get_theme()->template,array('tm-beans'),TRUE)){
			// 	Render the comment's HTML markup.
			do_action('beans_comment');
		}
		else{
			do_action('beans_extension_comment');
		}
		// The </li> tag is intentionally omitted.

	}// Method


}// Class
endif;
// new _beans_template();
_beans_template::__get_instance();
