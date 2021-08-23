<?php
/**
 * Beans admin page.
 * This class build the Beans admin page.
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
if(class_exists('_beans_admin') === FALSE) :
final class _beans_admin
{
/**
 * @since 1.0.1
 * 	Beans admin page.
 * 	This class build the Beans admin page.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	set_group()
 * 	load_dependency()
 * 	add_menu_page()
 * 	render()
 * 	register_mode_option()
 * 	register_term_meta()
 * 	register_post_meta()
 * 	register_theme_mod()
*/

	/**
		@access (private)
		@var (string) $plugin_name
			The ID of this plugin.
		@var (string) $version
			The current version of this plugin.
		@var (string) $admin_url
			https://developer.wordpress.org/reference/files/wp-admin/admin.php/
		@var (array) $group
			A settings group name.
			Should correspond to an allowed option key name.
	*/
	protected $plugin_name = '';
	protected $version = '';
	private $admin_url = '';
	private $group = array();

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
				[Plugin]/include/constant.php
		*/

		// Init properties.
		if(defined('BEANS_EXTENSION_VERSION')){
			$this->version = BEANS_EXTENSION_VERSION;
		}
		else{
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'beans-extension';
		$this->admin_url = 'admin.php?page=beans_extension_setting';
		$this->group = $this->set_group();

		// Load dependencies.
		$this->load_dependency();

		// Register hooks.
		add_action('admin_menu',[$this,'add_menu_page'],150);
		add_action('admin_enqueue_scripts',[$this,'admin_enqueue_scripts']);
		add_action('admin_init',[$this,'register_mode_option'],20);
		add_action('admin_init',[$this,'register_term_meta']);
		add_action('admin_init',[$this,'register_post_meta']);
		add_action('customize_register',[$this,'register_theme_mod']);

	}// Method


	/* Setter
	_________________________
	*/
	private function set_group()
	{
		/**
			@access (private)
				Define groups for Admin tabs and setting API group.
				https://developer.wordpress.org/reference/functions/register_setting/
			@return (array)
			@reference
				[Plugin]/admin/tab/xxx.php
		*/
		return array(
			'api' => esc_html__('Beans Api','beans-extension'),
			'column' => esc_html__('Column','beans-extension'),
			'image' => esc_html__('Image','beans-extension'),
			'layout' => esc_html__('Layout','beans-extension'),
			'cleaner' => esc_html__('Head Cleaner','beans-extension'),
			'backup' => esc_html__('Export','beans-extension'),
		);

	}// Method


	/* Method
	_________________________
	*/
	private function load_dependency()
	{
		/**
			@access (private)
				Include required files beforehand.
			@return (void)
			@reference
				[Plugin]/include/constant.php
		*/

		// Trait file for the admin.
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/helper/trait.php';

		// Helper functions for the admin.
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/helper/echo.php';
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/helper/misc.php';
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/helper/update.php';

		// Data definition for the admin.
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/data/source.php';
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/data/default.php';
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/data/option.php';

		// Groups for the admin.
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/general.php';
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/preview.php';
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/backup.php';
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/cleaner.php';
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/column.php';
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/image.php';
		require_once BEANS_EXTENSION_API_PATH['admin'] . 'tab/layout.php';

	}// Method


	/* Hook
	_________________________
	*/
	public function add_menu_page()
	{
		/**
			@access (public)
				Add a top-level menu page.
				https://developer.wordpress.org/reference/functions/add_menu_page/
			@return (void)
		*/
		add_menu_page(
			esc_html('Beans Framework Extension'),
			esc_html('Beans Extension'),
			'manage_options',
			'beans_extension_setting',
			[$this,'render'],
			plugins_url('asset/image/logo.png',dirname(__FILE__)),
			50
		);

	}// Method


	/* Hook
	_________________________
	 */
	public function admin_enqueue_scripts()
	{
		/**
			@access (public)
				Enqueue scripts for all admin pages.
				https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
			@return (void)
		*/

		/**
		 * @reference (WP)
		 * 	Determines whether the current request is for an administrative interface page.
		 * 	https://developer.wordpress.org/reference/functions/is_admin/
		*/
		if(is_admin()){
			wp_enqueue_style($this->plugin_name,plugins_url('asset/css/admin.min.css',dirname(__FILE__)),$this->version,'all');
			wp_enqueue_script($this->plugin_name,plugins_url('asset/js/admin.min.js',dirname(__FILE__)),array('jquery'),$this->version,TRUE);
			/**
			 * @reference (WP)
			 * 	Enqueues all scripts, styles, settings, and templates necessary to use all media JS APIs.
			 * 	https://developer.wordpress.org/reference/functions/wp_enqueue_media/
			*/
			wp_enqueue_media();
		}

	}// Method


	/* Method
	_________________________
	*/
	public function render()
	{
		/**
			@access (public)
				Render Admin tab pages.
			@return (void)
			@reference
				[Plugin]/api/option/beans.php
				[Plugin]/admin/tab/backup.php
				[Plugin]/admin/tab/column.php
				[Plugin]/admin/tab/general.php
				[Plugin]/admin/tab/image.php
				[Plugin]/admin/tab/layout.php
				[Plugin]/admin/tab/preview.php
		*/

		/**
		 * @reference (WP)
		 * 	Returns whether the current user has the specified capability.
		 * 	https://developer.wordpress.org/reference/functions/current_user_can/
		*/
		if(!current_user_can('manage_options')){
			wp_die(esc_html__('You do not have Sufficient Permissions to Access this Admin Page.','beans-extension'));
		}

		foreach((array)$this->group as $key => $value){
			$option[$key] = (isset($_GET['tab']) && ($_GET['tab'] === $key)) ? TRUE : FALSE;
		}
		?>
		<div class="wrap">
			<div class="wrap-content-main bx-container">
				<h1 class="bx-headline"><?php echo esc_html__('Plugin Settings','beans-extension'); ?>
					<span style="float: right; font-size: 12px;"><?php echo esc_html__('Version ','beans-extension'); ?><?php echo esc_attr($this->version); ?></span>
				</h1>
				<?php
				/**
				 * @reference (WP)
				 * 	Display settings errors registered by add_settings_error().
				 * 	https://developer.wordpress.org/reference/functions/settings_errors/
				*/
				settings_errors();
				?>

				<!-- Tabs -->
				<h2 class="nav-tab-wrapper">
					<!-- Preview -->
					<a href="<?php echo esc_url(admin_url($this->admin_url)); ?>" class="nav-tab<?php if(!isset($_GET['tab']) || (isset($_GET['tab']) && ($_GET['tab'] !== 'api') && ($_GET['tab'] !== 'column') && ($_GET['tab'] !== 'image') && ($_GET['tab'] !== 'layout') && ($_GET['tab'] !== 'cleaner') && ($_GET['tab'] !== 'backup'))){esc_attr_e(' nav-tab-active');} ?>"><?php echo __('Preview','beans-extension'); ?></a>

					<?php foreach((array)$this->group as $key => $value) : ?>
						<a href="<?php echo esc_url(add_query_arg(['tab' => $key],esc_url(admin_url($this->admin_url)))); ?>" class="nav-tab<?php if($option[$key]){esc_attr_e(' nav-tab-active');} ?>"><?php echo $value; ?></a>
					<?php endforeach; ?>
				</h2><!-- .nav-tab-wrapper -->

				<?php
				if($option['api']){
					_beans_option::__render('beans_extension_setting');
				}
				elseif($option['column']){
					?><form method="post" action="options.php" class="bx-form"><?php
					_beans_admin_column_tab::__render();
					?></form><?php
				}
				elseif($option['image']){
					?><form method="post" action="options.php" class="bx-form"><?php
					_beans_admin_image_tab::__render();
					?></form><?php
				}
				elseif($option['layout']){
					?><form method="post" action="options.php" class="bx-form"><?php
					_beans_admin_layout_tab::__render();
					?></form><?php
				}
				elseif($option['cleaner']){
					?><form method="post" action="options.php" class="bx-form"><?php
					_beans_admin_cleaner_tab::__render();
					?></form><?php
				}
				elseif($option['backup']){
					?><form method="post" action="options.php" class="bx-form"><?php
					_beans_admin_backup_tab::__render();
					?></form><?php
				}
				else{
					_beans_admin_preview_tab::__render();
				}
				?>
			</div><!-- .wrap-content-main -->
		</div><!-- .wrap -->

	<?php
	}// Method


	/* Hook
	_________________________
	*/
	public function register_mode_option()
	{
		/**
			@access (public)
				Register Beans HTML development mode option to Admin API tab.
					wp-admin/admin.php?page=beans_extension_setting&tab=api
			@global (array) $wp_meta_boxes
				https://developer.wordpress.org/apis/handbook/dashboard-widgets/
			@return (void)
			@reference
				[Plugin]/api/option/beans.php
				[Plugin]/utility/beans.php
		*/

		// WP global.
		global $wp_meta_boxes;

		$field = array(
			array(
				'id' => 'beans_extension_dev_mode',
				'label' => esc_html__('Enable Development Mode','beans-extension'),
				'checkbox_label' => esc_html__('Select to Activate Development Mode.','beans-extension'),
				'type' => 'checkbox',
				'description' => esc_html__('This Option should be enabled while your Website is in Development.','beans-extension'),
			),
		);

		/**
		 * @reference (Beans)
		 * 	Register options.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_options/
		*/
		_beans_option::__register(
			$field,
			'beans_extension_setting',
			'beans_extension_mode_option',
			array(
				'title' => esc_html__('Mode Option','beans-extension'),
				'context' => _beans_utility::__get_global_value('beans_extension_setting',$wp_meta_boxes) ? 'column' : 'normal',
			));

	}// Method


	/* Hook
	_________________________
	*/
	public function register_term_meta()
	{
		/**
			[ORIGINAL]
				beans_do_register_term_meta()
				https://www.getbeans.io/code-reference/functions/beans_do_register_term_meta/
			@access (public)
				Add Beans layout option to WP category and tags editor.
				This provides for backward compatibility with legacy Beans settings.
					wp-admin/term.php?taxonomy=category
					wp-admin/term.php?taxonomy=post_tag
			@return (void)
			@reference
				[Plugin]/api/layout/beans.php
				[Plugin]/api/term-meta/beans.php
		*/
		$option = get_option(BEANS_EXTENSION_PREFIX['option'] . 'layout');
		$term_meta = BEANS_EXTENSION_PREFIX['setting'] . 'layout_term_meta';
		if(!isset($option[$term_meta]) || (isset($option[$term_meta]) && !$option[$term_meta])){return;}

		/**
		 * @reference (Beans)
		 * 	Get layout option without default for the count.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_layouts_for_options/
		*/
		$option = _beans_layout::__get_setting();

		// 	Stop here if there is less than two layouts options.
		if(count($option) < 2){return;}

		$field = array(
			array(
				'id' => 'beans_extension_layout',
				'type' => 'radio',
				'label' => _x('Layout','term meta',$this->plugin_name),
				'default' => 'default_fallback',
				'option' => _beans_layout::__get_setting(TRUE),
			),
		);

		/**
		 * @reference (Beans)
		 * 	Register Term Meta.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_term_meta/
		*/
		_beans_term_meta::__register($field,array('category','post_tag'),$this->plugin_name);

	}// Method


	/* Hook
	_________________________
	*/
	public function register_post_meta()
	{
		/**
			[ORIGINAL]
				beans_do_register_post_meta()
				https://www.getbeans.io/code-reference/functions/beans_do_register_post_meta/
			@access (public)
				Add Beans layout option to WP post editor.
				This provides for backward compatibility with legacy Beans settings.
					wp-admin/post.php
			@return (void)
			@reference
				[Plugin]/api/layout/beans.php
				[Plugin]/api/post-meta/beans.php
		*/
		$option = get_option(BEANS_EXTENSION_PREFIX['option'] . 'layout');
		$post_meta = BEANS_EXTENSION_PREFIX['setting'] . 'layout_post_meta';
		if(!isset($option[$post_meta]) || (isset($option[$post_meta]) && !$option[$post_meta])){return;}

		/**
		 * @reference (Beans)
		 * 	Get layout option without default for the count.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_layouts_for_options/
		*/
		$option = _beans_layout::__get_setting();

		// Stop here if there are less than two layout options.
		if(count($option) < 2){return;}

		$field = array(
			array(
				'id' => 'beans_extension_layout',
				'type' => 'radio',
				'label' => _x('Layout','post meta',$this->plugin_name),
				'default' => 'default_fallback',
				'option' => _beans_layout::__get_setting(TRUE),
			),
		);

		/**
		 * @reference (Beans)
		 * 	Register Post Meta.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_post_meta/
		*/
		_beans_post_meta::__register(
			// Array of fields to register.
			$field,
			// 	Array of 'post type ID(s)','post ID(s)' or 'page template slug(s)' for which the post meta should be registered.
			array('post','page'),
			// A section ID to define the group of fields.
			$this->plugin_name,
			// The metabox Title.
			array('title' => esc_html__('Post Option','beans-extension'))
		);

	}// Method


	/* Hook
	_________________________
	*/
	public function register_theme_mod()
	{
		/**
			[ORIGINAL]
				beans_do_register_wp_customize_options()
				https://www.getbeans.io/code-reference/functions/beans_do_register_wp_customize_options/
			@access (public)
				Add Beans layout option to WP theme customizer.
				This provides for backward compatibility with legacy Beans settings.
					wp-admin/customize.php
			@global (array) $_beans_extension_component_setting
				API components setting global.
			@return (void)
			@reference
				[Plugin]/api/layout/beans.php
				[Plugin]/api/customizer/beans.php
		*/

		// Custom global variable.
		global $_beans_extension_component_setting;

		// Check if Beans customizer component is inactive.
		if(isset($_beans_extension_component_setting['stop_customizer']) && ($_beans_extension_component_setting['stop_customizer'])){return;}

		$field = array(
			array(
				'id' => 'beans_logo_image',
				'label' => esc_html__('Beans Logo','beans-extension'),
				'type' => 'WP_Customize_Image_Control',
			),
		);

		/**
		 * @reference (Beans)
		 * 	Register WP Customize Options.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_wp_customize_options/
		*/
		_beans_customizer::__register(
			$field,
			'title_tagline',
			array(
				'title' => esc_html__('Branding','beans-extension')
			));

		/**
		 * @reference (Beans)
		 * 	Get layout option without default for the count.
		 * 	https://www.getbeans.io/code-reference/functions/beans_get_layouts_for_options/
		*/
		$option = _beans_layout::__get_setting();

		// Only show the layout options if more than two layouts are registered.
		if(count($option) > 2){
			$field = array(
				array(
					'id' => 'beans_extension_layout',
					'label' => esc_html__('Default Layout','beans-extension'),
					'type' => 'radio',
					/**
					 * @reference (Beans)
					 * 	Get the default layout ID.
					 * 	https://www.getbeans.io/code-reference/hooks/beans_default_layout/
					*/
					'default' => _beans_layout::__get_default(),
					'option' => $option,
				),
			);

			/**
			 * @reference (Beans)
			 * 	Register WP Customize Options.
			 * 	https://www.getbeans.io/code-reference/functions/beans_register_wp_customize_options/
			*/
			_beans_customizer::__register(
				$field,
				'beans_extension_layout',
				array(
					'title' => '[Beans] ' . __('Default Layout','beans-extension'),
					'priority' => 1500,
				));
		}

		$enable_viewport_width = array(
			'id' => 'beans_enable_viewport_width',
			'label' => esc_html__('Enable to change the Viewport Width.','beans-extension'),
			'type' => 'activation',
			'default' => FALSE,
		);
		$viewport_width = array(
			'id' => 'beans_viewport_width',
			'type' => 'slider',
			'default' => 1000,
			'min' => 300,
			'max' => 2500,
			'interval' => 10,
			'unit' => 'px',
		);
		$enable_viewport_height = array(
			'id' => 'beans_enable_viewport_height',
			'label' => esc_html__('Enable to change the Viewport Height.','beans-extension'),
			'type' => 'activation',
			'default' => FALSE,
		);
		$viewport_height = array(
			'id' => 'beans_viewport_height',
			'type' => 'slider',
			'default' => 1000,
			'min' => 300,
			'max' => 2500,
			'interval' => 10,
			'unit' => 'px',
		);

		$field = array(
			array(
				'id' => 'beans_viewport_width_group',
				'label' => esc_html__('Viewport Width - for Previewing Only','beans-extension'),
				'description' => esc_html__('Slide Left or Right to change the Viewport Width. Publishing will not change the Width of your Website.','beans-extension'),
				'type' => 'group',
				'transport' => 'postMessage',
				'field' => array(
					$enable_viewport_width,
					$viewport_width
				),
			),
			array(
				'id' => 'beans_viewport_height_group',
				'label' => esc_html__('Viewport Height - for Previewing Only','beans-extension'),
				'description' => esc_html__('Slide Left or Right to change the Viewport Height. Publishing will not change the Height of your Website.','beans-extension'),
				'type' => 'group',
				'transport' => 'postMessage',
				'field' => array(
					$enable_viewport_height,
					$viewport_height
				),
			),
		);

		/**
		 * @reference (Beans)
		 * 	Register WP Customize Options.
		 * 	https://www.getbeans.io/code-reference/functions/beans_register_wp_customize_options/
		*/
		_beans_customizer::__register(
			$field,
			'beans_extension_preview',
			array(
				'title' => '[Beans] ' . esc_html__('Preview Tools','beans-extension'),
				'priority' => 1510,
			));

	}// Method


}// Class
endif;
_beans_admin::__get_instance();
