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
<!-- phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect, Generic.WhiteSpace.ScopeIndent.IncorrectExact -- View file is indented for HTML structure. -->
<div id="message" class="updated">
	<p><?php esc_html_e('Cache Flushed Successfully!','beans-extension'); ?></p>
</div>
