<?php
/**
 * Define Beans API classes.
 * @package Beans_Extension
 * @license GPL3.0+
 * @since 1.0.1
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
if(class_exists('_beans_layout') === FALSE) :
class _beans_layout
{
/**
 * @since 1.0.1
 * 	The Beans Layout API controls what and how Beans main section elements are displayed.
 * 	Layouts are:
 * 	 - "c" - content only
 * 	 - "c_sp" - content + sidebar primary
 * 	 - "sp_c" - sidebar primary + content
 * 	 - "c_ss" - content + sidebar secondary
 * 	 - "c_sp_ss" - content + sidebar primary + sidebar secondary
 * 	 - "ss_c" - sidebar secondary + content
 * 	 - "sp_ss_c" - sidebar primary + sidebar secondary + content
 * 	 - "sp_c_ss" - sidebar primary + content + sidebar secondary
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_image()
 * 	beans_get_default_layout()
 * 	beans_get_layout()
 * 	beans_get_layout_class()
 * 	_beans_get_layout_classes()
 * 	beans_get_layouts_for_options()
 * 	beans_has_primary_sidebar()
 * 	beans_has_secondary_sidebar()
 * 	beans_set_sidebar_layout_callbacks()
 * 	__get_reduced_grid()
*/

	/**
		@access(private)
			Class properties.
		@var (array) $_image
	*/
	private static $_image = array();

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

		// Init properties.
		self::$_image = $this->set_image();

	}// Method


	/* Setter
	_________________________
	 */
	private function set_image()
	{
		/**
			@access (private)
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@return (array)
			@reference
				[Plugin]/include/constant.php
				[Plugin]/include/component.php
				[Plugin]/admin/tab/general.php
				[Plugin]/admin/tab/layout.php
		*/
		$return = array();

		// Custom global variable.
		global $_beans_extension_component_setting;
		if($_beans_extension_component_setting['general']['legacy_layout']){
			$return = array(
				'c' => array(
					'src' => BEANS_EXTENSION_API_URL['asset'] . 'image/beans/c.png',
					'alt' => esc_attr__('Full-Width Content Layout','beans-extension'),
					'screen_reader_text' => esc_attr__('Option for the Full-Width Content Layout.','beans-extension'),
				),
				'c_sp' => array(
					'src' => BEANS_EXTENSION_API_URL['asset'] . 'image/beans/cs.png',
					'alt' => esc_attr__('Content and Primary Sidebar Layout','beans-extension'),
					'screen_reader_text' => esc_attr__('Option for the Content and Primary Sidebar Layout.','beans-extension'),
				),
				'sp_c' => array(
					'src' => BEANS_EXTENSION_API_URL['asset'] . 'image/beans/sc.png',
					'alt' => esc_attr__('Primary Sidebar and Content Layout','beans-extension'),
					'screen_reader_text' => esc_attr__('Option for the Primary Sidebar and Content Layout.','beans-extension'),
				),
				'c_sp_ss' => array(
					'src' => BEANS_EXTENSION_API_URL['asset'] . 'image/beans/css.png',
					'alt' => esc_attr__('Content,Primary Sidebar and Secondary Sidebar Layout','beans-extension'),
					'screen_reader_text' => esc_attr__('Option for the Content,Primary Sidebar and Secondary Sidebar Layout.','beans-extension'),
				),
				'sp_ss_c' => array(
					'src' => BEANS_EXTENSION_API_URL['asset'] . 'image/beans/ssc.png',
					'alt' => esc_attr__('Primary Sidebar,Secondary Sidebar and Content Layout','beans-extension'),
					'screen_reader_text' => esc_attr__('Option for the Primary Sidebar,Secondary Sidebar and Content Layout.','beans-extension'),
				),
				'sp_c_ss' => array(
					'src' => BEANS_EXTENSION_API_URL['asset'] . 'image/beans/scs.png',
					'alt' => esc_attr__('Primary Sidebar,Content and Secondary Sidebar Layout','beans-extension'),
					'screen_reader_text' => esc_attr__('Option for the Primary Sidebar,Content and Secondary Sidebar Layout.','beans-extension'),
				),
			);
		}
		else{
			$return = array(
				'c' => array(
					'src' => BEANS_EXTENSION_API_URL['asset'] . 'image/layout/c.png',
					'alt' => esc_attr__('Full-Width Content Layout','beans-extension'),
					'screen_reader_text' => esc_attr__('Option for the Full-Width Content Layout.','beans-extension'),
				),
				'c_sp' => array(
					'src' => BEANS_EXTENSION_API_URL['asset'] . 'image/layout/cs.png',
					'alt' => esc_attr__('Content and Primary Sidebar Layout','beans-extension'),
					'screen_reader_text' => esc_attr__('Option for the Content and Primary Sidebar Layout.','beans-extension'),
				),
			);
		}
		return apply_filters('beans_extension_default_image',$return);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_default()
	{
		/**
			[ORIGINAL]
				beans_get_default_layout()
				https://www.getbeans.io/code-reference/hooks/beans_default_layout/
			@access (public)
				Get the default layout ID.
					'default' => _beans_layout::__get_default(),
					_beans_customizer::__register($fields,'beans_layout',array('title' => __('Default Layout')));
				function __get_layout(){
					$layout = get_theme_mod('beans_layout',self::__get_default());
			@return (string)
			@reference
				[Plugin]/api/widget/beans.php
		*/

		/**
		 * @reference (Beans)
		 * 	Check whether a widget area is registered.
		 * 	https://www.getbeans.io/code-reference/functions/beans_has_widget_area/
		*/
		$default = _beans_widget::__has_widget_area('sidebar_primary') ? 'c_sp' : 'c';

		/**
		 * @since 1.0.1
		 * 	Filter the default layout ID.
		 * 	The default layout ID is set to "c_sp" (content + sidebar primary).
		 * 	If the sidebar primary is unregistered,then it is set to "c" (content only).
		 * 	https://www.getbeans.io/code-reference/hooks/beans_layouts/
		 * @param (string) $default
		 * 	The default layout ID.
		*/
		return apply_filters('beans_extension_default_layout',$default);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_current()
	{
		/**
			[ORIGINAL]
				beans_get_layout()
				https://www.getbeans.io/code-reference/functions/beans_get_layout/
			@access (public)
				Get the current web page's layout ID.
					function __get_attribute(array $args){
						$layout = self::__get_layout();
			@return (string)
			@reference
				[Plugin]/api/post-meta/beans.php
				[Plugin]/api/term-meta/beans.php
		*/

		// Get layout settings for WP dashboard (post editor, category editor, tags editor).
		if(is_singular()){
			/**
			 * @reference (Beans)
			 * 	Get the current post meta value.
			 * 	https://www.getbeans.io/code-reference/functions/beans_get_post_meta/
			*/
			$layout = _beans_post_meta::__get_setting('beans_extension_layout');
		}
		elseif(is_home()){
			$posts_page = (int)get_option('page_for_posts');
			if(0 !== $posts_page){
				$layout = _beans_post_meta::__get_setting('beans_extension_layout',FALSE,$posts_page);
			}
		}
		elseif(is_category() || is_tag() || is_tax()){
			/**
			 * @reference (Beans)
			 * 	Get the current term meta value.
			 * 	https://www.getbeans.io/code-reference/functions/beans_get_term_meta/
			*/
			$layout = _beans_term_meta::__get_setting('beans_extension_layout');
		}

		/**
		 * @since 1.0.1
		 * 	When the layout is not found or is set to "default_fallback",use the theme's default layout.
		 * @reference (WP)
		 * 	Retrieves theme modification value for the current theme.
		 * 	https://developer.wordpress.org/reference/functions/get_theme_mod/
		*/
		if(!isset($layout) || !$layout || 'default_fallback' === $layout){
			$layout = get_theme_mod('beans_extension_layout',self::__get_default());
		}

		/**
		 * @since 1.0.1
		 * 	Filter the web page's layout ID.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_layout/
		 * @param (string) $layout
		 * 	The layout ID.
		*/
		return apply_filters('beans_extension_layout',$layout);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_page_layout($id)
	{
		/**
			[ORIGINAL]
				beans_get_layout_class()
				https://www.getbeans.io/code-reference/functions/beans_get_layout_class/
			@access (public)
				Get the current web page's layout class.
				This function generates the layout class(es) based on the current layout.
					'class' => 'site-main ' . _beans_layout::__get_page_layout('content'),
					'class' => 'sidebar ' . _beans_layout::__get_page_layout('sidebar_primary'),
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@param (string) $id
				The searched layout section ID.
			@return (bool)
				Layout class,FALSE if no layout class found.
				https://github.com/Bowriverstudio/beans-frontend-framework-uikit3
			@reference
				[Plugin]/utility/beans.php
				[Plugin]/include/component.php
				[Plugin]/admin/tab/general.php
		*/

		/**
		 * @since 1.0.1
		 * 	Filter the arguments used to define the layout grid.
		 * 	The content number of columns are automatically calculated based on the grid,sidebar primary and sidebar secondary columns.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_layout_grid_settings/
		 * @global (array) $_beans_extension_component_setting
		 * 	API components support global.
		 * @param (array) $args{
		 * 	An array of arguments.
		 * 	@type (int) $grid
		 * 		Total number of columns the grid contains.
		 * 		[Default] 4
		 * 	@type (int) $sidebar_primary
		 * 		The number of columns the sidebar primary takes.
		 * 		[Default] 1
		 * 	@type (int) $sidebar_secondary
		 * 		The number of columns the sidebar secondary takes.
		 * 		[Default] 1
		 * 	@type (string) $breakpoint
		 * 		The UIkit grid breakpoint which may be set to 'small','medium' or 'large'.
		 * 		[Default] 'medium'
		 * }
		*/

		// Custom global variable.
		global $_beans_extension_component_setting;
		if($_beans_extension_component_setting['general']['uikit'] === 'uikit2'){
			$args = apply_filters('beans_extension_layout_configuration',array(
				'grid' => 4,
				'sidebar_primary' => 1,
				'sidebar_secondary' => 1,
				'breakpoint' => 'medium',
			));
		}
		else{
			$args = apply_filters('beans_extension_layout_configuration',array(
				'grid' => 4,
				'sidebar_primary' => 1,
				'sidebar_secondary' => 1,
				'breakpoint' => 'm',
			));
		}

		/**
		 * @since 1.0.1
		 * 	Filter the layout class.
		 * 	The dynamic portion of the hook name refers to the searched layout section ID.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_layout_class_id/
		 * @param (string) $layout
		 * 	The layout class.
		*/
		return apply_filters("beans_extension_layout_class_{$id}",_beans_utility::__get_global_value($id,self::__get_attribute($args)));

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_attribute(array $args)
	{
		/**
			[ORIGINAL]
				_beans_get_layout_classes()
			@since 1.5.0
			@access (public)
				Get the layout's class attribute values.
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@param (array) $args
				Grid configuration.
			@return (array)
				https://github.com/Bowriverstudio/beans-frontend-framework-uikit3
			@reference
				[Plugin]/utility/beans.php
				[Plugin]/api/widget/beans.php
				[Plugin]/include/component.php
				[Plugin]/admin/tab/general.php
		*/
		$grid = _beans_utility::__get_global_value('grid',$args);

		// $c stands for "content".
		$c = $grid;
		$sp = _beans_utility::__get_global_value('sidebar_primary',$args);
		$ss = _beans_utility::__get_global_value('sidebar_secondary',$args);

		// Custom global variable.
		global $_beans_extension_component_setting;
		if($_beans_extension_component_setting['general']['uikit'] === 'uikit2'){
			$prefix = 'uk-width-' . _beans_utility::__get_global_value('breakpoint',$args,'medium');
			$classes = array(
				'content' => "{$prefix}-{$c}-{$grid}",
			);
		}
		else{
			$prefix = 'uk-width';
			$suffix = '@' . _beans_utility::__get_global_value('breakpoint',$args,'medium');
			$classes = array(
				'content' => "{$prefix}-{$c}-{$grid}-{$suffix}",
			);
		}

		/**
		 * @reference (Beans)
		 * 	Check whether a widget area is registered.
		 * 	https://www.getbeans.io/code-reference/functions/beans_has_widget_area/
		*/
		if(!$_beans_extension_component_setting['general']['stop_widget']){
			if(!_beans_widget::__has_widget_area('sidebar_primary')){
				return $classes;
			}
		}

		$layout = self::__get_current();
		$has_secondary = _beans_widget::__has_widget_area('sidebar_secondary');
		$c = $has_secondary && strlen(trim($layout)) > 4 ? $grid - ($sp + $ss) : $grid - $sp;

		if($_beans_extension_component_setting['general']['uikit'] === 'uikit2'){
			switch($layout){
				case 'c_sp' :
				case 'c_sp_ss' :
					$classes['content'] = "{$prefix}-{$c}-{$grid}";
					$classes['sidebar_primary'] = "{$prefix}-{$sp}-{$grid}";
					if($has_secondary && 'c_sp_ss' === $layout){
						$classes['sidebar_secondary'] = "{$prefix}-{$ss}-{$grid}";
					}
					break;

				case 'sp_c' :
				case 'sp_c_ss' :
					$classes['content'] = "{$prefix}-{$c}-{$grid} uk-push-{$sp}-{$grid}";
					$classes['sidebar_primary'] = "{$prefix}-{$sp}-{$grid} uk-pull-{$c}-{$grid}";
					if($has_secondary && 'sp_c_ss' === $layout){
						$classes['sidebar_secondary'] = "{$prefix}-{$ss}-{$grid}";
					}
					break;

				case 'c_ss' :
					// If we don't have a secondary sidebar,bail out.
					if(!$has_secondary){
						return $classes;
					}
					$classes['content'] = "{$prefix}-{$c}-{$grid}";
					$classes['sidebar_secondary'] = "{$prefix}-{$ss}-{$grid}";
					break;

				case 'ss_c' :
					// If we don't have a secondary sidebar,bail out.
					if(!$has_secondary){
						return $classes;
					}
					$classes['content'] = "{$prefix}-{$c}-{$grid} uk-push-{$ss}-{$grid}";
					$classes['sidebar_secondary'] = "{$prefix}-{$ss}-{$grid} uk-pull-{$c}-{$grid}";
					break;

				case 'sp_ss_c':
					$push_content = $has_secondary ? $sp + $ss : $sp;
					$classes['content'] = "{$prefix}-{$c}-{$grid} uk-push-{$push_content}-{$grid}";
					$classes['sidebar_primary'] = "{$prefix}-{$sp}-{$grid} uk-pull-{$c}-{$grid}";
					if($has_secondary){
						$classes['sidebar_secondary'] = "{$prefix}-{$ss}-{$grid} uk-pull-{$c}-{$grid}";
					}
					break;

				default :
					break;
			}

		}
		else{
			switch($layout){
				case 'c_sp':
					$c = $grid - $sp;
					$classes['sidebar_primary'] = "{$prefix}-" . self::__get_reduced_grid("{$sp}-{$grid}") . "{$suffix}";
					$classes['content'] = "{$prefix}-" . self::__get_reduced_grid("{$c}-{$grid}") . "{$suffix}";
					break;

				case 'c':
					$classes['content'] = "{$prefix}-1-1{$suffix}";
					break;

				default :
					break;
			}
		}
		return $classes;

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_setting($add_default = FALSE)
	{
		/**
			[ORIGINAL]
				beans_get_layouts_for_options()
				https://www.getbeans.io/code-reference/functions/beans_get_layouts_for_options/
			@access (public)
				Generate layout elements used by Beans 'imageradio' option type.
				Added layout should contain a unique ID as the array key and a URL path to its related image as the array value.
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@param (bool) $add_default
				[Optional]
				Whether the 'default_fallback' element is added or not.
			@return (array)
				Layouts ready for Beans 'imageradio' option type.
			@return
				[Plugin]/include/component.php
				[Plugin]/admin/tab/general.php
				[Plugin]/admin/tab/layout.php
		*/
		$layouts['c'] = self::$_image['c'];
		$layouts['c_sp'] = self::$_image['c_sp'];

		// Check if Beans legacy layout.
		global $_beans_extension_component_setting;
		if($_beans_extension_component_setting['general']['legacy_layout']){

			// Add sidebar primary layouts if the primary widget area is registered.
			$layouts['sp_c'] = self::$_image['sp_c'];

			// Add sidebar secondary layouts if the primary and secondary widget area are registered.
			$layouts['c_sp_ss'] = self::$_image['c_sp_ss'];
			$layouts['sp_ss_c'] = self::$_image['sp_ss_c'];
			$layouts['sp_c_ss'] = self::$_image['sp_c_ss'];
		}

		/**
		 * @since 1.0.1
		 * 	Filter the layouts.
		 * @param (array)$layouts
		 * 	An array of layouts.
		*/
		$layouts = apply_filters('beans_extension_layout_setting',$layouts);
		if(!$add_default){
			return $layouts;
		}

		if($_beans_extension_component_setting['general']['legacy_layout']){
			$layouts = array_merge(array(
				'default_fallback' => sprintf(
					/* translators: The (%s) placeholder is for the "Modify" hyperlink. */
					esc_html__('Use Default Layout (%s)','beans-extension'),
					'<a href="' . admin_url('customize.php?autofocus[control]=beans_extension_layout') . '">' . _x('Modify','Default layout') . '</a>'
				),
			),$layouts);
		}
		else{
			$layouts = array_merge(array(
				'default_fallback' => sprintf(
					/* translators: The (%s) placeholder is for the "Modify" hyperlink. */
					esc_html__('Use Default Layout (%s)','beans-extension'),
					'<a href="' . admin_url('admin.php?page=beans_extension_setting&tab=layout') . '">' . _x('Modify','Default layout') . '</a>'
				),
			),$layouts);
		}
		return $layouts;

	}// Method


	/* Method
	_________________________
	*/
	public static function __has_primary_sidebar($layout)
	{
		/**
			[ORIGINAL]
				beans_has_primary_sidebar()
			@since 1.5.0
			@access (public)
				Check if the given layout has a primary sidebar.
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@param (string) $layout
				The layout to check.
			@return (bool)
			@reference
				[Plugin]/api/widget/beans.php
				[Plugin]/include/component.php
				[Plugin]/admin/tab/general.php
				[Plugin]/admin/tab/layout.php
		*/

		// Custom global variable.
		global $_beans_extension_component_setting;
		if($_beans_extension_component_setting['general']['legacy_layout']){
			if(!in_array($layout,array('c_sp','sp_c','c_sp_ss','sp_c_ss','sp_ss_c'),TRUE)){
				return FALSE;
			}
		}
		else{
			if(!in_array($layout,array('c_sp'),TRUE)){
				return FALSE;
			}
		}
		/**
		 * @reference (Beans)
		 * 	Check whether a widget area is in use.
		 * 	https://www.getbeans.io/code-reference/functions/beans_is_active_widget_area/
		*/
		return _beans_widget::__is_active_widget_area('sidebar_primary');

	}// Method


	/* Method
	_________________________
	*/
	public static function __has_secondary_sidebar($layout)
	{
		/**
			[ORIGINAL]
				beans_has_secondary_sidebar()
			@since 1.5.0
			@access (public)
				Check if the given layout has a secondary sidebar.
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@param (string) $layout
				The layout to check.
			@return (bool)
			@reference
				[Plugin]/api/widget/beans.php
				[Plugin]/include/component.php
				[Plugin]/admin/tab/general.php
				[Plugin]/admin/tab/layout.php
		*/

		// Custom global variable.
		global $_beans_extension_component_setting;
		if(!$_beans_extension_component_setting['general']['legacy_layout']){return;}

		if(!in_array($layout,array('c_ss','ss_c','c_sp_ss','sp_c_ss','sp_ss_c'),TRUE)){
			return FALSE;
		}
		/**
		 * @reference (Beans)
		 * 	Check whether a widget area is in use.
		 * 	https://www.getbeans.io/code-reference/functions/beans_is_active_widget_area/
		*/
		return _beans_widget::__is_active_widget_area('sidebar_secondary');

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_sidebar_layout_callback()
	{
		/**
			[ORIGINAL]
				beans_set_sidebar_layout_callbacks()
			@since 2.0.0
			@access (public)
				Sets the Side bar layout order
				UiKit 3 does not support grid pull or pushes - which is why the dom order is set instead of the original way.
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@return (void)
				https://github.com/Bowriverstudio/beans-frontend-framework-uikit3
			@reference
				[Plugin]/api/action/beans.php
				[Plugin]/include/component.php
				[Plugin]/admin/tab/general.php
				[Plugin]/admin/tab/layout.php
		*/

		// Custom global variable.
		global $_beans_extension_component_setting;
		if(!$_beans_extension_component_setting['general']['legacy_layout']){return;}

		$layout = self::__get_current();
		switch ($layout){
			case 'c_sp' :
			case 'c_sp_ss' :
			case 'c_ss' :
				_beans_action::__smart_action('beans_primary_after_markup','beans_sidebar_primary_template',5);
				_beans_action::__smart_action('beans_primary_after_markup','beans_sidebar_secondary_template');
				break;
			case 'sp_c_ss' :
				_beans_action::__smart_action('beans_primary_before_markup','beans_sidebar_primary_template',5);
				_beans_action::__smart_action('beans_primary_after_markup','beans_sidebar_secondary_template');
				break;
			case 'sp_c' :
			case 'ss_c' :
			case 'sp_ss_c' :
				_beans_action::__smart_action('beans_primary_before_markup','beans_sidebar_primary_template',5);
				_beans_action::__smart_action('beans_primary_before_markup','beans_sidebar_secondary_template');
				break;
			case 'c' :
			case 'full-width-content':
			default :
				break;
		}

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_reduced_grid(string $numberOfColumns_grid)
	{
		/**
			[ORIGINAL]
				beans_get_reduced_grid()
				https://github.com/Bowriverstudio/beans-frontend-framework-uikit3
			@since 2.0.0
			@access (public)
				Reduces the number of columns and grid to the lowest term.
				 - .uk-child-width-1-2
					All elements take up half of their parent container.
				 - .uk-child-width-1-3	
					All elements take up a third of their parent container.
				 - .uk-child-width-1-4
					All elements take up a fourth of their parent container.
				 - .uk-child-width-1-5
					All elements take up a fifth of their parent container.
				 - .uk-child-width-1-6
					All elements take up a sixth of their parent container.
			@param(string) $numberOfColumns_grid
			@return (string)
		*/
		$return = $numberOfColumns_grid;
		switch($numberOfColumns_grid){
			case '2-4' :
			case '3-6' :
				$return = '1-2';
				break;
			case '2-6' :
				$return = '1-3';
				break;
			case '4-6' :
				$return = '2-3';
				break;
		}
		return $return;

	}// Method


}// Class
endif;
// new _beans_layout();
_beans_layout::__get_instance();
