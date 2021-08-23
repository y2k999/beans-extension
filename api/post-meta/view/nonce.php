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

/**
 * @reference (WP)
 * 	Creates a cryptographic token tied to a specific action, user, user session, and window of time.
 * 	https://developer.wordpress.org/reference/functions/wp_create_nonce/
*/

/* Exec
______________________________
*/
?>
<input type="hidden" name="beans_extension_post_meta_nonce" value="<?php echo esc_attr(wp_create_nonce('beans_extension_post_meta_nonce')); ?>" />
