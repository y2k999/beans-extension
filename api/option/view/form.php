<?php
/**
 * View file of Beans API.
 * @package Beans_Extension
 * @license GPL3.0+
 * @since 1.0.1
 * @since 1.5.0
 * 	Moved to view file.
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
?>
<form action="" method="post" class="bs-options" data-page="<?php echo esc_attr(_beans_utility::__get_global_value('page')); ?>">
	<?php
	/**
	 * @since 1.0.1
	 * 	Retrieve or display nonce hidden field for forms.
	 * 	https://developer.wordpress.org/reference/functions/wp_nonce_field/
	 * @reference
	 * 	[Plugin]/utility/beans.php
	*/
	wp_nonce_field('closedpostboxes','closedpostboxesnonce',FALSE);
	wp_nonce_field('meta-box-order','meta-box-order-nonce',FALSE);
	?>
	<input type="hidden" name="beans_extension_option_nonce" value="<?php echo esc_attr(wp_create_nonce('beans_extension_option_nonce')); ?>" />
	<div class="metabox-holder<?php echo $column_class ? esc_attr($column_class) : ''; ?>">
		<?php
		/**
		 * @reference (WP)
		 * 	Meta-Box template function.
		 * 	https://developer.wordpress.org/reference/functions/do_meta_boxes/
		*/
		do_meta_boxes($page,'normal',NULL);

		if($column_class){
			do_meta_boxes($page,'column',NULL);
		}
		?>
	</div>
	<p class="bs-options-form-actions">
		<!-- phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect, Generic.WhiteSpace.ScopeIndent.IncorrectExact -- View file is indented for HTML structure. -->
		<input type="submit" name="beans_extension_save_option" value="<?php esc_attr_e('Save','beans-extension'); ?>" class="button-primary">
		<input type="submit" name="beans_extension_reset_option" value="<?php esc_attr_e('Reset','beans-extension'); ?>" class="button-secondary">
	</p>
</form>
