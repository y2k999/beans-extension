<?php
/**
 * Define helper functions.
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
/**
 * [TOC]
 * 	__beans_admin_echo_boolean()
 * 	__beans_admin_echo_integer()
 * 	__beans_admin_echo_string()
 * 	__beans_admin_echo_select()
 * 	__beans_admin_echo_image()
 * 	__beans_admin_echo_radio()
*/


	/* Method
	_________________________
	*/
	if(function_exists('__beans_admin_echo_boolean') === FALSE) :
	function __beans_admin_echo_boolean($needle,$option,$input)
	{
		/**
			@access (public)
				Render HTML for checkbox type.
			@param (string) $needle
			@param (string) $option
			@param (string) $input
			@return (void)
			@reference
				[Plugin]/admin/tab/helper/trait.php
		*/
		?><input type="checkbox" name="<?php echo esc_attr($option) . '[' . esc_attr($needle) . ']'; ?>" id="<?php echo esc_attr($needle); ?>" value="1" <?php echo checked(1,$input,FALSE); ?> /><?php

	}// Method
	endif;


	/* Method
	_________________________
	*/
	if(function_exists('__beans_admin_echo_integer') === FALSE) :
	function __beans_admin_echo_integer($needle,$option,$input)
	{
		/**
			@access (public)
				Render HTML for number type.
			@param (string) $needle
			@param (string) $option
			@param (string) $input
			@return (void)
			@reference
				[Plugin]/admin/tab/helper/trait.php
		*/
		?><input type="number" name="<?php echo esc_attr($option) . '[' . esc_attr($needle) . ']'; ?>" id="<?php echo esc_attr($needle); ?>" value="<?php echo esc_attr($input); ?>" /><?php

	}// Method
	endif;


	/* Method
	_________________________
	*/
	if(function_exists('__beans_admin_echo_string') === FALSE) :
	function __beans_admin_echo_string($needle,$option,$input)
	{
		/**
			@access (public)
				Render HTML for string type.
			@param (string) $needle
			@param (string) $option
			@param (string) $input
			@return (void)
			@reference
				[Plugin]/admin/tab/helper/trait.php
		*/
		?><input type="text" name="<?php echo esc_attr($option) . '[' . esc_attr($needle) . ']'; ?>" id="<?php echo esc_attr($needle); ?>" class="widefat" value="<?php echo esc_attr($input); ?>" /><?php

	}// Method
	endif;


	/* Method
	_________________________
	*/
	if(function_exists('__beans_admin_echo_select') === FALSE) :
	function __beans_admin_echo_select($setting,$needle,$option,$input)
	{
		/**
			@access (public)
				Render HTML for select type.
			@param (string) $setting
			@param (string) $needle
			@param (string) $option
			@param (string) $input
			@return (void)
			@reference
				[Plugin]/admin/tab/helper/trait.php
		*/
		$select = \Beans_Extension\_beans_admin_option_data::__get_setting($setting);

		?><select name="<?php echo esc_attr($option) . '[' . esc_attr($needle) . ']'; ?>" id="<?php echo esc_attr($needle); ?>" >
			<?php foreach($select as $select_key => $select_value) : ?>
				<option value="<?php echo esc_attr($select_key); ?>" <?php selected($select_key,$input); ?> >
					<?php echo esc_html($select_value); ?>
				</option>
			<?php endforeach; ?>
		</select><?php 

	}// Method
	endif;


	/* Method
	_________________________
	 */
	if(function_exists('__beans_admin_echo_image') === FALSE) :
	function __beans_admin_echo_image($needle,$name,$input)
	{
		/**
			@access (public)
				Render HTML for image type.
			@param (string) $needle
			@param (string) $name
			@param (string) $input
			@return (void)
			@reference
				[Plugin]/admin/tab/helper/trait.php
		*/
		?><img src="<?php echo esc_attr($input); ?>" id="<?php echo esc_attr($needle); ?>_image" style="margin: 0 auto; max-width: 240px; max-height: 160px; background-color: #aeaeae;" >
		<br />
		<button type="button" id="<?php echo esc_attr($needle); ?>_button" class="button button-primary" style="margin-top: 5px; width: 240px; max-width: 100%;" ><?php echo esc_html__('Upload Media','beans-extension'); ?></button>

		<input type="hidden" name="<?php echo esc_attr($name) . '[' . esc_attr($needle) . ']'; ?>" id="<?php echo esc_attr($needle); ?>" value="<?php echo esc_attr($input); ?>" /><?php

	}// Method
	endif;


	/* Method
	_________________________
	*/
	if(function_exists('__beans_admin_echo_radio') === FALSE) :
	function __beans_admin_echo_radio($needle,$name,$input){
		/**
			@access (public)
				Render HTML for radio image type.
			@param (string) $needle
			@param (string) $name
			@param (string) $input
			@return (void)
			@reference
				[Plugin]/admin/tab/helper/trait.php
				[Plugin]/include/constant.php
		*/
		?>
		<?php if(($needle === 'bx_setting_layout_single') || ($needle === 'bx_setting_layout_page')) : ?>
			<input type="radio" name = "<?php echo esc_attr($name) . '[' . esc_attr($needle) . ']'; ?>" id="<?php echo esc_attr($needle); ?>" value="c" <?php checked($input,'c'); ?> checked>
			<label for="<?php echo esc_attr($needle); ?>">
				<img src="<?php echo BEANS_EXTENSION_API_URL['asset'] . 'image/layout/c.png'; ?>" alt="" width="100" height="100" />
			</label>
			<input type="radio" name="<?php echo esc_attr($name) . '[' . esc_attr($needle) . ']'; ?>" id="<?php echo esc_attr($needle); ?>" value="c_sp" <?php checked($input,'c_sp'); ?>>
			<label for="<?php echo esc_attr($needle); ?>">
				<img src="<?php echo BEANS_EXTENSION_API_URL['asset'] . 'image/layout/cs.png'; ?>" alt="" width="100" height="100" />
			</label>
		<?php elseif(($needle === 'bx_setting_layout_home') || ($needle === 'bx_setting_layout_archive')) : ?>

			<input type="radio" name = "<?php echo esc_attr($name) . '[' . esc_attr($needle) . ']'; ?>" id="<?php echo esc_attr($needle); ?>" value="list" <?php checked($input,'list'); ?> checked>
			<label for="<?php echo esc_attr($needle); ?>">
				<img src="<?php echo BEANS_EXTENSION_API_URL['asset'] . 'image/layout/list.png'; ?>" alt="" width="100" height="100" />
			</label>
			<input type="radio" name="<?php echo esc_attr($name) . '[' . esc_attr($needle) . ']'; ?>" id="<?php echo esc_attr($needle); ?>" value="card" <?php checked($input,'card'); ?>>
			<label for="<?php echo esc_attr($needle); ?>">
				<img src="<?php echo BEANS_EXTENSION_API_URL['asset'] . 'image/layout/card.png'; ?>" alt="" width="100" height="100" />
			</label>

		<?php else : ?>

		<?php endif; ?>

	<?php
	}// Method
	endif;
