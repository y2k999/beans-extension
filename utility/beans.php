<?php
/**
 * A set of tools to ease building applications.
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
if(class_exists('_beans_utility') === FALSE) :
class _beans_utility
{
/**
 * @since 1.0.1
 * 	The Beans Utilities is a set of tools to ease building applications.
 * 	Since these functions are used throughout the Beans framework and are therefore required,they are loaded automatically when the Beans framework is included.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	beans_render_function()
 * 	beans_render_function_array()
 * 	beans_remove_dir()
 * 	beans_scandir()
 * 	beans_str_ends_with()
 * 	beans_str_starts_with()
 * 	beans_path_to_url()
 * 	beans_url_to_path()
 * 	beans_sanitize_path()
 * 	beans_get()
 * 	beans_post()
 * 	beans_get_or_post()
 * 	beans_in_multi_array()
 * 	beans_multi_array_key_exists()
 * 	beans_array_shortcodes()
 * 	beans_admin_menu_position()
 * 	beans_join_arrays()
 * 	beans_array_unique()
 * 	beans_join_arrays_clean()
 * 	_beans_is_uri()
 * 	_beans_doing_ajax()
 * 	_beans_doing_autosave()
*/

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
				Send to Constructor.
			@return (void)
			@reference
				This is only called once, since the only way to instantiate this is with the get_instance() method in trait (singleton.php).
				[Plugin]/trait/singleton.php
		*/

	}// Method


	/* Method
	_________________________
	*/
	public static function __render_function($callback)
	{
		/**
			[ORIGINAL]
				beans_render_function()
				https://www.getbeans.io/code-reference/functions/beans_render_function/
			@access (public)
				Calls function given by the first parameter and passes the remaining parameters as arguments.
				The main purpose of this function is to store the content echoed by a function in a variable.
			@param (callable) $callback
				The callback to be called.
			@param (mixed) $args,...
				[Optional]
				Additional parameters to be passed to the callback.
			@return (string)
				The callback content.
		*/
		if(!is_callable($callback)){return;}

		// Returns an array comprising a function's argument list.
		$args = func_get_args();

		// Turn on output buffering.
		ob_start();

		// Call a callback with an array of parameters.
		call_user_func_array($callback,array_slice($args,1));

		// Get current buffer contents and delete current output buffer.
		return ob_get_clean();

	}// Method


	/* Method
	_________________________
	*/
	public static function __render_function_array($callback,$params = array())
	{
		/**
			[ORIGINAL]
				beans_render_function_array()
				https://www.getbeans.io/code-reference/functions/beans_render_function_array/
			@access (public)
				Calls function given by the first parameter and passes the remaining parameters as arguments.
				The main purpose of this function is to store the content echoed by a function in a variable.
			@param (callable) $callback
				The callback to be called.
			@param (array) $params
				[Optional]
				The parameters to be passed to the callback,as an indexed array.
			@return (string)
				The callback content.
		*/
		if(!is_callable($callback)){return;}

		ob_start();
		call_user_func_array($callback,$params);
		return ob_get_clean();

	}// Method


	/* Method
	_________________________
	*/
	public static function __remove_directory($dir_path)
	{
		/**
			[ORIGINAL]
				beans_remove_dir()
				https://www.getbeans.io/code-reference/functions/beans_remove_dir/
			@access (public)
				Remove a directory and its files.
			@param (string) $dir_path
				Path to directory to remove.
			@return (bool)
				Returns TRUE if the directory was removed; else,return FALSE.
		*/
		if(!is_dir($dir_path)){
			return FALSE;
		}

		foreach(self::__scan_directory($dir_path) as $needle => $item){
			$path = $dir_path . '/' . $item;
			if(is_dir($path)){
				self::__remove_directory($path);
			}
			else{
				/* phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case. */
				@unlink($path);
			}
		}

		/* phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case. */
		return @rmdir($dir_path);

	}// Method


	/* Method
	_________________________
	*/
	public static function __scan_directory($dir_path)
	{
		/**
			[ORIGINAL]
				beans_scandir()
			@since 1.5.0
			@access (public)
				List files and directories inside of the specified path.
			@param (string) $dir_path
				Path to the directory to scan.
			@return (array)|(bool)
				Returns FALSE upon error.
		*/

		/* phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case. */
		$items = @scandir($dir_path);
		if(!$items){
			return FALSE;
		}

		// Get rid of dot files when present.
		if('.' === $items[0]){
			unset($items[0],$items[1]);
		}
		return $items;

	}// Method


	/* Method
	_________________________
	*/
	public static function __str_end_with($haystack,$needles)
	{
		/**
			[ORIGINAL]
				beans_str_ends_with()
			@since 1.5.0
			@access (public)
				Check if the given string ends with the given substring(s).
				When passing an array of needles,the first needle match returns `TRUE`.
				Therefore,only one word in the array needs to match.
			@param (string) $haystack
				The given string to check.
			@param (string)|(array)$needles
				The substring(s) to check for at the end of the given string.
			@return (bool)
		*/
		$haystack = (string)$haystack;

		foreach((array)$needles as $needle){
			$substring = mb_substr($haystack,-mb_strlen($needle));
			if($substring === (string)$needle){
				return TRUE;
			}
		}
		return FALSE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __str_start_with($haystack,$needles)
	{
		/**
			[ORIGINAL]
				beans_str_starts_with()
			@since 1.5.0
			@access (public)
				Check if the given string starts with the given substring(s).
				When passing an array of needles,the first needle match returns `TRUE`.
				Therefore,only one word in the array needs to match.
			@param (string) $haystack
				The given string to check.
			@param (string)|(array)$needles
				The substring(s) to check for at the beginning of the given string.
			@return (bool)
		*/
		$haystack = (string)$haystack;

		foreach((array)$needles as $needle){
			$substring = mb_substr($haystack,0,mb_strlen($needle));
			if($substring === (string)$needle){
				return TRUE;
			}
		}
		return FALSE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __path_to_url($path,$force_rebuild = FALSE)
	{
		/**
			[ORIGINAL]
				beans_path_to_url()
				https://www.getbeans.io/code-reference/functions/beans_path_to_url/
			@since 1.5.0
			@access (public)
				Convert internal path to a url.
				This function must only be used with internal paths.
			@param (string) $path
				Path to be converted. Accepts absolute and relative internal paths.
			@param (bool) $force_rebuild...,
				[Optional]
				Forces the rebuild of the root url and path.
			@return (string)
				Url.
		 */
		static $root_path;
		static $root_url;

		// Stop here if it is already a url or data format.
		if(self::__is_uri($path)){
			return $path;
		}

		/**
		 * @since 1.0.1
		 * 	Standardize backslashes
		 * @reference (WP)
		 * 	Normalize a filesystem path.
		 * 	https://developer.wordpress.org/reference/functions/wp_normalize_path/
		*/
		$path = wp_normalize_path($path);

		// Set root and host if it isn't cached.
		if(!$root_path || TRUE === $force_rebuild){
			// Standardize backslashes set host.
			$root_path = wp_normalize_path(untrailingslashit(ABSPATH));

			// Removes trailing forward slashes and backslashes if they exist.
			$root_url	 = untrailingslashit(site_url());

			// 	Remove subfolder if necessary.
			$subfolder = parse_url($root_url,PHP_URL_PATH);

			if($subfolder && '/' !== $subfolder){
				$pattern = '#' . untrailingslashit(preg_quote($subfolder)) . '$#';
				$root_path = preg_replace($pattern,'',$root_path);
				$root_url = preg_replace($pattern,'',$root_url);
			}

			/**
			 * @since 1.0.1
			 * 	If it's a multisite and not the main site, then add the site's path.
			 * @reference (WP)
			 * 	Determine whether a site is the main site of the current network.
			 * 	https://developer.wordpress.org/reference/functions/is_main_site/
			*/
			if(!is_main_site()){
				/**
				 * @reference (WP)
				 * 	Retrieve the details for a blog from the blogs table and blog options.
				 * 	https://developer.wordpress.org/reference/functions/get_blog_details/
				 * 	Retrieve the current site ID.
				 * 	https://developer.wordpress.org/reference/functions/get_current_blog_id/
				*/
				$blogdetails = get_blog_details(get_current_blog_id());
				if($blogdetails && (!defined('WP_SITEURL') || (defined('WP_SITEURL') && WP_SITEURL === site_url()))){
					$root_url = untrailingslashit($root_url) . $blogdetails->path;
				}
			}

			// Maybe re-add tilde from host.
			$maybe_tilde = self::__get_global_value(0,explode('/',trailingslashit(ltrim($subfolder,'/'))));
			if(FALSE !== stripos($maybe_tilde,'~')){
				$root_url = trailingslashit($root_url) . $maybe_tilde;
			}
		}

		// Remove root if necessary.
		if(FALSE !== stripos($path,$root_path)){
			$path = str_replace($root_path,'',$path);
		}
		elseif(FALSE !== stripos($path,self::__get_global_value('DOCUMENT_ROOT',$_SERVER))){
			$path = str_replace(self::__get_global_value('DOCUMENT_ROOT',$_SERVER),'',$path);
		}

		// Appends a trailing slash.
		return trailingslashit($root_url) . ltrim($path,'/');

	}// Method


	/* Method
	_________________________
	*/
	public static function __url_to_path($url,$force_rebuild = FALSE)
	{
		/**
			[ORIGINAL]
				beans_url_to_path()
				https://www.getbeans.io/code-reference/functions/beans_url_to_path/
			@since 1.5.0
			@access (public)
				Convert internal url to a path.
				This function must only be used with internal urls.
			@param (string) $url
				Url to be converted.
				Accepts only internal urls.
			@param (bool) $force_rebuild
				[Optional]
				Forces the rebuild of the root url and path.
			@return (string)
				Absolute path.
		*/
		static $root_path;
		static $blogdetails;

		/**
		 * @reference (WP)
		 * 	Retrieves the URL for the current site where WordPress application files.
		 * 	https://developer.wordpress.org/reference/functions/site_url/
		*/
		$site_url = site_url();

		if(TRUE === $force_rebuild){
			$root_path = '';
			$blogdetails = '';
		}

		/**
		 * @reference (PHP)
		 * 	Fix protocol. It isn't needed to set SSL as it is only used to parse the URL.(scheme : ex) http:// ?)
		 * 	http://phpspot.net/php/man/php/function.parse-url.html
		 */
		if(!parse_url($url,PHP_URL_SCHEME)){
			$original_url = $url;
			$url = 'http://' . ltrim($url,'/');
		}

		// It's not an internal url,bail out.
		if(FALSE === stripos(parse_url($url,PHP_URL_HOST),parse_url($site_url,PHP_URL_HOST))){
			return isset($original_url) ? $original_url : $url;
		}

		// Parse url and standardize backslashes.
		$url = parse_url($url,PHP_URL_PATH);

		/**
		 * @reference (WP)
		 * 	Normalize a filesystem path.
		 * 	https://developer.wordpress.org/reference/functions/wp_normalize_path/
		*/
		$path = wp_normalize_path($url);

		// Maybe remove tilde from path.
		$trimmed_path = trailingslashit(ltrim($path,'/'));
		$maybe_tilde = self::__get_global_value(0,explode('/',$trimmed_path));

		if(FALSE !== stripos($maybe_tilde,'~')){
			$end_with_slash = substr($path,-1) === '/';
			$path = preg_replace('#\~[^/]*\/#','',$trimmed_path);

			if($path && !$end_with_slash){
				$path = rtrim($path,'/');
			}
		}

		// Set root if it isn't cached yet.
		if(!$root_path){
			// Standardize backslashes and remove windows drive for local installs.
			$root_path = wp_normalize_path(untrailingslashit(ABSPATH));
			$set_root = TRUE;
		}

		// If the subfolder exists for the root URL, then strip it off of the root path.
		// Why? We don't want a double subfolder in the final path.
		$subfolder = parse_url($site_url,PHP_URL_PATH);

		if(isset($set_root) && $subfolder && '/' !== $subfolder){
			$root_path = preg_replace('#' . untrailingslashit(preg_quote($subfolder)) . '$#','',$root_path);
			// Add an extra step which is only used for extremely rare case.
			if(defined('WP_SITEURL')){
				$subfolder = parse_url(WP_SITEURL,PHP_URL_PATH);
				if('' !== $subfolder){
					$root_path = preg_replace('#' . untrailingslashit(preg_quote($subfolder)) . '$#','',$root_path);
				}
			}
		}

		/**
		 * @since 1.0.1
		 * 	Remove the blog path for multisites.
		 * @reference (WP)
		 * 	Determine whether a site is the main site of the current network.
		 * 	https://developer.wordpress.org/reference/functions/is_main_site/
		*/
		if(!is_main_site()){
			/**
			 * @since 1.0.1
			 * 	Set blogdetails if it isn't cached.
			 * @reference (WP)
			 * 	Retrieve the details for a blog from the blogs table and blog options.
			 * 	https://developer.wordpress.org/reference/functions/get_blog_details/
			 * 	Retrieve the current site ID.
			 * 	https://developer.wordpress.org/reference/functions/get_current_blog_id/
			*/
			if(!$blogdetail){
				$blogdetail = get_blog_details(get_current_blog_id());
			}
			$path = preg_replace('#^(\/?)' . trailingslashit(preg_quote(ltrim($blogdetail->path,'/'))) . '#','',$path);
		}

		// Remove Windows drive for local installs if the root isn't cached yet.
		if(isset($set_root)){
			$root_path = self::__sanitize_path($root_path);
		}

		// Add root of it doesn't exist.
		if(FALSE === strpos($path,$root_path)){
			$path = trailingslashit($root_path) . ltrim($path,'/');
		}
		return self::__sanitize_path($path);

	}// Method


	/* Method
	_________________________
	*/
	public static function __sanitize_path($path)
	{
		/**
			[ORIGINAL]
				beans_sanitize_path()
				https://www.getbeans.io/code-reference/functions/beans_sanitize_path/
			@since 1.2.1
			@access (public)
				Sanitize path.
			@param (string) $path
				Path to be sanitize.
				Accepts absolute and relative internal paths.
			@return (string)
				Sanitize path.
		*/

		// Try to convert it to real path
		if(FALSE !== realpath($path)){
			$path = realpath($path);
		}

		// Remove Windows drive for local installs if the root isn't cached yet.
		$path = preg_replace('#^[A-Z]\:#i','',$path);

		/**
		 * @reference (WP)
		 * 	Normalize a filesystem path.
		 * 	https://developer.wordpress.org/reference/functions/wp_normalize_path/
		*/
		return wp_normalize_path($path);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_global_value($needle,$haystack = FALSE,$default = NULL)
	{
		/**
			[ORIGINAL]
				beans_get()
				https://www.getbeans.io/code-reference/functions/beans_get/
			@access (public)
				Get value from $_GET or defined $haystack.
			@param (string) $needle
				Name of the searched key.
			@param (mixed) $haystack...,
				[Optional]
				The target to search.
				If FALSE,$_GET is set to be the $haystack.
			@param (mixed) $default...,
				[Optional]
				Value to return if the needle isn't found.
			@return (string)
				Returns the value if found;else $default is returned.
		*/
		if(FALSE === $haystack){
			/* phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification -- The nonce verification check should be at the form processing level. */
			$haystack = $_GET;
		}
		$haystack = (array)$haystack;

		if(isset($haystack[$needle])){
			return $haystack[$needle];
		}
		return $default;

	}// Method


	/* Method
	_________________________
	*/
	public static function __post_global_value($needle,$default = NULL)
	{
		/**
			[ORIGINAL]
				beans_post()
				https://www.getbeans.io/code-reference/functions/beans_post/
			@access (public)
				Get value from $_POST.
			@param (string) $needle
				Name of the searched key.
			@param (mixed) $default...,
				[Optional]
				Value to return if the needle isn't found.
			@return (string)
				Returns the value if found;else $default is returned.
		*/

		/* phpcs:ignore WordPress.CSRF.NonceVerification.NoNonceVerification -- The nonce verification check should be at the form processing level. */
		return self::__get_global_value($needle,$_POST,$default);

	}// Method


	/* Method
	_________________________
	*/
	public static function __get_or_post_global_value($needle,$default = NULL)
	{
		/**
			[ORIGINAL]
				beans_get_or_post()
				https://www.getbeans.io/code-reference/functions/beans_get_or_post/
			@access (public)
				Get value from $_GET or $_POST superglobals.
			@param (string) $needle
				Name of the searched key.
			@param (mixed) $default...,
				[Optional]
				Value to return if the needle isn't found.
			@return (string)
				Returns the value if found;else $default is returned.
		*/
		$get = self::__get_global_value($needle);
		if($get){
			return $get;
		}

		$post = self::__post_global_value($needle);
		if($post){
			return $post;
		}
		return $default;

	}// Method


	/* Method
	_________________________
	*/
	public static function __in_multi_array($needle,$haystack,$strict = FALSE)
	{
		/**
			[ORIGINAL]
				beans_in_multi_array()
				https://www.getbeans.io/code-reference/functions/beans_in_multi_array/
			@access (public)
				Checks if a value exists in a multi-dimensional array.
			@param (string) $needle
				The searched value.
			@param (array) $haystack
				The multi-dimensional array.
			@param (bool) $strict
				If the third parameter strict is set to TRUE,the beans_in_multi_array() function will also check the types of the needle in the haystack.
			@return (bool)
				Returns TRUE if needle is found in the array;else,FALSE is returned.
		*/
		if(in_array($needle,$haystack,$strict)){
			/* phpcs:ignore WordPress.PHP.StrictInArray.MissingTRUEStrict -- The rule does not account for when we are passing a boolean within a variable as the 3rd argument. */
			return TRUE;
		}

		foreach((array)$haystack as $value){
			if(is_array($value) && self::__in_multi_array($needle,$value)){
				return TRUE;
			}
		}
		return FALSE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __multi_array_key_exist($needle,array $haystack){
		/**
			[ORIGINAL]
				beans_multi_array_key_exists()
				https://www.getbeans.io/code-reference/functions/beans_multi_array_key_exists/
			@since 1.5.0
			@access (public)
				Checks if a key or index exists in a multi-dimensional array.
			@param (string) $needle
				The key to search for within the haystack.
			@param (array) $haystack
				The array to be searched.
			@return (bool)
				Returns TRUE if needle is found in the array;else,FALSE is returned.
		*/
		if(array_key_exists($needle,$haystack)){
			return TRUE;
		}

		foreach($haystack as $value){
			if(is_array($value) && self::__multi_array_key_exist($needle,$value)){
				return TRUE;
			}
		}
		return FALSE;

	}// Method


	/* Method
	_________________________
	*/
	public static function __array_shortcode($content,$haystack){
		/**
			[ORIGINAL]
				beans_array_shortcodes()
				https://www.getbeans.io/code-reference/functions/beans_array_shortcodes/
			@access (public)
				Search content for shortcodes and filter shortcodes through their hooks.
				Shortcodes must be delimited with curly brackets (e.g.{key}) and correspond to the searched array key.
			@param (string)$content
				Content containing the shortcode(s) delimited with curly brackets (e.g.{key}).
				Shortcode(s) correspond to the searched array key and will be replaced by the array value if found.
			@param (array)$haystack
				The associative array used to replace shortcode(s).
			@return (string)
				Content with shortcodes filtered out.
		*/
		if(preg_match_all('#{(.*?)}#',$content,$matches)){
			foreach($matches[1] as $needle){
				$sub_keys = explode('.',$needle);
				$value = FALSE;
				foreach($sub_keys as $sub_key){
					$search = $value ? $value : $haystack;
					$value = self::__get_global_value($sub_key,$search);
				}
				if($value){
					$content = str_replace('{' . $needle . '}',$value,$content);
				}
			}
		}
		return $content;

	}// Method


	/* Method
	_________________________
	*/
	public static function __admin_menu_position($position)
	{
		/**
			[ORIGINAL]
				beans_admin_menu_position()
				https://www.getbeans.io/code-reference/functions/beans_admin_menu_position/
			@access (public)
				Make sure the menu position is valid.
				If the menu position is unavailable,it will loop through the positions until one is found that is available.
			@global $menu
				https://codex.wordpress.org/Global_Variables
			@param (int) $position
				The desired position.
			@return (bool)
				Valid position.
		*/
		if(!is_array($position)){
			return $position;
		}

		global $menu;
		if(array_key_exists($position,$menu)){
			return self::__admin_menu_position($position + 1);
		}
		return $position;

	}// Method


	/* Method
	_________________________
	*/
	public static function __join_array(array &$array1,array $array2)
	{
		/**
			[ORIGINAL]
				beans_join_arrays()
			@since 1.5.0
			@access (public)
				Maybe joins two arrays together without requiring an additional variable assignment upon return.
			@param (array) $array1
				Initial array (passed by reference),which becomes the final array.
			@param (array) $array2
				The array to merge into $array1.
			@return (void)
				Nothing is returned.
		*/
		// Bail out if the 2nd array is empty,as there's no work to do.
		if(empty($array2)){return;}

		// If the 1st array is empty,set it to the 2nd array.
		if(empty($array1)){
			// Then bail out as we're done.
			$array1 = $array2;
			return;
		}

		// Both arrays have elements.
		// Let's join them together.
		$array1 = array_merge($array1,$array2);

	}// Method


	/* Method
	_________________________
	*/
	public static function __array_unique(array $array)
	{
		/**
			[ORIGINAL]
				beans_array_unique()
			@since 1.5.0
			@access (public)
				Remove duplicate values from the given array and re-indexes to start at element 0.
				Keys are not preserved.
			@param (array) $array
				The given array to filter.
			@return (array)
		*/
		return array_values(array_unique($array));

	}// Method


	/* Method
	_________________________
	*/
	public static function __join_array_clean(array $array1,array $array2,$reindex = TRUE)
	{
		/**
			[ORIGINAL]
				beans_join_arrays_clean()
			@since 1.5.0
			@access (public)
				Join the given arrays and clean the merged array by removing duplicates and empties.
				By default,the clean joined array is re-indexed;however,setting the third parameter to `FALSE` will preserve the keys.
			@param (array) $array1
				Initial array to join.
			@param (array) $array2
				The array to merge into $array1.
			@param (bool) $reindex
				When TRUE,re-indexes the clean array; else,preserves the keys.
			@return (array)
		*/
		self::__join_array($array1,$array2);

		if(empty($array1)){
			return $array1;
		}
		$array1= array_filter(array_unique($array1));

		return $reindex ? array_values($array1) : $array1;

	}// Method


	/* Method
	_________________________
	*/
	public static function __is_uri($maybe_uri)
	{
		/**
			[ORIGINAL]
				_beans_is_uri()
			@since 1.5.0
			@access (public)
				Checks if the given input is a URL or data URI.
				It checks that the given input begins with:
				 - http
				 - https
				 - //
				 - data
			@param (string) $maybe_uri
				The given input to check.
			@return (bool)
		*/
		return (1 === preg_match('#^(http|https|\/\/|data)#',$maybe_uri));

	}// Method


	/* Method
	_________________________
	*/
	public static function __doing_ajax()
	{
		/**
			[ORIGINAL]
				_beans_doing_ajax()
			@since 1.5.0
			@access (public)
				Checks if WP is doing ajax.
			@return (bool)
			@reference (WP)
				Determines whether the current request is a WordPress Ajax request.
				https://developer.wordpress.org/reference/functions/wp_doing_ajax/
		*/
		return defined('DOING_AJAX') && DOING_AJAX;

	}// Method


	/* Method
	_________________________
	*/
	public static function __doing_autosave()
	{
		/**
			[ORIGINAL]
				_beans_doing_autosave()
			@since 1.5.0
			@access (public)
				Checks if WP is doing an autosave.
			@return (bool)
			@reference
				Save a post submitted with XHR.
				https://developer.wordpress.org/reference/functions/wp_autosave/
		*/
		return defined('DOING_AUTOSAVE') && DOING_AUTOSAVE;

	}// Method


}// Class
endif;
// new _beans_utility();
_beans_utility::__get_instance();
