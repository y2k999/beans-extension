<?php
/**
 * Fired during plugin activation.
 * @package Beans_Extension
 * @license GPL3.0+
 * @since 1.0.1
*/

/**
 * Inspired by Beans Framework WordPress Theme
 * @link https://www.getbeans.io
 * @author Thierry Muller
*/
// namespace Beans_Extension;


/* Prepare
______________________________
*/

// If this file is called directly,abort.
if(!defined('WPINC')){die;}


/* Exec
______________________________
*/
if(class_exists('_beans_activator') === FALSE) :
class _beans_activator
{
/**
 * @since 1.0.1
 * 	Fired during plugin activation.
 * 	This class defines all code necessary to run during the plugin's activation.
 * 
 * [TOC]
 * 	__construct()
 * 	run()
*/


	/* Constructor
	_________________________
	*/
	public function __construct()
	{
		/**
			@access (public)
				Class Constructor.
			@return (void)
		*/
		$this->run();

	}// Method


	/* Method
	_________________________
	*/
	private function run()
	{
		/**
			@access (private)
				Set the activation hook for a plugin.
				https://developer.wordpress.org/reference/functions/register_activation_hook/
			@return (void)
		*/

		/**
		 * @reference (WP)
		 * 	Returns whether the current user has the specified capability.
		 * 	https://developer.wordpress.org/reference/functions/current_user_can/
		*/
		if(!current_user_can('activate_plugins')){return;}

		/**
		 * @since 1.0.1
		 * 	If Original Beans theme (tm-beans) is the active theme, bail out.
		 * @reference (WP)
		 * 	Gets a WP_Theme object for a theme.
		 * 	https://developer.wordpress.org/reference/functions/wp_get_theme/
		*/
		if(in_array(wp_get_theme()->template,array('tm-beans'),TRUE)){
			/**
			 * @reference (WP)
			 * 	Deactivate a single plugin or multiple plugins.
			 * 	https://developer.wordpress.org/reference/functions/deactivate_plugins/
			 * 	Gets the basename of a plugin.
			 * 	https://developer.wordpress.org/reference/functions/plugin_basename/
			*/
			deactivate_plugins(plugin_basename(dirname(__FILE__),1));

			/**
			 * @reference (WP)
			 * 	Retrieve the name of the current filter or action.
			 * 	https://developer.wordpress.org/reference/functions/current_filter/
			*/
			if(current_filter() !== 'switch_theme'){
				$message = __('Sorry, you can\'t activate this plugin when the <a href="https://www.getbeans.io" target="_blank">Original Beans theme (tm-beans)</a> framework is installed and a child theme is activated.','beans-extension');
				/**
				 * @reference (WP)
				 * 	Kills WordPress execution and displays HTML page with an error message.
				 * 	https://developer.wordpress.org/reference/functions/wp_die/
				 * 	Sanitizes content for allowed HTML tags for post content.
				 * 	https://developer.wordpress.org/reference/functions/wp_kses_post/
				*/
				wp_die(wp_kses_post($message));
			}
			return;
		}

	}// Method


}// Class
endif;
new _beans_activator();
