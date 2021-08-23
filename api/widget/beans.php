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
if(class_exists('_beans_widget') === FALSE) :
class _beans_widget
{
/**
 * @since 1.0.1
 * 	The Beans Widgets API extends the WordPress Widgets API. 
 * 	Where WordPress uses 'sidebar',Beans uses 'widget_area' as it is more appropriate when using it to define an area which is not besides the main content (e.g. a mega footer widget area).
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_hook()
 * 	invoke_hook()
 * 	beans_register_widget_area()
 * 	beans_deregister_widget_area()
 * 	beans_is_active_widget_area()
 * 	beans_has_widget_area()
 * 	beans_get_widget_area_output()
 * 	beans_get_widget_area()
 * 	beans_widget_area_shortcodes()
 * 	beans_have_widgets()
 * 	beans_setup_widget()
 * 	beans_get_widget()
 * 	beans_widget_shortcodes()
 * 	_beans_setup_widget_area()
 * 	_beans_setup_widgets()
 * 	_beans_prepare_widget_data()
 * 	_beans_reset_widget_area()
 * 	_beans_reset_widget()
 * 	_beans_widget_area_subfilters()
 * 	_beans_widget_subfilters()
 * 	_beans_force_the_widget()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $_class
			Name/Identifier with Prefix.
		@var (array) $hook
			The collection of hooks that is being registered (that is, actions or filters).
	*/
	private static $_class = '';
	private $hook = array();

	/**
	 * Traits.
	*/
	use _trait_singleton;
	use _trait_hook;


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
		self::$_class = __utility_get_class(get_class($this));

		// Register hooks.
		$this->hook = $this->set_hook();
		$this->invoke_hook($this->hook);

	}// Method


	/* Setter
	_________________________
	*/
	private function set_hook()
	{
		/**
			@access (private)
				Fires before rendering the requested widget.
				https://developer.wordpress.org/reference/hooks/the_widget/
			@return (array)
				The collection of hooks that is being registered (that is, actions or filters).
			@reference
				[Plugin]/trait/hook.php
		*/
		return $this->set_parameter_callback(array(
			'force' => array(
				'tag' => 'add_action',
				'hook' => 'the_widget',
				'args' => 3
			),
		));

	}// Method


	/* Method
	_________________________
	*/
	public static function __register_widget_area($args = array())
	{
		/**
			[ORIGINAL]
				beans_register_widget_area()
				https://www.getbeans.io/code-reference/functions/beans_register_widget_area/
			@access (public)
				Register a widget area.
				Since a Beans widget area is using the WordPress sidebar,this function registers a WordPress sidebar using register_sidebar(),with additional arguments.
				https://codex.wordpress.org/Function_Reference/register_sidebar
				[NOTE]
					the 'class',
					 - before_widget',
					 - 'after_widget',
					 - 'before_title',
					 - 'after_title' 
					arguments are not supported.
				Beans widgets are built using the Beans HTML API which allows full control over HTML markup and attributes.
				When allowing for automatic generation of the name and ID parameters,keep in mind that the incrementor for your widget area can change over time depending on what other plugins and themes are installed.
			@param (array) $args{
				[Optional]
					Arguments used by the widget area.
				@type (string) $id
					The unique identifier by which the widget area will be called.
				@type (string) $name...,
					[Optional]
					The name or title of the widget area displayed in the admin dashboard.
				@type (string) $description...,
					[Optional]
					The widget area description.
				@type (string) $beans_type...,
					[Optional]
					The widget area type.
					Accepts 'stack','grid' or 'offcanvas'.
					[Default] stack
				@type (bool) $beans_show_widget_title...,
					[Optional]
					Whether to show the widget title or not.
					[Default] TRUE
				@type (bool) $beans_show_widget_badge...,
					[Optional]
					Whether to show the widget badge or not.
					[Default] FALSE
				@type (bool) $beans_widget_badge_content...,
					[Optional]
					The badge content.
					This may contain widget shortcodes @see beans_widget_shortcodes()}.
					[Default] 'Hello'
				}
			@return (string)
				The widget area ID is added to the $wp_registered_sidebars globals when the widget area is setup.
			@reference
				[Plugin]/utility/beans.php
				[Plugin]/api/filter/beans.php
		*/

		// Stop here if the id isn't set.
		$id = _beans_utility::__get_global_value('id',$args);
		if(!$id){
			return '';
		}

		/**
		 * @reference (Beans)
		 * 	Filter the default arguments used by the widget area.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_widgets_area_default_args/
		*/
		$defaults = apply_filters('beans_widget_area_default_args',array(
			'beans_type' => 'stack',
			'beans_show_widget_title' => TRUE,
			'beans_show_widget_badge' => FALSE,
			'beans_widget_badge_content' => __('Hello','beans-extension'),
		));

		/**
		 * @reference (Beans)
		 * 	Filter the arguments used by the widget area.
		 * 	The dynamic portion of the hook name,$id,refers to the widget area id.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_widgets_area_args_id/
		*/
		$args = _beans_filter::__apply_filter("beans_widget_area_args[_{$id}]",array_merge($defaults,$args));

		/**
		 * @reference (WP)
		 * 	Builds the definition for a single sidebar and returns the ID.
		 * 	https://developer.wordpress.org/reference/functions/register_sidebar/
		*/
		return register_sidebar($args);

	}// Method


	/* Method
	_________________________
	*/
	public static function __deregister_widget_area($id)
	{
		/**
			[ORIGINAL]
				beans_deregister_widget_area()
				https://www.getbeans.io/code-reference/functions/beans_deregister_widget_area/
			@access (public)
				Remove a registered widget area.
			@param (string) $id
				The ID of the registered widget area.
			@return (void)
		*/

		/**
		 * @reference (WP)
		 * 	Since a Beans widget area is using the WordPress sidebar,this function deregisters the defined WordPress sidebar using unregister_sidebar().
		 * 	https://codex.wordpress.org/Function_Reference/unregister_sidebar
		*/
		unregister_sidebar($id);

	}// Method


	/* Method
	_________________________
	*/
	public static function __is_active_widget_area($id)
	{
		/**
			[ORIGINAL]
				beans_is_active_widget_area()
				https://www.getbeans.io/code-reference/functions/beans_is_active_widget_area/
			@access (public)
				Check whether a widget area is in use.
			@param (string) $id
				The ID of the registered widget area.
			@return (bool)
				True if the widget area is in use,false otherwise.
		*/

		/**
		 * @reference (WP)
		 * 	Since a Beans widget area is using the WordPress sidebar,this function checks if the defined sidebar is in use using is_active_sidebar().
		 * 	https://codex.wordpress.org/Function_Reference/is_active_sidebar
		*/
		return is_active_sidebar($id);

	}// Method


	/* Method
	_________________________
	*/
	public static function __has_widget_area($id)
	{
		/**
			[ORIGINAL]
				beans_has_widget_area()
				https://www.getbeans.io/code-reference/functions/beans_has_widget_area/
			@access (public)
				Check whether a widget area is registered.
				While checks if a widget area contains widgets,this function only checks if a widget area is registered.
				{@see beans_is_active_widget_area()}
			@global (array) $wp_registered_sidebars
				The new sidebars are stored in this array by sidebar ID.
			@param (string) $id
				The ID of the registered widget area.
			@return (bool)
				True if the widget area is registered,false otherwise.
		*/

		// Stores the sidebars,since many themes can have more than one
		global $wp_registered_sidebars;
		return isset($wp_registered_sidebars[$id]);

	}// Method


	/* Method
	_________________________
	*/
	public static function __render_widget_area($id)
	{
		/**
			[ORIGINAL]
				beans_get_widget_area_output()
			@access (public)
				Get the output of a widget area.
			@param (string) $id
				The ID of the registered widget area.
			@return (string)|(bool)
				The output,if a widget area was found and called.
				False if not found.
		*/

		// Stop here if the widget area is not registered.
		if(!self::__has_widget_area($id)){
			return FALSE;
		}
		self::__setup_widget_area($id);

		/**
		 * @reference (Beans)
		 * 	Fires after a widget area is initialized.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_widget_area_init/
		*/
		do_action('beans_widget_area_init');

		// Turn on output buffering.
		ob_start();

		/**
		 * @reference (Beans)
		 * 	Fires when{@see beans_get_widget_area_output()} is called.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_widget_area/
		*/
		do_action('beans_widget_area');

		// Get current buffer contents and delete current output buffer.
		$output = ob_get_clean();

		// Reset widget area global to reduce memory usage.
		self::__reset_widget_area();

		/**
		 * @reference (Beans)
		 * 	Fires after a widget area is reset.
		 * 	https://www.getbeans.io/code-reference/hooks/beans_widget_area_reset/
		*/
		do_action('beans_widget_area_reset');

		return $output;

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_widget_area($needle = FALSE)
	{
		/**
			[ORIGINAL]
				beans_get_widget_area()
				https://www.getbeans.io/code-reference/functions/beans_get_widget_area/
			@access (public)
				Retrieve data from the current widget area in use.
			@global (array) $_beans_extension_widget_area
				Widget area global.
			@param (string)|(bool) $needle
				[Optional]
					The searched widget area needle.
			@return (string)|(bool)
				The current widget area data,or field data if the needle is specified.
				False if not found.
			@reference
				[Plugin]/utility/beans.php
		*/

		// Custom global variable.
		global $_beans_extension_widget_area;

		if(!$needle){
			return $_beans_extension_widget_area ? $_beans_extension_widget_area : FALSE;
		}
		return _beans_utility::__get_global_value($needle,$_beans_extension_widget_area,FALSE);

	}// Method


	/* Method
	_________________________
	*/
	public static function __widget_area_shortcode($content)
	{
		/**
			[ORIGINAL]
				beans_widget_area_shortcodes()
				https://www.getbeans.io/code-reference/functions/beans_widget_area_shortcodes/
			@access (public)
				Search content for shortcodes and filter shortcodes through their hooks.
				Shortcodes must be delimited with curly brackets (e.g.{key}) and correspond to the searched array key from the widget area global.
			@global (array) $_beans_extension_widget_area
				Widget area global.
			@param (string)|(array) $content 
				Content containing the shortcode(s) delimited with curly brackets (e.g.{key}).
				Shortcode(s) correspond to the searched array key and will be replaced by the array value if found.
			@return (string)
				Content with shortcodes filtered out.
			@reference
				[Plugin]/utility/beans.php
		*/
		if(!isset($GLOBALS['_beans_extension_widget_area'])){
			return $content;
		}

		if(is_array($content)){
			/**
			 * @reference (WP)
			 * 	Build URL query based on an associative and, or indexed array.
			 * 	https://developer.wordpress.org/reference/functions/build_query/
			*/
			$content = build_query($content);
		}
		return _beans_utility::__array_shortcode($content,$GLOBALS['_beans_extension_widget_area']);

	}// Method


	/* Method
	_________________________
	*/
	public static function __has_widget()
	{
		/**
			[ORIGINAL]
				beans_have_widgets()
				https://www.getbeans.io/code-reference/functions/beans_have_widgets/
			@access (public)
				Whether there are more widgets available in the loop.
			@global (array) $_beans_extension_widget_area
				Widget area global.
			@return (bool)
				True if widgets are available,false if end of loop.
			@reference
				[Plugin]/utility/beans.php
		*/

		// Custom global variable.
		global $_beans_extension_widget_area;
		if(!_beans_utility::__get_global_value('widget',$_beans_extension_widget_area)){
			return FALSE;
		}

		$widgets = array_keys($_beans_extension_widget_area['widget']);
		if(isset($widgets[$_beans_extension_widget_area['current_widget']])){
			return TRUE;
		}

		// Reset last widget global to reduce memory usage.
		self::__reset_widget();

		return FALSE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __setup_widget()
	{
		/**
			[ORIGINAL]
				beans_setup_widget()
				https://www.getbeans.io/code-reference/functions/beans_setup_widget/
			@access (public)
				Sets up the current widget.
				This function also prepares the next widget integer.
			@global (array) $_beans_extension_widget_area
				Widget area global.
			@return (bool)
				True on success,false on failure.
			@reference
				[Plugin]/utility/beans.php
		*/

		// Custom global variable.
		global $_beans_extension_widget_area;
		$widgets = array_keys($_beans_extension_widget_area['widget']);

		// Retrieve widget id if exists.
		$id = _beans_utility::__get_global_value($_beans_extension_widget_area['current_widget'],$widgets);
		if(!$id){
			return FALSE;
		}

		// Set next current widget integer.
		$_beans_extension_widget_area['current_widget'] = $_beans_extension_widget_area['current_widget'] + 1;

		self::__prepare_widget($id);

		return TRUE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_widget($needle = FALSE)
	{
		/**
			[ORIGINAL]
				beans_get_widget()
				https://www.getbeans.io/code-reference/functions/beans_get_widget/
			@access (public)
				Retrieve data from the current widget in use.
			@global (array) $_beans_extension_widget
				Widget global.
			@param (string)|(bool) $needle
				[Optional]
				The searched widget needle.
			@return (string)|(bool)
				The current widget data,or field data if the needle is specified.
				False if not found.
			@reference
				[Plugin]/utility/beans.php
		*/

		// Custom global variable.
		global $_beans_extension_widget;

		if(!$needle){
			return $_beans_extension_widget ? $_beans_extension_widget : FALSE;
		}
		return _beans_utility::__get_global_value($needle,$_beans_extension_widget,FALSE);

	}// Method


	/* Method
	_________________________
	*/
	public static function __widget_shortcode($content)
	{
		/**
			[ORIGINAL]
				beans_widget_shortcodes()
				https://www.getbeans.io/code-reference/functions/beans_widget_shortcodes/
			@access (public)
				Search content for shortcodes and filter shortcodes through their hooks.
				Shortcodes must be delimited with curly brackets (e.g.{key}) and correspond to the searched array key from the widget global.
			@global (array) $_beans_extension_widget
				Widget global.
			@param (string)|(array) $content
				Content containing the shortcode(s) delimited with curly brackets (e.g.{key}).
				Shortcode(s) correspond to the searched array key and will be replaced by the array value if found.
			@return (string)
				Content with shortcodes filtered out.
			@reference
				[Plugin]/utility/beans.php
		*/
		if(!isset($GLOBALS['_beans_extension_widget'])){
			return $content;
		}

		if(is_array($content)){
			/**
			 * @reference (WP)
			 * 	Build URL query based on an associative and, or indexed array.
			 * 	https://developer.wordpress.org/reference/functions/build_query/
			*/
			$content = build_query($content);
		}
		return _beans_utility::__array_shortcode($content,$GLOBALS['_beans_extension_widget']);

	}// Method


	/**
		[ORIGINAL]
			_beans_setup_widget_area()
		@access (public)
			Set up widget area global data.
		@global (array) $wp_registered_sidebars
			The new sidebars are stored in this array by sidebar ID.
		@global (array) $_beans_extension_widget_area
			Widget area global.
		@param (string) $id
			Widget area ID.
		@return (bool)
		@reference
			[Plugin]/utility/beans.php
	*/
	public static function __setup_widget_area($id)
	{
		// Custom global variable.
		global $_beans_extension_widget_area;

		// WP global.
		global $wp_registered_sidebars;
		if(!isset($wp_registered_sidebars[$id])){
			return FALSE;
		}

		/**
		 * @since 1.0.1
		 * 	Add widget area delimiters.
		 * 	This is used to split wp sidebar as well as the widgets title.
		*/
		$wp_registered_sidebars[$id] = array_merge($wp_registered_sidebars[$id],array(
			/* phpcs:ignore WordPress.Variables.GlobalVariables.OverrideProhibited -- Valid use case. */
			'before_widget' => '<!--widget-%1$s-->',
			'after_widget' => '<!--widget-end-->',
			'before_title' => '<!--title-start-->',
			'after_title' => '<!--title-end-->',
		));

		// Start building widget area global before dynamic_sidebar is called.
		$_beans_extension_widget_area = $wp_registered_sidebars[$id];

		// Buffer sidebar,please make it easier for us wp.
		$sidebar = _beans_utility::__render_function('dynamic_sidebar',$id);

		// Prepare sidebar split.
		$sidebar = preg_replace(
			'#(<!--widget-([a-z0-9-_]+)-->(.*?)<!--widget-end-->*?)#smU',
			'<!--split-sidebar-->$1<!--split-sidebar-->',
			$sidebar
		);

		// Split sidebar.
		$splited_sidebar = explode('<!--split-sidebar-->',$sidebar);

		// Prepare widgets count.
		preg_match_all('#<!--widget-end-->#',$sidebar,$counter);

		// Continue building widget area global with the split sidebar elements.
		$_beans_extension_widget_area['widget_count'] = count($counter[0]);
		$_beans_extension_widget_area['current_widget'] = 0;

		// Only add widgets if exists.
		if(3 === count($splited_sidebar)){
			$_beans_extension_widget_area['before_widget'] = $splited_sidebar[0];
			$_beans_extension_widget_area['widget'] = self::__prepare_widget_area($splited_sidebar[1]);
			$_beans_extension_widget_area['after_widget'] = $splited_sidebar[2];
		}
		return TRUE;

	}// Method


	/**
		[ORIGINAL]
			_beans_setup_widgets()
		@access (public)
			Setup widget area global widgets data.
		@global (array) $wp_registered_widgets
			Uses stored registered widgets.
		@global (array) $_beans_extension_widget_area
			Widget area global.
		@param (string) $widget_area_content
			Content of the widget area.
		@return (array)
		@reference
			[Plugin]/utility/beans.php
	*/
	public static function __prepare_widget_area($widget_area_content)
	{
		// Custom global variable.
		global $_beans_extension_widget_area;

		// WP global.
		global $wp_registered_widgets;

		$beans_widgets = array();

		foreach(explode('<!--widget-end-->',$widget_area_content) as $content){
			if(!preg_match('#<!--widget-([a-z0-9-_]+?)-->#smU',$content,$matches)){continue;}

			// Retrieve widget id.
			$id = $matches[1];

			// Stop here if the widget can't be found.
			$data = _beans_utility::__get_global_value($id,$wp_registered_widgets);

			// Start building the widget array.
			$widget = array();

			// Set defaults.
			$widget['options'] = array();
			$widget['type'] = NULL;
			$widget['title'] = '';

			// Add total count.
			$widget['count'] = $_beans_extension_widget_area['widget_count'];

			// Add basic widget arguments.
			foreach(array('id','name','classname','description') as $var){
				$widget[$var] = isset($data[$var]) ? $data[$var] : NULL;
			}

			// Add type and options.
			$object = current($data['callback']);

			if(isset($data['callback']) && is_array($data['callback']) && $object){
				/**
					@reference (WP)
						Core base class extended to register widgets.
						https://developer.wordpress.org/reference/classes/wp_widget/
				*/
				if(is_a($object,'WP_Widget')){
					$widget['type'] = $object->id_base;

					if(isset($data['params'][0]['number'])){
						$number = $data['params'][0]['number'];
						$params	= get_option($object->option_name);
						if(FALSE === $params && isset($object->alt_option_name)){
							$params = get_option($object->alt_option_name);
						}
						if(isset($params[$number])){
							$widget['options'] = $params[$number];
						}
					}
				}
			}
			elseif('nav_menu-0' === $id){
				// Widget type fallback.
				$widget['type'] = 'nav_menu';
			}

			// Widget fallback name.
			if(empty($widget['name'])){
				$widget['name'] = ucfirst($widget['type']);
			}

			// Extract and add title.
			if(preg_match('#<!--title-start-->(.*)<!--title-end-->#s',$content,$matches)){
				$widget['title'] = strip_tags($matches[1]);
			}

			// Remove title from content.
			$content = preg_replace('#(<!--title-start-->.*<!--title-end-->*?)#smU','',$content);

			// Remove widget HTML delimiters.
			$content = preg_replace('#(<!--widget-([a-z0-9-_]+)-->|<!--widgets-end-->)#','',$content);

			$widget['content'] = $content;

			// Add widget control arguments and register widget.
			$beans_widgets[$widget['id']] = array_merge($widget,array(
				'show_title' => isset($_beans_extension_widget_area['beans_show_widget_title']) && $_beans_extension_widget_area['beans_show_widget_title'] ? FALSE : TRUE,
				'badge' => isset($_beans_extension_widget_area['beans_show_widget_badge']) && $_beans_extension_widget_area['beans_show_widget_badge'] ? TRUE : FALSE,
				'badge_content' => isset($_beans_extension_widget_area['beans_widget_badge_content']) ? $_beans_extension_widget_area['beans_widget_badge_content'] : __('Hello','beans-extension'),
			));
		}
		return $beans_widgets;

	}// Method


	/**
		[ORIGINAL]
			_beans_prepare_widget_data()
		@access (public)
			Setup widget global data.
		@global (array) $_beans_extension_widget
			Widget global.
		@param (string) $id
			Widget ID.
		@return (void)
	*/
	public static function __prepare_widget($id)
	{
		// Custom global variable.
		global $_beans_extension_widget;

		$widgets = self::__get_widget_area('widget');
		$_beans_extension_widget = $widgets[$id];

	}// Method


	/**
		[ORIGINAL]
			_beans_reset_widget_area()
		@access (public)
			Reset widget area global data.
		@global (array) $_beans_extension_widget_area
			Widget area global.
		@return (void)
	*/
	public static function __reset_widget_area()
	{
		unset($GLOBALS['_beans_extension_widget_area']);

	}// Method


	/**
		[ORIGINAL]
			_beans_reset_widget()
		@access (public)
			Reset widget global data.
		@global (array) $_beans_extension_widget
			Widget global.
		@return (void)
	*/
	public static function __reset_widget()
	{
		unset($GLOBALS['_beans_extension_widget']);

	}// Method


	/**
		[ORIGINAL]
			_beans_widget_area_subfilters()
		@access (public)
			Build widget area sub-filters.
		@global (array) $_beans_extension_widget_area
			Widget area global.
		@return (string)
	*/
	public static function __widget_area_subfilter()
	{
		// Custom global variable.
		global $_beans_extension_widget_area;

		return '[_' . $_beans_extension_widget_area['id'] . ']';

	}// Method


	/**
		[ORIGINAL]
			_beans_widget_subfilters()
		@access (public)
			Build widget sub-filters.
		@global (array) $_beans_extension_widget_area
			Widget area global.
		@global (array) $_beans_extension_widget
			Widget global.
		@return (string)
	*/
	public static function __widget_subfilter()
	{
		// Custom global variable.
		global $_beans_extension_widget_area;
		global $_beans_extension_widget;

		$subfilters = array(
			// Add sidebar id.
			$_beans_extension_widget_area['id'],
			// Add widget type.
			$_beans_extension_widget['type'],
			// Add widget id.
			$_beans_extension_widget['id'],
		);

		return '[_' . implode('][_',$subfilters) . ']';

	}// Method


	/* Hook
	_________________________
	*/
	public function force($widget,$instance,$args)
	{
		/**
			[ORIGINAL]
				_beans_force_the_widget()
			@access (public)
				Force atypical widget added using the_widget() to have a correctly registered id.
			@global (WP_Widget_Factory) $wp_widget_factory
				Singleton that registers and instantiates WP_Widget classes.
				https://developer.wordpress.org/reference/classes/wp_widget_factory/
			@param (string) $widget
				The widget's PHP class name (see class-wp-widget.php).
			@param (array) $instance
				[Optional]
				The widget's instance settings.
				[Default] empty array
			@param (array) $args
				Array of arguments to configure the display of the widget.
			@return (void)
			@reference
				[Plugin]/utility/beans.php
		*/

		// WP global.
		global $wp_widget_factory;

		$widget_obj = $wp_widget_factory->widgets[$widget];

		/**
		 * @reference (WP)
		 * 	Core base class extended to register widgets.
		 * 	https://developer.wordpress.org/reference/classes/wp_widget/
		*/
		if(!$widget_obj instanceof WP_Widget){return;}

		// Stop here if the widget correctly contains an id.
		if(FALSE !== stripos(_beans_utility::__get_global_value('before_widget',$args),$widget_obj->id)){return;}

		printf('<!--widget-%1$s-->',esc_attr($widget_obj->id));

	}// Method


}// Class
endif;
_beans_widget::__get_instance();
