<?php
/**
 * Build tab/group of admin page.
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
if(class_exists('_beans_admin_image_tab') === FALSE) :
class _beans_admin_image_tab
{
/**
 * @since 1.0.1
 * 	Build the admin tab menu content.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_hook()
 * 	invoke_hook()
 * 	__render()
 * 	register()
 * 	- sanitize()
 * 	- describe()
 * 	- markup()
 * 	update()
 * 	enqueue()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $_class
			Name/Identifier with prefix.
		@var (string) $_index
			Name/Identifier without prefix.
		@var (array) $_section
			Part of the Settings API.
		@var (array) $_setting
			Part of the Settings API.
		@var (array) $hook
			The collection of hooks that is being registered (that is, actions or filters).
	*/
	private static $_class = '';
	private static $_index = '';
	private static $_section = array();
	private static $_setting = array();
	private $hook = array();

	/**
	 * Traits.
	*/
	use _trait_singleton;
	use _trait_hook;
	use _trait_admin;


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
		self::$_index = basename(__FILE__,'.php');
		self::$_section = $this->set_section(self::$_index);
		self::$_setting = $this->set_setting(self::$_section);

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
				Fires as an admin screen or script is being initialized.
				https://developer.wordpress.org/reference/hooks/admin_init/
			@return (array)
			@reference
				[Plugin]/trait/hook.php
		*/
		return $this->set_parameter_callback(array(
			'register' => array(
				'tag' => 'add_action',
				'hook' => 'admin_init'
			),
			'update' => array(
				'tag' => 'add_action',
				'hook' => 'admin_init'
			),
			'enqueue' => array(
				'tag' => 'add_action',
				'hook' => 'admin_enqueue_scripts'
			),
		));

	}// Method


	/* Method
	_________________________
	*/
	public static function __render()
	{
		/**
			@access (public)
				Render Image tab page.
			@return (void)
			@reference
				[Plugin]/admin/beans.php
				[Plugin]/include/constant.php
				[Plugin]/admin/tab/helper/trait.php
		*/

		/**
		 * @reference (WP)
		 * 	Output nonce, action, and option_page fields for a settings page.
		 * 	https://developer.wordpress.org/reference/functions/settings_fields/
		*/
		// bx_group_{tab_name}
		settings_fields(BEANS_EXTENSION_PREFIX['group'] . self::$_index);

		if(!empty(self::$_section)){
			foreach((array)self::$_section as $key => $value){
				/**
				 * @reference (WP)
				 * 	Prints out all settings sections added to a particular settings page.
				 * 	https://developer.wordpress.org/reference/functions/do_settings_sections/
				*/
				// bx_view_{tab_name}_{section_name}
				do_settings_sections(BEANS_EXTENSION_PREFIX['view'] . self::$_index . '_' . $key);
			}
		}

		/**
		 * @reference (WP)
		 * 	Echo submit button, with provided text and appropriate class(es).
		 * 	https://developer.wordpress.org/reference/functions/submit_button/
		*/
		submit_button();

	}// Method


	/* Hook
	_________________________
	*/
	public function register()
	{
		/**
			@access (public)
			@return (void)
				[Plugin]/include/constant.php
				[Plugin]/admin/tab/helper/trait.php
		*/

		/**
		 * @reference (WP)
		 * 	Registers a setting and its data.
		 * 	https://developer.wordpress.org/reference/functions/register_setting/
		*/
		register_setting(
			// bx_group_{tab_name}
			BEANS_EXTENSION_PREFIX['group'] . self::$_index,
			// bx_option_{tab_name}
			BEANS_EXTENSION_PREFIX['option'] . self::$_index,
			[$this,'sanitize']
		);

		foreach((array)self::$_section as $section_key => $section_value){
			/**
			 * @reference (WP)
			 * 	Add a new section to a settings page.
			 * 	https://developer.wordpress.org/reference/functions/add_settings_section/
			*/
			add_settings_section(
				// bx_section_{tab_name}_{section_name}
				BEANS_EXTENSION_PREFIX['section'] . self::$_index . '_' . $section_key,
				$section_value['title'],
				function() use($section_value){
					$this->describe($section_value['description']);
				},
				// bx_view_{tab_name}_{section_name}
				BEANS_EXTENSION_PREFIX['view'] . self::$_index . '_' . $section_key 
			);

			foreach((array)self::$_setting[$section_key] as $setting_key => $setting_value){
				/**
				 * @reference (WP)
				 * 	Add a new field to a section of a settings page.
				 * https://developer.wordpress.org/reference/functions/add_settings_field/
				*/
				add_settings_field(
					// bx_setting_{tab_name}_{setting_name}
					BEANS_EXTENSION_PREFIX['setting'] . self::$_index . '_' . $setting_key,
					$setting_value['label'],
					function() use($setting_key,$setting_value){
						$this->markup($setting_key,$setting_value);
					},
					// bx_view_{tab_name}_{section_name}
					BEANS_EXTENSION_PREFIX['view'] . self::$_index . '_' . $section_key,
					// bx_section_{tab_name}_{section_name}
					BEANS_EXTENSION_PREFIX['section'] . self::$_index . '_' . $section_key
				);
			}
		}

	}// Method


	/* Hook
	_________________________
	*/
	public function update()
	{
		/**
			@access (public)
			@return (void)
			@reference
				[Plugin]/include/constant.php
				[Plugin]/admin/tab/helper/update.php
				[Plugin]/admin/tab/app/image.php
		*/
		// bx_option_{tab_name}
		$option = get_option(BEANS_EXTENSION_PREFIX['option'] . self::$_index);
		if(empty($option)){return;}

		foreach((array)self::$_section as $section_key => $section_value){
			if($section_key !== 'configuration'){continue;}

			foreach((array)self::$_setting[$section_key] as $setting_key => $setting_value){

				// bx_setting_{tab_name}_{setting_name}
				$needle = BEANS_EXTENSION_PREFIX['setting'] . self::$_index . '_' . $setting_key;
				$data = isset($option[$needle]) ? $option[$needle] : _beans_admin_default_data::__get_setting(self::$_index,$section_key,$setting_key);
				__beans_admin_update_boolean($setting_key,$data);

				if(($setting_key === 'autosave_thumbnail') && $option[$needle]){
					new \Beans_Extension\_beans_admin_image_app();
				}
			}
		}

	}// Method


	/* Hook
	_________________________
	*/
	public function enqueue()
	{
		/**
			@access (public)
				Enqueues all scripts, styles, settings, and templates necessary to use all media JS APIs.
				https://developer.wordpress.org/reference/functions/wp_enqueue_media/
			@return (void)
				[Plugin]/include/constant.php
		*/

		/**
		 * @reference (WP)
		 * 	Whether the site is being previewed in the Customizer.
		 * 	https://developer.wordpress.org/reference/functions/is_customize_preview/
		*/
		if(is_customize_preview()){return;}

		/**
		 * @reference (WP)
		 * 	Enqueues all scripts, styles, settings, and templates necessary to use all media JS APIs.
		 * 	https://developer.wordpress.org/reference/functions/wp_enqueue_media/
		*/
		if(function_exists('wp_enqueue_media') === FALSE){
			wp_enqueue_media();
		}

		// bx_option_{tab_name}
		$option = get_option(BEANS_EXTENSION_PREFIX['option'] . self::$_index);

		foreach((array)self::$_section as $section_key => $section_value){

			foreach((array)self::$_setting[$section_key] as $setting_key => $setting_value){

				// bx_setting_{tab_name}_{setting_name}
				$needle = BEANS_EXTENSION_PREFIX['setting'] . self::$_index . '_' . $setting_key;
				$button = BEANS_EXTENSION_PREFIX['setting'] . self::$_index . '_' . $setting_key . '_button';
				$image = BEANS_EXTENSION_PREFIX['setting'] . self::$_index . '_' . $setting_key . '_image';
				?>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
				<script type = "text/javascript">
					(function($){
						$(function(){
							jQuery("#<?php echo $button; ?>").click(_bx_render_media);
							var media_uploader = null;
							function _bx_render_media(){
								media_uploader = wp.media({
									frame:"post",
									state:"insert",
									multiple:false
								});
								media_uploader.on("insert",function(){
									var json = media_uploader.state().get("selection").first().toJSON();
									var image_url = json.url;
									jQuery("#<?php echo $needle; ?>").val(image_url);
									jQuery("#<?php echo $image; ?>").attr("src",image_url);
								});
								media_uploader.open();
							};
						});
					}(jQuery));
				</script>
			<?php
			}
		}

	}// Method


}// Class
endif;
// new _beans_admin_image_tab();
_beans_admin_image_tab::__get_instance();
