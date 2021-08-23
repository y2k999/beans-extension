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
 * @reference
 * 	wp-admin/term.php?taxonomy=category
 * 	wp-admin/term.php?taxonomy=post_tag
*/


/* Exec
______________________________
*/
?>
<tr class="form-field">
	<th scope="row">
		<?php beans_extension_field_label($field); ?>
	</th>
	<td>
		<!-- phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect, Generic.WhiteSpace.ScopeIndent.IncorrectExact -- View file is indented for HTML structure. -->
		<?php beans_field($field); ?>
	</td>
</tr>
