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
<script type="text/javascript">
	(function (jQuery){
		$(document).ready(function (){
			jQuery('#page_template').data('beans-pre',jQuery('#page_template').val());
			jQuery('#page_template').change(function (){
				var save = jQuery('#save-action #save-post'),meta = JSON.parse('<?php echo wp_json_encode($_beans_extension_post_meta); ?>');

				if(-1 === jQuery.inArray(jQuery(this).val(), meta) && -1 === jQuery.inArray(jQuery(this).data('beans-pre'),meta)){
					return;
				}
				if(save.length === 0){
					save = jQuery('#publishing-action #publish');
				}
				jQuery(this).data('beans-pre',jQuery(this).val());
				save.trigger('click');
				jQuery('#wpbody-content').fadeOut();
			});
		});
	})(jQuery);
</script>
