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
if(class_exists('_beans_admin_backup_tab') === FALSE) :
class _beans_admin_backup_tab
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
	 * @access (private)
	 * @var (string) $admin_url
	 * 	https://developer.wordpress.org/reference/files/wp-admin/admin.php/
	*/
	private $admin_url = '';


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
		$this->admin_url = 'admin.php?page=beans_extension_setting&tab=backup';

	}// Method


	/* Method
	_________________________
	*/
	public static function __render()
	{
		/**
			@access (public)
				Render Import Export tab page.
			@return (void)
			@reference
				[Plugin]/admin/beans.php
				[Plugin]/admin/tab/helper/trait.php
		*/

		// Backup Tab
		if(isset($_POST['action']) && $_POST['action'] === 'importing'){

			try{
				// Takes a JSON encoded string and converts it into a PHP variable.
				$array = json_decode(stripslashes($_POST['bx_option_backup_import']),TRUE);
				if(count($array) > 0){
					if(isset($array['general'])){
						update_option('bx_option_general',$array['general']);
					}
					if(isset($array['column'])){
						update_option('bx_option_column',$array['column']);
					}
					if(isset($array['image'])){
						// Ignore image settings.
						// update_option('bx_option_image',$array['image']);
					}

					if(isset($array['layout'])){
						update_option('bx_option_layout',$array['layout']);
					}
					if(isset($array['cleaner'])){
						update_option('bx_option_cleaner',$array['cleaner']);
					}

					/**
					 * @reference (WP)
					 * 	Performs a safe (local) redirect, using wp_redirect().
					 * 	https://developer.wordpress.org/reference/functions/wp_safe_redirect/
					 * 	Retrieves the URL to the admin area for the current site.
					 * 	https://developer.wordpress.org/reference/functions/admin_url/
					*/
					wp_safe_redirect(admin_url($this->admin_url . '&res=success&msg=' . urlencode(esc_html__('Imported Successfully!','beans-extension'))));
					die();
				}
				else{
					wp_safe_redirect(admin_url($this->admin_url . '&res=invalid&msg=' . urlencode(esc_html__('No data or Invalid data!','beans-extension'))));
				}
			}
			catch(Exception $e){
				wp_safe_redirect(admin_url($this->admin_url . '&res=error&msg=' . urlencode($e->getMessage())));
			}
		}

		if(isset($_GET['res']) && !empty($_GET['res'])){
			switch($_GET['res']){
				case 'success' :
					?>
					<div class="notice-success notice is-dismissible">
						<p><strong><?php echo esc_html($_GET['msg']); ?></strong></p>
						<button type="button" class="notice-dismiss"></button>
					</div>
					<?php
					break;
				case 'invalid' :
					?>
					<div class="notice-error notice is-dismissible">
						<p><strong><?php echo esc_html($_GET['msg']); ?></strong></p>
						<button type="button" class="notice-dismiss"></button>
					</div>
					<?php
					break;
				case 'error' :
					?>
					<div class="notice-error notice is-dismissible">
						<p><strong><?php echo esc_html($_GET['msg']); ?></strong></p>
						<button type="button" class="notice-dismiss"></button>
					</div>
					<?php
					break;
			}
		}
		?>

		<table class="form-table">
			<h2 class="bx-headline"><?php echo esc_html__('Import your get_option() Settings','beans-extension'); ?></h2>
			<tr>
				<th scope="row">
					<div class="bx-warning">
						<?php echo esc_html__('Warning! Importing new settings will Overwrite your current configuration.','beans-extension'); ?>
					</div><!-- .warning-info -->
				</th>
				<td>
					<input type="hidden" value="importing" name="action">
					<textarea id="bx_setting_backup_import" name="bx_option_backup_import" class="textarea widefat" rows="8" placeholder="Paste your settings to be imported."></textarea>
					<br />
					<button type="submit" id="bx_setting_backup_import" class="button button-primary">
						<?php echo esc_html__('Start Importing','beans-extension'); ?>
					</button>
				</td>
			</tr>
		</table>

		<?php
		$general = get_option('bx_option_general');
		$column = get_option('bx_option_column');
		$image = get_option('bx_option_image');
		$layout = get_option('bx_option_layout');
		$cleaner = get_option('bx_option_cleaner');

		$output = array(
			'general' => $general,
			'column' => $column,
			'image' => $image,
			'layout' => $layout,
			'cleaner' => $cleaner,
		);
		?>
		<form method="post">
			<table class="form-table">
				<h2 class="wx-headline"><?php echo esc_html__('Export your get_option() Settings','beans-extension'); ?></h2>
				<tr>
					<th scope="row">
						<div class="wx-warning">
							<?php echo esc_html__('Note:Images like Logo and Thumbnails are not included. You will need to manually set it.','beans-extension'); ?>
						</div>
					</th>
					<td>
						<textarea id="bx_setting_backup_export" name="bx_option_backup_export" class="textarea widefat" rows="8">
							<?php
							/**
							 * @reference (WP)
							 * 	Encode a variable into JSON, with some sanity checks.
							 * 	https://developer.wordpress.org/reference/functions/wp_json_encode/
							*/
							echo esc_html(wp_json_encode($output));
							?>
						</textarea>
						<br />
						<button type="button" id="bx_setting_backup_export" class="button button-primary">
							<?php echo esc_html__('Copy to Clipboard','beans-extension'); ?>
						</button>
					</td>
				</tr>
			</table>
		</form>

	<?php
	}// Method


}// Class
endif;
// new _beans_admin_backup_tab();
_beans_admin_backup_tab::__get_instance();
