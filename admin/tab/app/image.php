<?php
/**
 * Define application module for the tab/group.
 * @package Beans_Extension
 * @license GPL3.0+
 * @since 1.0.1
*/

/**
 * Inspired by XO Featured Image Tools WordPress Plugin
 * @link https://xakuro.com/wordpress/
 * @author Xakuro
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
if(class_exists('_beans_admin_image_app') === FALSE) :
class _beans_admin_image_app
{
/**
 * @since 1.0.1
 * 	Authosave thumbnail for image tab/group.
 * 
 * [TOC]
 * 	__construct()
 * 	set_post_type()
 * 	set_category()
 * 	set_hook()
 * 	invoke_hook()
 * 	from_content()
 * 	from_category()
 * 	get_attachment_id_by_guid()
 * 	get_attachment_id_by_url()
*/

	/**
		@access (private)
			Class properties.
		@var (string) $_class
			Name/Identifier with prefix.
		@var (array) $post_type
			Post type.
		@var (array) $category
			Category
		@var (array) $hook
			The collection of hooks that is being registered (that is, actions or filters).
	*/
	private static $_class = '';
	private $post_type = array();
	private $category = array();
	private $hook = array();


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
				Send to Constructor.
			@return (void)
		*/

		// Init properties.
		self::$_class = __utility_get_class(get_class($this));
		$this->post_type = $this->set_post_type();
		$this->category = $this->set_category();

		// Register hooks.
		$this->hook = $this->set_hook();
		$this->invoke_hook($this->hook);

	}// Method


	/* Setter
	_________________________
	*/
	private function set_post_type()
	{
		/**
			@access (private)
			@return (array)
		*/

		/**
		 * @reference (WP)
		 * 	Get a list of all registered post type objects.
		 * 	https://developer.wordpress.org/reference/functions/get_post_types/
		*/
		$post_types = get_post_types(array('public' => TRUE),'objects');

		$return = array();

		if(!empty($post_types)){
			foreach($post_types as $post_type){
				/**
				 * @reference (WP)
				 * 	Check a post type’s support for a given feature.
				 * 	https://developer.wordpress.org/reference/functions/post_type_supports/
				*/
				if(post_type_supports($post_type->name,'thumbnail')){
					$return[] = $post_type->name;
				}
			}
		}
		return $return;

	}// Method


	/* Setter
	_________________________
	*/
	private function set_category()
	{
		/**
			@access (private)
			@return (array)
				List of category objects.
		*/

		/**
		 * @reference (WP)
		 * 	Retrieves a list of category objects.
		 * 	https://developer.wordpress.org/reference/functions/get_categories/
		*/
		$terms = get_categories(array(
			'exclude' => 1,
			'orderby' => 'id',
			'hide_empty'	 => 0,
		));

		$return = array();

		if(!empty($terms)){
			foreach($terms as $term){
				$return[] = $term->category_nicename;
			}
		}
		return $return;

	}// Method


	/* Setter
	_________________________
	*/
	private function set_hook()
	{
		/**
			@access (private)
				Fires once a post has been saved.
				https://developer.wordpress.org/reference/hooks/save_post/
			@return (array)
				The collection of hooks that is being registered (that is, actions or filters).
			@reference
				[Plugin]/trait/hook.php
		*/
		return $this->set_parameter_callback(array(
			'from_content'	 => array(
				'tag' => 'add_action',
				'hook' => 'save_post',
				'args' => 2
			),
			'from_category'	 => array(
				'tag' => 'add_action',
				'hook' => 'save_post',
				'args' => 2
			),
		));

	}// Method


	/* Hook
	_________________________
	*/
	public function from_category($post_id,$post)
	{
		/**
			@access (public)
				https://nandani.sakura.ne.jp/web_all/php/3135/
			@param (int) $post_id
				Post ID.
			@param (WP_Post) $post
				Post object.
			@return (void)
				[Plugin]/include/constant.php
		*/
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){return;}

		/**
		 * @reference (WP)
		 * 	Determines if the specified post is a revision.
		 * 	https://developer.wordpress.org/reference/functions/wp_is_post_revision/
		*/
		if(wp_is_post_revision($post_id)){return;}

		/**
		 * @since 1.0.1
		 * 	Check the eyecatch.
		 * @reference (WP)
		 * 	Retrieves a post meta field for the given post ID.
		 * 	https://developer.wordpress.org/reference/functions/get_post_meta/
		*/
		$attachment_id = get_post_meta($post_id,'_thumbnail_id',TRUE);
		if($attachment_id){return;}

		// Check "Image" tab settings.
		$option = get_option(BEANS_EXTENSION_PREFIX['option'] . 'image');
		if(empty($option)){return;}

		/**
		 * @reference (WP)
		 * 	Retrieves post categories.
		 * 	https://developer.wordpress.org/reference/functions/get_the_category/
		*/
		$categories = get_the_category($post_id);
		if(!empty($categories)){
			foreach($categories as $category){
				foreach($this->category as $key => $value){
					$needle = BEANS_EXTENSION_PREFIX['setting'] . 'image_' . $key;
					if(isset($option[$needle])){
						$media_id = $this->get_attachment_id_by_guid($option[$needle]);
						/**
						 * @reference (WP)
						 * 	Updates a post meta field based on the given post ID.
						 * 	https://developer.wordpress.org/reference/functions/update_post_meta/
						*/
						update_post_meta($post_id,$meta_key = '_thumbnail_id',$meta_value = $media_id);
					}
					else{
						$needle = BEANS_EXTENSION_PREFIX['setting'] . 'image_nopost';
						$media_id = $this->get_attachment_id_by_guid($option[$needle]);
						update_post_meta($post_id,$meta_key = '_thumbnail_id',$meta_value = $media_id);
					}
				}
			}
		}

	}// Method


	/* Hook
	_________________________
	 */
	public function from_content($post_id,$post)
	{
		/**
			@access (public)
				When saving a post, save the first image in the content as an eye catch image.
				https://web.contempo.jp/weblog/tips/p1562
				https://nandani.sakura.ne.jp/web_all/php/3135/
				https://xakuro.com/wordpress/
			@param (int) $post_id
				Post ID.
			@param (WP_Post) $post
				Post object.
			@return (void)
		*/
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){return;}

		/**
		 * @reference (WP)
		 * 	Determines if the specified post is a revision.
		 * 	https://developer.wordpress.org/reference/functions/wp_is_post_revision/
		*/
		if(wp_is_post_revision($post_id)){return;}

		/**
		 * @since 1.0.1
		 * 	Check post type.
		 * @reference (WP)
		 * 	Retrieves the post type of the current post or of a given post.
		 * 	https://developer.wordpress.org/reference/functions/get_post_type/
		*/
		$post_type = get_post_type($post_id);
		if(get_post_type_object($post_type)->public !== TRUE){return;}

		/**
		 * @since 1.0.1
		 * 	Check the eyecatch.
		 * @reference (WP)
		 * 	Retrieves a post meta field for the given post ID.
		 * 	https://developer.wordpress.org/reference/functions/get_post_meta/
		*/
		$attachment_id = get_post_meta($post_id,'_thumbnail_id',TRUE);
		$content = $post->post_content;

		if(!$attachment_id){
			$attachment_id = 0;

			$matches = array();
			preg_match_all('/<\s*img .*?src\s*=\s*[\"|\'](.*?)[\"|\'].*?>/i',$content,$matches);

			foreach($matches[0] as $key => $img){
				$url = $matches[1][$key];

				// Get the ID from the Wp-image-{$id} class.
				$class_matches = array();
				if(preg_match('/class\s*=\s*[\"|\'].*?wp-image-([0-9]*).*?[\"|\']/i',$img,$class_matches)){
					$attachment_id = $class_matches[1];
				}

				// Get ID from URL.
				if(!$attachment_id){
					$attachment_id = $this->get_attachment_id_by_url($url);
					if(!$attachment_id){
						$attachment_id = $this->get_attachment_id_by_url($url,FALSE);
					}
				}

				// Get ID from GUID URL.
				if(!$attachment_id){
					$attachment_id = $this->get_attachment_id_by_guid($url);
				}

				if($attachment_id){
					update_post_meta($post_id,'_thumbnail_id',$attachment_id);
					break;
				}
			}

			// Gallery
			if(!$attachment_id){
				$matches = array();
				if(preg_match('/\[gallery\s*ids\s*=\s*[\"|\']([0-9]+).*?[\"|\'].*?\]/i',$content,$matches)){
					$attachment_id = $matches[1];
					update_post_meta($post_id,'_thumbnail_id',$attachment_id);
				}
			}
		}

	}// Method


	/**
		@access (private)
			Get attachment file from GUID.
		@global (wpdb) $wpdb
			WordPress database abstraction object.
			https://developer.wordpress.org/reference/classes/wpdb/
		@param (string) $guid
			GUID.
		@return (int)
			Attachment post ID, 0 on failure.
	*/
	private function get_attachment_id_by_guid($guid)
	{
		// WP global.
		global $wpdb;

		$attachment_id = $wpdb->get_var($wpdb->prepare(
			"SELECT ID FROM $wpdb->posts WHERE post_type='attachment' AND guid=%s LIMIT 1;",
			$guid
		));
		return (int)$attachment_id;

	}// Method


	/**
		@access (private)
			Acquire the ID from the URL of the attachment file.
			https://xakuro.com/wordpress/
		@global (wpdb) $wpdb
			https://developer.wordpress.org/reference/classes/wpdb/
		@param (string) $url
			Attachment file URL.
		@param (bool) $is_full_size option.
			A value indicating whether it is full size.
			True for full size, false for different size.
			The default is true.
		@return (int)
			It returns ID (1 or more) if it succeeds, 0 if it does not exist.
	*/
	private function get_attachment_id_by_url($url,$is_full_size = TRUE)
	{
		// WP global.
		global $wpdb;

		$attachment_id = 0;

		// If it is a relative URL, convert it to an absolute URL.
		$parse_url = parse_url( $url );
		if(!isset($parse_url['host'])){
			if(isset($_SERVER['SERVER_NAME'])){
				/**
				 * @reference (WP)
				 * 	Sets the scheme for a URL.
				 * 	https://developer.wordpress.org/reference/functions/set_url_scheme/
				*/
				$host = set_url_scheme('//' . strtolower($_SERVER['SERVER_NAME']));
				$url = rtrim($host,'/') . '/' . ltrim($parse_url['path'],'/');
			}
		}
		$full_size_url = $url;

		if(!$is_full_size){
			// Remove the size notation (-999x999) from the URL to get the full-size URL.
			$full_size_url = preg_replace('/(-[0-9]+x[0-9]+)(\.[^.]+){0,1}$/i','${2}',$url);
			if($url === $full_size_url){
				// Abort because it is not a different size.
				return $attachment_id;
			}
		}

		/**
		 * @reference (WP)
		 * 	Returns an array containing the current upload directory’s path and URL.
		 * 	https://developer.wordpress.org/reference/functions/wp_upload_dir/
		*/
		$uploads = wp_upload_dir();
		$base_url = $uploads['baseurl'];
		if(strpos($full_size_url,$base_url) === 0){
			$attached_file = str_replace($base_url . '/','',$full_size_url);
			$attachment_id = $wpdb->get_var( $wpdb->prepare(
				"SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_wp_attached_file' AND meta_value=%s LIMIT 1;",
				$attached_file
			));
		}
		return (int)$attachment_id;

	}// Method


}// Class
endif;
// new _beans_admin_image_app();
// _beans_admin_image_app::__get_instance();
