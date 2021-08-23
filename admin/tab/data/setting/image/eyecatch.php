<?php
/**
 * Define default values.
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
	 * @access (public)
	 * 	Eyecatch section of image tab/group.
	 * @return (array)
	 * 	[Plugin]/admin/tab/data/source.php
	 * 	[Plugin]/include/constant.php
	*/
	if(function_exists('__beans_admin_eyecatch_image_setting') === FALSE) :
	function __beans_admin_eyecatch_image_setting()
	{

		$option = get_option(BEANS_EXTENSION_PREFIX['option'] . 'image');
		$needle = BEANS_EXTENSION_PREFIX['setting'] . 'image_num_of_categories';
		$number = isset($option[$needle]) ? $option[$needle] : 5;

		$args = array(
			'exclude' => 1,
			'orderby' => 'count',
			'order' => 'desc',
			'hide_empty' => 1,
			'number' => $number,
		);
		$categories = get_categories($args);

		$return = array();

		if(!empty($categories)){
			foreach($categories as $category){
				// $return[$category->category_nicename] = array(
				$return[] = array(
					'label' => esc_html($category->cat_name),
					'type' => 'image',
					'default' => BEANS_EXTENSION_API_URL['asset'] . 'image/misc/nopost.jpg',
					'group' => 'image',
					'section' => 'eyecatch',
				);
			}
		}
		return $return;

	}// Method
	endif;
