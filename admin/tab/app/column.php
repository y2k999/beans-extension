<?php
/**
 * Define application module for the tab/group.
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
if(class_exists('_beans_admin_column_app') === FALSE) :
class _beans_admin_column_app
{
/**
 * @since 1.0.1
 * 	Update admin columns.
 * 
 * [TOC]
 * 	__construct()
 * 	set_hook()
 * 	invoke_hook()
 * 	unset_columns_post()
 * 	unset_columns_page()
 * 	wp_unique_post_slug()
 * 	add_id_post()
 * 	add_thumbnail_post()
 * 	add_wordcount_post()
 * 	add_pv_post()
 * 	render_id_post()
 * 	render_thumbnail_post()
 * 	render_wordcount_post()
 * 	render_pv_post()
 * 	metabox_pv_post()
 * 	orderby_pv_post()
 * 	sortable_pv_post()
 * 	add_id_page()
 * 	add_thumbnail_page()
 * 	add_template_page()
 * 	add_slug_page()
 * 	render_id_page()
 * 	render_thumbnail_page()
 * 	render_template_page()
 * 	render_slug_page()
 * 	add_follow_profile()
 * 	__set_post_view()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $_class
			Name/Identifier with prefix.
		@var (string) $_index
			Name/Identifier without prefix.
		@var (array) $hook
			The collection of hooks that is being registered (that is, actions or filters).
		@var (string) $pv_key
			Custom field for the PV.
	*/
	private static $_class = '';
	private static $_index = '';
	private $hook = array();
	private static $_pv_key = 'post_views_count';

	/**
	 * Traits.
	*/
	use _trait_hook;


	/* Constructor
	_________________________
	*/
	public function __construct()
	{
		/**
			@access (public)
				Class Constructor.
			@return (void)
		*/

		// Init properties.
		self::$_class = __utility_get_class(get_class($this));
		self::$_index = basename(__FILE__,'.php');

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
			@return (array)
				The collection of hooks that is being registered (that is, actions or filters).
			@reference
				[Plugin]/trait/hook.php
				[Plugin]/include/constant.php
		*/
		// bx_option_{tab_name}
		$option = get_option(BEANS_EXTENSION_PREFIX['option'] . self::$_index);
		if(empty($option)){return;}

		return $this->set_parameter_callback(array(
			/**
			 * @since 1.0.1
			 * 	Post Column
			*/
			'wp_unique_post_slug' => array(
				'tag' => 'add_filter',
				'hook' => 'wp_unique_post_slug',
				'priority' => 10,
				'args' => 4
			),
			// Add new columns
			'add_id_post' => array(
				'tag' => 'add_filter',
				'hook' => 'manage_posts_columns'
			),
			'add_wordcount_post' => array(
				'tag' => 'add_filter',
				'hook' => 'manage_posts_columns'
			),
			'add_thumbnail_post' => array(
				'tag' => 'add_filter',
				'hook' => 'manage_posts_columns'
			),
			'add_pv_post' => array(
				'tag' => 'add_filter',
				'hook' => 'manage_posts_columns'
			),
			'orderby_pv_post' => array(
				'tag' => 'add_filter',
				'hook' => 'request'
			),
			'sortable_pv_post' => array(
				'tag' => 'add_filter',
				'hook' => 'manage_edit-post_sortable_columns'
			),
			// View new columns
			'render_id_post' => array(
				'tag' => 'add_action',
				'hook' => 'manage_posts_custom_column',
				'args' => 2
			),
			'render_wordcount_post' => array(
				'tag' => 'add_action',
				'hook' => 'manage_posts_custom_column',
				'args' => 2
			),
			'render_thumbnail_post' => array(
				'tag' => 'add_action',
				'hook' => 'manage_posts_custom_column',
				'args' => 2
			),
			'render_pv_post' => array(
				'tag' => 'add_action',
				'hook' => 'manage_posts_custom_column',
				'args' => 2
			),
			'metabox_pv_post' => array(
				'tag' => 'add_action',
				'hook' => 'block_editor_meta_box_hidden_fields'
			),
			/**
			 * @since 1.0.1
			 * 	Page Column
			*/
			// Add new columns
			'add_id_page' => array(
				'tag' => 'add_filter',
				'hook' => 'manage_pages_columns'
			),
			'add_thumbnail_page' => array(
				'tag' => 'add_filter',
				'hook' => 'manage_pages_columns'
			),
			'add_template_page' => array(
				'tag' => 'add_filter',
				'hook' => 'manage_pages_columns'
			),
			'add_slug_page' => array(
				'tag' => 'add_filter',
				'hook' => 'manage_pages_columns'
			),
			// View new columns
			'render_id_page' => array(
				'tag' => 'add_action',
				'hook' => 'manage_pages_custom_column',
				'args' => 2
			),
			'render_thumbnail_page' => array(
				'tag' => 'add_action',
				'hook' => 'manage_pages_custom_column',
				'args' => 2
			),
			'render_template_page' => array(
				'tag' => 'add_action',
				'hook' => 'manage_pages_custom_column',
				'args' => 2
			),
			'render_slug_page' => array(
				'tag' => 'add_action',
				'hook' => 'manage_pages_custom_column',
				'args' => 2
			),
			/**
			 * @since 1.0.1
			 * 	User Profile
			*/
			'add_follow_profile' => array(
				'tag' => 'add_filter',
				'hook' => 'user_contactmethods'
			),
		));

	}// Method


	/* Hook
	_________________________
	*/
	public function wp_unique_post_slug($slug,$post_ID,$post_status,$post_type)
	{
		/**
			@access (public)
				Filters the unique post slug.
				https://developer.wordpress.org/reference/hooks/wp_unique_post_slug/
			@param (string) $slug
				The desired slug (post_name).
			@param (string) $post_ID
				Post ID.
			@param (string) $post_status
				No uniqueness checks are made if the post is still draft or pending.
			@param (string) $post_type
				Post type.
			@return (string)
				Unique slug for the post, based on $post_name (with a -1, -2, etc. suffix)
		*/
		if(preg_match('/(%[0-9a-f]{2})+/',$slug)){
			/**
			 * @reference (WP)
			 * 	Encode the Unicode values to be used in the URI.
			 * 	https://developer.wordpress.org/reference/functions/utf8_uri_encode/
			*/
			$slug = utf8_uri_encode($post_type) . '-' . $post_ID;
		}
		return $slug;

	}// Method


	/* Hook
	_________________________
	*/
	public function unset_columns_post($post_columns)
	{
		/**
			@access (public)
				Filters the columns displayed in the Pages list table.
				https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_pages_columns
				 - 'cb',
				 - 'title',
				 - 'author',
				 - 'categories',
				 - 'tags',
				 - 'comments',
				 - 'date',
			@param (string[]) $post_columns
				An associative array of column headings.
			@return (string[])
			@reference
				[Plugin]/admin/tab/helper/misc.php
		*/
		if(!__beans_admin_boolean_check(self::$_index,'id_post') && !__beans_admin_boolean_check(self::$_index,'thumbnail_post') && !__beans_admin_boolean_check(self::$_index,'wordcount_post') && !__beans_admin_boolean_check(self::$_index,'pv_post')){
			return $post_columns;
		}
		// unset($post_columns['title']);
		// unset($post_columns['cb']);
		unset($post_columns['author']);
		// unset($post_columns['categories']);
		unset($post_columns['tags']);
		unset($post_columns['comments']);
		// unset($post_columns['date']);

		return $post_columns;

	}// Method


	/* Hook
	_________________________
	*/
	public function unset_columns_page($post_columns)
	{
		/**
			@access (public)
			@param (string[]) $post_columns
				An associative array of column headings.
			@return (string[])
			@reference
				[Plugin]/admin/tab/helper/misc.php
		*/
		if(!__beans_admin_boolean_check(self::$_index,'id_page') && !__beans_admin_boolean_check(self::$_index,'thumbnail_page') && !__beans_admin_boolean_check(self::$_index,'template_page') && !__beans_admin_boolean_check(self::$_index,'slug_page')){
			return $post_columns;
		}
		// unset($post_columns['date']);
		unset($post_columns['author']);
		unset($post_columns['comments']);

		return $post_columns;

	}// Method


	/* Hook
	_________________________
	*/
	public function add_id_post($post_columns)
	{
		/**
			@access (public)
				Filters the columns displayed in the Posts list table.
				https://developer.wordpress.org/reference/hooks/manage_posts_columns/
			@param (string[]) $post_columns
				An associative array of column headings.
			@return (string[])
			@reference
				[Plugin]/admin/tab/helper/misc.php
		*/
		if(!__beans_admin_boolean_check(self::$_index,'id_post')){
			return $post_columns;
		}

		foreach($post_columns as $key => $value){
			if($key == 'categories'){
				$post_columns['postid'] = esc_html__('ID','beans-extension');
			}
			$post_columns[$key] = $value;
		}
		return $post_columns;

	}// Method


	/* Hook
	_________________________
	*/
	public function add_thumbnail_post($post_columns)
	{
		/**
			@access (public)
				Filters the columns displayed in the Posts list table.
				https://developer.wordpress.org/reference/hooks/manage_posts_columns/
			@param (string[]) $post_columns
				An associative array of column headings.
			@return (string[])
			@reference
				[Plugin]/admin/tab/helper/misc.php
		*/
		if(!__beans_admin_boolean_check(self::$_index,'thumbnail_post')){
			return $post_columns;
		}

		foreach($post_columns as $key => $value){
			if($key == 'date'){
				$post_columns['bx_thumbnail_post'] = esc_html__('Thumbnail','beans-extension');
			}
			$post_columns[$key] = $value;
		}
		return $post_columns;

	}// Method


	/* Hook
	_________________________
	*/
	public function add_wordcount_post($post_columns)
	{
		/**
			@access (public)
				Filters the columns displayed in the Posts list table.
				https://developer.wordpress.org/reference/hooks/manage_posts_columns/
			@param (string[]) $post_columns
				An associative array of column headings.
			@return (string[])
			@reference
				[Plugin]/admin/tab/helper/misc.php
		*/
		if(!__beans_admin_boolean_check(self::$_index,'wordcount_post')){
			return $post_columns;
		}

		foreach($post_columns as $key => $value){
			if($key == 'date'){
				$post_columns['bx_wordcount_post'] = esc_html__('Word count','beans-extension');
			}
			$post_columns[$key] = $value;
		}
		return $post_columns;

	}// Method


	/* Hook
	_________________________
	*/
	public function add_pv_post($post_columns)
	{
		/**
			@access (public)
				Filters the columns displayed in the Posts list table.
				https://developer.wordpress.org/reference/hooks/manage_posts_columns/
			@param (string[]) $post_columns
				An associative array of column headings.
			@return (string[])
			@reference
				[Plugin]/admin/tab/helper/misc.php
		*/
		if(!__beans_admin_boolean_check(self::$_index,'pv_post')){
			return $post_columns;
		}
		$post_columns['post_views_count'] = esc_html__('Post View','beans-extension');

		return $post_columns;

	}// Method


	/* Hook
	_________________________
	*/
	public function render_id_post($column_name,$post_id)
	{
		/**
			@access (public)
				Fires in each custom column in the Posts list table.
				https://developer.wordpress.org/reference/hooks/manage_posts_custom_column/
			@param (string) $column_name
				The name of the column to display.
			@param (int) $post_id
				The current post ID.
			@return (void)
		*/
		if('postid' !== $column_name){return;}
		echo $post_id;

	}// Method


	/* Hook
	_________________________
	*/
	public function render_thumbnail_post($column_name,$post_id)
	{
		/**
			@access (public)
				Fires in each custom column in the Posts list table.
				https://developer.wordpress.org/reference/hooks/manage_posts_custom_column/
			@param (string) $column_name
				The name of the column to display.
			@param (int) $post_id
				The current post ID.
			@return (void)
		*/
		if('bx_thumbnail_post' !== $column_name){return;}

		/**
		 * @reference (WP)
		 * 	Retrieve post thumbnail ID.
		 * 	https://developer.wordpress.org/reference/functions/get_post_thumbnail_id/
		*/
		$thumbnail_id = get_post_thumbnail_id($post_id);
		if($thumbnail_id){
			/**
			 * @reference (WP)
			 * 	Retrieves an image to represent an attachment.
			 * 	https://developer.wordpress.org/reference/functions/wp_get_attachment_image_src/
			*/
			$attachment = wp_get_attachment_image_src($thumbnail_id,'medium');
			if(!empty($attachment)){
				echo '<img src = "' . $attachment[0] . '" width = "100px">';
			}
		}
		else{
			echo esc_html__('No Image.','beans-extension');
		}

	}// Method


	/* Hook
	_________________________
	*/
	public function render_wordcount_post($column_name,$post_id)
	{
		/**
			@access (public)
				Fires in each custom column in the Posts list table.
				https://developer.wordpress.org/reference/hooks/manage_posts_custom_column/
			@param (string) $column_name
				The name of the column to display.
			@param (int) $post_id
				The current post ID.
			@return (void)
		*/
		if('bx_wordcount_post' !== $column_name){return;}

		/**
		 * @reference (WP)
		 * 	Retrieve data from a post field based on Post ID.
		 * 	https://developer.wordpress.org/reference/functions/get_post_field/
		*/
		$post_content = get_post_field('post_content',$post_id);
		$post_content = strip_tags($post_content);
		$wordcount = mb_strlen($post_content);
		echo $wordcount;

	}// Method


	/* Hook
	_________________________
	*/
	public function render_pv_post($column_name,$post_id)
	{
		/**
			@access (public)
				Fires in each custom column in the Posts list table.
				https://developer.wordpress.org/reference/hooks/manage_posts_custom_column/
			@param (string) $column_name
				The name of the column to display.
			@param (int) $post_id
				The current post ID.
			@return (void)
		*/
		if('post_views_count' !== $column_name){return;}

		/**
		 * @reference (WP)
		 * 	Retrieves a post meta field for the given post ID.
		 * 	https://developer.wordpress.org/reference/functions/get_post_meta/
		*/
		$pv = get_post_meta($post_id,'post_views_count',TRUE);
		?>
		<div style = "text-align: center;">
			<?php echo $pv; ?>
		</div>

	<?php
	}// Method


	/* Hook
	_________________________
	*/
	public function metabox_pv_post($post)
	{
		/**
			@access (public)
				Renders the hidden form required for the meta boxes form.
				https://developer.wordpress.org/reference/functions/the_block_editor_meta_box_post_form_hidden_fields/
			@param (WP_Post) $post
				Current post object.
			@return (void)
		*/
		if($post->post_type !== 'post'){return;}
		$pv = $post->post_views_count;
		if(empty($pv)){$pv = 0;}
		?>
		<div style="margin: 20px 0 0 0; padding: 12px;">
			<span><?php echo esc_html__('View counts ：','beans-extension'); ?></span>
			<strong><?php echo $pv; ?> View </strong>
		</div>';
	<?php
	}// Method


	/* Hook
	_________________________
	*/
	public function orderby_pv_post($params)
	{
		/**
			@access (public)
				Filters the array of parsed query variables.
				https://developer.wordpress.org/reference/hooks/request/
			@param (array) $params
			@return (array)
		*/
		if(isset($params['orderby']) && 'post_views_count' == $params['orderby']){
			$params = array_merge($params,array(
				'meta_key' => 'post_views_count',
				'orderby' => 'meta_value_num'
			));
		}
		return $params;

	}// Method


	/* Hook
	_________________________
	*/
	public function sortable_pv_post($sortable_column)
	{
		/**
			@access (public)
				Filters the list table sortable columns for a specific screen.
				https://developer.wordpress.org/reference/hooks/manage_this-screen-id_sortable_columns/
			@param (string) $sortable_column
			@return (string)
		*/
		$sortable_column['post_views_count'] = 'post_views_count';
		return $sortable_column;

	}// Method


	/* Hook
	_________________________
	*/
	public function add_id_page($post_columns)
	{
		/**
			@access (public)
				Filters the columns displayed in the Pages list table.
				https://developer.wordpress.org/reference/hooks/manage_pages_columns/
			@param (string[]) $post_columns
				An associative array of column headings.
			@return (string[])
			@reference
				[Plugin]/admin/tab/helper/misc.php
		*/
		if(!__beans_admin_boolean_check(self::$_index,'id_page')){
			return $post_columns;
		}

		foreach($post_columns as $key => $value){
			if($key == 'date'){
				$post_columns['postid'] = esc_html__('ID','beans-extension');
			}
			$post_columns[$key] = $value;
		}
		return $post_columns;

	}// Method


	/* Hook
	_________________________
	*/
	public function add_thumbnail_page($post_columns)
	{
		/**
			@access (public)
				Filters the columns displayed in the Pages list table.
				https://developer.wordpress.org/reference/hooks/manage_pages_columns/
			@param (string[]) $post_columns
				An associative array of column headings.
			@return (string[])
			@reference
				[Plugin]/admin/tab/helper/misc.php
		*/
		if(!__beans_admin_boolean_check(self::$_index,'thumbnail_page')){
			return $post_columns;
		}

		foreach($post_columns as $key => $value){
			if($key == 'date'){
				$post_columns['bx_thumbnail_page'] = esc_html__('Thumbnail','beans-extension');
			}
			$post_columns[$key] = $value;
		}
		return $post_columns;

	}// Method


	/* Hook
	_________________________
	*/
	public function add_template_page($post_columns)
	{
		/**
			@access (public)
				Filters the columns displayed in the Pages list table.
				https://developer.wordpress.org/reference/hooks/manage_pages_columns/
			@param (string[]) $post_columns
				An associative array of column headings.
			@return (string[])
			@reference
				[Plugin]/admin/tab/helper/misc.php
		*/
		if(!__beans_admin_boolean_check(self::$_index,'template_page')){
			return $post_columns;
		}

		foreach($post_columns as $key => $value){
			if($key == 'date'){
				$post_columns['template'] = esc_html__('Page template','beans-extension');
			}
			$post_columns[$key] = $value;
		}
		return $post_columns;

	}// Method


	/* Hook
	_________________________
	*/
	public function add_slug_page($post_columns)
	{
		/**
			@access (public)
				Filters the columns displayed in the Pages list table.
				https://developer.wordpress.org/reference/hooks/manage_pages_columns/
			@param (string[]) $post_columns
				An associative array of column headings.
			@return (string[])
			@reference
				[Plugin]/admin/tab/helper/misc.php
		*/
		if(!__beans_admin_boolean_check(self::$_index,'slug_page')){
			return $post_columns;
		}
		$post_columns['slug'] = esc_html__('Page slug','beans-extension');

		return $post_columns;

	}// Method


	/* Hook
	_________________________
	*/
	public function render_id_page($column_name,$post_id)
	{
		/**
			@access (public)
				Fires in each custom column on the Posts list table.
				https://developer.wordpress.org/reference/hooks/manage_pages_custom_column/
			@param (string) $column_name
				The name of the column to display.
			@param (int) $post_id
				The current post ID.
			@return (void)
		*/
		if('postid' !== $column_name){return;}
		echo $post_id;

	}// Method


	/* Hook
	_________________________
	*/
	public function render_thumbnail_page($column_name,$post_id)
	{
		/**
			@access (public)
				Fires in each custom column on the Posts list table.
				https://developer.wordpress.org/reference/hooks/manage_pages_custom_column/
			@param (string) $column_name
				The name of the column to display.
			@param (int) $post_id
				The current post ID.
			@return (void)
		*/
		if('bx_thumbnail_page' !== $column_name){return;}

		/**
		 * @reference (WP)
		 * 	Retrieve post thumbnail ID.
		 * 	https://developer.wordpress.org/reference/functions/get_post_thumbnail_id/
		*/
		$thumbnail_id = get_post_thumbnail_id($post_id);
		if($thumbnail_id){
			/**
			 * @reference (WP)
			 * 	Retrieves an image to represent an attachment.
			 * 	https://developer.wordpress.org/reference/functions/wp_get_attachment_image_src/
			*/
			$attachment = wp_get_attachment_image_src($thumbnail_id,'medium');
			echo '<img src = "' . $attachment[0] . '" width = "100px">';
		}
		else{
			echo esc_html__('No Image.','beans-extension');
		}

	}// Method


	/* Hook
	_________________________
	*/
	public function render_template_page($column_name,$post_id)
	{
		/**
			@access (public)
				Fires in each custom column on the Posts list table.
				https://developer.wordpress.org/reference/hooks/manage_pages_custom_column/
			@param (string) $column_name
				The name of the column to display.
			@param (int) $post_id
				The current post ID.
			@return (void)
		*/
		if('template' !== $column_name){return;}

		/**
		 * @reference (WP)
		 * 	Retrieves a post meta field for the given post ID.
		 * 	https://developer.wordpress.org/reference/functions/get_post_meta/
		*/
		$template = get_post_meta($post_id,'_wp_page_template',TRUE);
		echo $template ? $template : 'Default';

	}// Method


	/* Hook
	_________________________
	*/
	public function render_slug_page($column_name,$post_id)
	{
		/**
			@access (public)
				Fires in each custom column on the Posts list table.
				https://developer.wordpress.org/reference/hooks/manage_pages_custom_column/
			@param (string) $column_name
				The name of the column to display.
			@param (int) $post_id
				The current post ID.
			@return (void)
		*/
		if($column_name == 'slug'){
			/**
			 * @reference (WP)
			 * 	Retrieves post data given a post ID or post object.
			 * 	https://developer.wordpress.org/reference/functions/get_post/
			*/
			$post = get_post($post_id);
			$slug = $post->post_name;
			// echo attribute_escape($slug);
			echo esc_attr($slug);
		}

	}// Method


	/* Hook
	_________________________
	*/
	public function add_follow_profile($user_contact)
	{
		/**
			@access (public)
				Filters the user contact methods.
				https://developer.wordpress.org/reference/hooks/user_contactmethods/
			@param (array) $user_contact
			@return (array)
			@reference
				[Plugin]/admin/tab/helper/misc.php
		*/
		if(__beans_admin_boolean_check(self::$_index,'account_twitter') !== FALSE){
			$user_contact['twitter'] = esc_html('Twitter Account');
		}

		if(__beans_admin_boolean_check(self::$_index,'account_facebook') !== FALSE){
			$user_contact['facebook'] = esc_html('Facebook Account');
		}

		if(__beans_admin_boolean_check(self::$_index,'account_instagram') !== FALSE){
			$user_contact['instagram'] = esc_html('Instagram Account');
		}

		if(__beans_admin_boolean_check(self::$_index,'account_github') !== FALSE){
			$user_contact['github'] = esc_html('Github Account');
		}

		if(__beans_admin_boolean_check(self::$_index,'account_youtube') !== FALSE){
			$user_contact['youtube'] = esc_html('YouTube Account');
		}

		return $user_contact;

	}// Method


	/* Method
	_________________________
	*/
	public static function __set_post_view($post_id)
	{
		/**
			@access (public)
			@param (int) $post_id
				Post ID.
			@return (void)
		*/
		/**
		 * @reference (WP)
		 * 	Retrieves a post meta field for the given post ID.
		 * 	https://developer.wordpress.org/reference/functions/get_post_meta/
		*/
		$count = get_post_meta($post_id,self::$_pv_key,TRUE);
		if($count === ''){
			$count = 0;
				/**
				 * @reference (WP)
				 * 	Deletes a post meta field for the given post ID.
				 * 	https://developer.wordpress.org/reference/functions/delete_post_meta/
				 * 	Adds a meta field to the given post.
				 * 	https://developer.wordpress.org/reference/functions/add_post_meta/
				*/
				delete_post_meta($post_id,self::$_pv_key);
				add_post_meta($post_id,self::$_pv_key,'0');
		}
		else{
			$count++;
			/**
			 * @reference (WP)
			 * 	Updates a post meta field based on the given post ID.
			 * 	https://developer.wordpress.org/reference/functions/update_post_meta/
			*/
			update_post_meta($post_id,self::$_pv_key,$count);
		}

	}// Method


}// Class
endif;
// new _beans_admin_column_app();
// _beans_admin_column_app::__get_instance();
