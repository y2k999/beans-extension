<?php
/**
 * Build tab/group of admin page.
 * @package Beans_Extension
 * @license GPL3.0+
 * @since 1.0.1
*/

/**
 * Inspired by AE Admin Customizer WordPress Plugin
 * @link https://wordpress.org/plugins/ae-admin-customizer/
 * @author Allan Empalmado
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
if(class_exists('_beans_admin_preview_tab') === FALSE) :
class _beans_admin_preview_tab
{
/**
 * @since 1.0.1
 * 	Build the admin tab menu content.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	__render()
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
	public static function __render()
	{
		/**
			@access (public)
				Render Preview tab page.
			@return (void)
			@reference
				[Plugin]/admin/beans.php
				[Plugin]/admin/tab/general.php
		*/
		?>
		<div class="tab-content">
			<div class="postbox">
				<h2 class="bx-headline"><?php esc_html__('Theme Preview','beans-extension'); ?></h2>
				<div>
					<iframe id="bx-iframe-preview" class="bx-iframe-preview" src="<?php echo esc_url(home_url()); ?>"></iframe>
				</div>
			</div>
		</div><!-- .content -->

		<div class="tab-content" style="margin-top: 50px;">
			<form method="post" action="options.php" class="bx-form">
				<?php _beans_admin_general_tab::__render(); ?>
			</form>
		</div><!-- .content -->

		<?php 
	}// Method


}// Class
endif;
// new _beans_admin_preview_tab();
_beans_admin_preview_tab::__get_instance();
