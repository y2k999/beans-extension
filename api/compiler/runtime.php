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
if(class_exists('_beans_runtime_compiler') === FALSE) :
final class _beans_runtime_compiler
{
/**
 * @since 1.0.1
 * 	Compiles and minifies CSS,LESS and JS.
 * 	This class compiles and minifies CSS,LESS and JS.
 * 
 * [TOC]
 * 	__construct()
 * 	run_compiler()
 * 	modify_filesystem_method()
 * 	filesystem()
 * 	is_wp_filesystem_direct()
 * 	maybe_make_dir()
 * 	set_fragments()
 * 	set_filename()
 * 	hash()
 * 	cache_file_exist()
 * 	get_filename()
 * 	cache_file()
 * 	enqueue_file()
 * 	get_url()
 * 	get_extension()
 * 	combine_fragments()
 * 	get_content()
 * 	get_internal_content()
 * 	get_remote_content()
 * 	get_function_content()
 * 	add_content_media_query()
 * 	format_content()
 * 	replace_css_url()
 * 	replace_css_url_callback()
 * 	init_config()
 * 	get_fragments_filemtime()
 * 	get_new_hash()
 * 	remove_modified_files()
 * 	strip_whitespace()
 * 	is_function()
 * 	kill()
 * 	report()
 * 	set_filname()
 * 	__get()
*/

	/**
		@access (private)
			Class properties.
		@var (array) $config
			Compiler's runtime configuration parameters.
		@var (string) $dir
			Cache dir.
		@var (string) $url
			Cache url.
		@var (string) $current_fragment
			The fragment currently being processed.
		@var (string) $compiled_content
			The compiled content.
		@var (string) $filename
			Compiled content's filename.
	*/
	private $config;
	private $dir;
	private $url;
	private $current_fragment;
	private $compiled_content;
	private $filename;


	/* Constructor
	_________________________
	*/
	public function __construct(array $config)
	{
		/**
			@since1.5.0
				Moved config initializer & compile tasks out of constructor.
			@access (public)
				Create a new Compiler.
			@param (array) $config
				Runtime configuration parameters for the Compiler.
			@return (void)
			@reference
				[Plugin]/api/compiler/beans.php
		*/

		// Init properties.
		$this->config = $this->init_config($config);
		$this->dir = _beans_compiler::__get_directory(is_admin()) . $this->config['id'];
		$this->url = _beans_compiler::__get_url(is_admin()) . $this->config['id'];

	}// Method


	/* Method
	_________________________
	*/
	public function run()
	{
		/**
			[ORIGINAL]
				run_compiler()
			@since 1.5.1
				Recompile when in development mode.
			@access (public)
				Run the compiler.
			@return (void)
			@reference
				[Plugin]/api/compiler/beans.php
		*/

		/**
		 * @since 1.0.1
		 * 	Modify the WP Filesystem method.
		 * @reference (WP)
		 * 	Filters the filesystem method to use.
		 * 	https://developer.wordpress.org/reference/hooks/filesystem_method/
		*/
		add_filter('filesystem_method',[$this,'modify_filesystem']);

		$this->set_fragment();
		$this->set_filename();

		if(_beans_compiler::__is_dev_mode() || !$this->check_cache()){
			$this->init_filesystem();
			$this->maybe_make_dir();
			$this->combine_fragment();
			$this->create_cache();
		}
		$this->enqueue_cache();

		/**
		 * @reference (WP)
		 * 	Keep it safe and reset the WP Filesystem method.
		 * 	https://developer.wordpress.org/reference/hooks/filesystem_method/
		*/
		remove_filter('filesystem_method',[$this,'modify_filesystem']);

	}// Method


	/* Hook
	_________________________
	*/
	public function modify_filesystem()
	{
		/**
			[ORIGINAL]
				modify_filesystem_method()
			@access (public)
				Callback to set the WP Filesystem method.
			@return (string)
		*/
		return 'direct';

	}// Method


	/**
		[ORIGINAL]
			filesystem()
		@access (private)
			Initialise the WP Filesystem.
			https://codex.wordpress.org/Filesystem_API
		@return (bool)|(void)
	*/
	private function init_filesystem()
	{
		// If the WP_Filesystem is not already loaded,load it.
		if(!function_exists('WP_Filesystem')){
			require_once ABSPATH . '/wp-admin/includes/file.php';
		}

		// If the WP_Filesystem is not initialized or is not set to WP_Filesystem_Direct,then initialize it.
		if($this->check_filesystem()){
			return TRUE;
		}

		// Initialize the filesystem.
		$response = WP_Filesystem();

		// If the filesystem did not initialize,then generate a report and exit.
		if(TRUE !== $response || !$this->check_filesystem()){
			return $this->kill();
		}
		return TRUE;

	}// Method


	/**
		[ORIGINAL]
			is_wp_filesystem_direct()
		@since 1.5.0
		@access (private)
			Check if the filesystem is set to "direct".
			WordPress Filesystem Class for direct PHP file and folder manipulation.
			https://developer.wordpress.org/reference/classes/wp_filesystem_direct/
		@global (wp_filesystem) $wp_filesystem
			https://codex.wordpress.org/Filesystem_API
		@return (bool)
	*/
	private function check_filesystem()
	{
		return isset($GLOBALS['wp_filesystem']) && is_a($GLOBALS['wp_filesystem'],'WP_Filesystem_Direct');

	}// Method


	/**
		[ORIGINAL]
			maybe_make_dir()
		@since 1.5.0
			Changed access to private.
		@access (private)
			Make directory.
			Recursive directory creation based on full path.
			https://developer.wordpress.org/reference/functions/wp_mkdir_p/
		@return (bool)
	*/
	private function maybe_make_dir()
	{
		if(!@is_dir($this->dir)){
			/* phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- This is a valid use case. */
			wp_mkdir_p($this->dir);
		}
		return is_writable($this->dir);

	}// Method


	/**
		[ORIGINAL]
			set_fragments()
		@access (private)
			Set class frsagments.
		@global (array) $_beans_extension_compiler_added_fragment
			Added fragments global.
		@return (void)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function set_fragment()
	{
		// Custom global variable.
		global $_beans_extension_compiler_added_fragment;

		$added_fragment = _beans_utility::__get_global_value($this->config['id'],$_beans_extension_compiler_added_fragment[$this->config['format']]);
		if($added_fragment){
			$this->config['fragment'] = array_merge($this->config['fragment'],$added_fragment);
		}

		/**
		 * @since 1.0.1
		 * 	Filter the compiler fragment files.
		 * 	The dynamic portion of the hook name,$this->config['id'],refers to the compiler id used as a reference.
		 * @param (array) $fragments
		 * 	An array of fragment files.
		*/
		$this->config['fragment'] = apply_filters('beans_extension_compiler_fragment_' . $this->config['id'],$this->config['fragment']);

	}// Method


	/**
		[ORIGINAL]
			set_filename()
		@since 1.5.0
			Renamed method.
			Changed storage location to $filename property.
		@access (private)
			Set the filename for the compiled asset.
		@return (void)
	*/
	private function set_filename()
	{
		$hash = $this->hash($this->config);
		$fragment_filemtime = $this->get_filemtime();
		$hash = $this->get_new_hash($hash,(array)$fragment_filemtime);
		$this->filename = $hash . '.' . $this->get_extension();

	}// Method


	/**
		[ORIGINAL]
			hash()
		@since 1.5.0
		@access (private)
			Hash the given array.
		@param (array) $given_array
			Given array to be hashed.
		@return (string)
	*/
	private function hash(array $given_array)
	{
		/* phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged,WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize -- Valid use case. */
		return substr(md5(@serialize($given_array)),0,7);

	}// Method


	/**
		[ORIGINAL]
			cache_file_exist()
		@access (private)
			Checks if the file exists on the filesystem,meaning it's been cached.
		@return (bool)
	*/
	private function check_cache()
	{
		$filename = $this->get_absolute_path();
		if(empty($filename)){
			return FALSE;
		}
		return file_exists($filename);

	}// Method


	/**
		[ORIGINAL]
			get_filename()
		@since 1.5.0
		@access (private)
			Get the absolute path of the cached and compiled file.
		@return (string)
	*/
	private function get_absolute_path()
	{
		if(isset($this->filename)){
			return $this->dir . '/' . $this->filename;
		}
		return '';

	}// Method


	/**
		[ORIGINAL]
			cache_file()
		@access (private)
			Create cached file.
		@global (wp_filesystem) $wp_filesystem
			https://codex.wordpress.org/Filesystem_API
		@return (bool)
			It is safe to access the filesystem because we made sure it was set.
	*/
	private function create_cache()
	{
		$filename = $this->get_absolute_path();
		if(empty($filename)){
			return FALSE;
		}
		return $GLOBALS['wp_filesystem']->put_contents($filename,$this->compiled_content,FS_CHMOD_FILE);

	}// Method


	/**
		[ORIGINAL]
			enqueue_file()
		@since 1.5.0
			Changed access to private.
		@access (private)
			Enqueue cached file.
		@return (void)|(bool)
	*/
	private function enqueue_cache()
	{
		// Enqueue CSS file.
		if('style' === $this->config['type']){
			return wp_enqueue_style(
				$this->config['id'],
				$this->get_url(),
				$this->config['dependency'],
				$this->config['version']
			);
		}

		// Enqueue JS file.
		if('script' === $this->config['type']){
			return wp_enqueue_script(
				$this->config['id'],
				$this->get_url(),
				$this->config['dependency'],
				$this->config['version'],
				$this->config['in_footer']
			);
		}
		return FALSE;

	}// Method


	/**
		[ORIGINAL]
			get_url()
		@access (private)
			Get cached file url.
		@return (string)
	*/
	private function get_url()
	{
		$url = trailingslashit($this->url) . $this->filename;
		/**
		 * @reference (WP)
		 * 	Determines if SSL is used.
		 * 	https://developer.wordpress.org/reference/functions/is_ssl/
		*/
		if(is_ssl()){
			$url = str_replace('http://','https://',$url);
		}
		return $url;

	}// Method


	/**
		[ORIGINAL]
			get_extension()
		@access (private)
			Get the file extension from the configured "type".
		@return (string)|(null)
	*/
	private function get_extension()
	{
		if('style' === $this->config['type']){
			return 'css';
		}

		if('script' === $this->config['type']){
			return 'js';
		}

	}// Method


	/**
		[ORIGINAL]
			combine_fragments()
		@access (private)
			Combine content of the fragments.
		@return (void)
	*/
	private function combine_fragment()
	{
		$content = '';

		// Loop through fragments.
		foreach($this->config['fragment'] as $fragment){
			// Stop here if the fragment is empty.
			if(empty($fragment)){continue;}
			$fragment_content = $this->get_fragment($fragment);

			// Stop here if no content or content is an html page.
			if(!$fragment_content || preg_match('#^\s*\<#',$fragment_content)){continue;}

			// Continue processing style.
			if('style' === $this->config['type']){
				$fragment_content = $this->replace_url($fragment_content);
				$fragment_content = $this->add_media_query($fragment_content);
			}

			// If there's content,start a new line.
			if($content){
				$content .= "\n\n";
			}
			$content .= $fragment_content;
		}
		$this->compiled_content = !empty($content) ? $this->format($content) : '';

	}// Method


	/**
		[ORIGINAL]
			get_content()
		@since 1.5.0
		@access (private)
			Get the fragment's content.
		@param (string)|(callable)$fragment
			The given fragment from which to get the content.
		@return (bool)|(string)
	*/
	private function get_fragment($fragment)
	{
		// Set the current fragment used by other functions.
		$this->current_fragment = $fragment;

		// If the fragment is callable,call it to get the content.
		if($this->is_callable($fragment)){
			return $this->get_function_content();
		}

		// Try remote content if the internal content returned FALSE.
		$content = $this->get_internal_content();
		if(empty($content)){
			$content = $this->get_remote_content();
		}
		return $content;

	}// Method


	/**
		[ORIGINAL]
			get_internal_content()
		@access (private)
			Get internal file content.
		@global (wp_filesystem) $wp_filesystem
			https://codex.wordpress.org/Filesystem_API
		@return (string)|(bool)
			It is safe to access the filesystem because we made sure it was set.
		@reference
			[Plugin]/utility/beans.php
	*/
	private function get_internal_content()
	{
		$fragment = $this->current_fragment;

		if(!file_exists($fragment)){
			// Replace URL with path.
			$fragment = _beans_utility::__url_to_path($fragment);

			// Stop here if it isn't a valid file.
			/* phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case. */
			if(!file_exists($fragment) || 0 === @filesize($fragment)){
				return FALSE;
			}
		}
		return $GLOBALS['wp_filesystem']->get_contents($fragment);

	}// Method


	/**
		[ORIGINAL]
			get_remote_content()
		@access (private)
			Get external file content.
			Retrieve only the body from the raw response.
			https://developer.wordpress.org/reference/functions/wp_remote_retrieve_body/
		@return (string)|(bool)
	*/
	private function get_remote_content()
	{
		$fragment = $this->current_fragment;

		if(empty($fragment)){
			return FALSE;
		}

		// For a relative URL,add http: to it.
		if(substr($fragment,0,2) === '//'){
			$fragment = 'http:' . $fragment;
		}
		elseif(substr($fragment,0,1) === '/'){
			/**
			 * @since 1.0.1
			 * 	Add domain if it is local but could not be fetched as a file.
			 * @reference (WP)
			 * 	Retrieves the URL for the current site where the front end is accessible.
			 * 	https://developer.wordpress.org/reference/functions/home_url/
			*/
			// $fragment = site_url($fragment);
			$fragment = home_url($fragment);
		}

		/**
		 * @reference (WP)
		 * 	Performs an HTTP request using the GET method and returns its response.
		 * 	https://developer.wordpress.org/reference/functions/wp_remote_get/
		*/
		$request = wp_remote_get($fragment);
		if(is_wp_error($request)){
			return '';
		}

		// If no content was received and the URL is not https,then convert the URL to SSL and retry.
		if((!isset($request['body']) || (200 !== $request['response']['code'])) && (substr($fragment,0,8) !== 'https://')){
			$fragment = str_replace('http://','https://',$fragment);
			$request = wp_remote_get($fragment);
			if(is_wp_error($request)){
				return '';
			}
		}

		if((!isset($request['body']) || (200 !== $request['response']['code']))){
			return FALSE;
		}

		/**
		 * @reference (WP)
		 * 	Retrieve only the body from the raw response.
		 * 	https://developer.wordpress.org/reference/functions/wp_remote_retrieve_body/
		*/
		return wp_remote_retrieve_body($request);

	}// Method


	/**
		[ORIGINAL]
			get_function_content()
		@access (private)
			Get function content.
		@return (string)|(bool)
	*/
	private function get_function_content()
	{
		if(!is_callable($this->current_fragment)){
			return FALSE;
		}
		return call_user_func($this->current_fragment);

	}// Method


	/**
		[ORIGINAL]
			add_content_media_query()
		@access (private)
			Wrap content in query.
		@param (string) $content
			Given content to process.
		@return (string)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function add_media_query($content)
	{
		// Ignore if the fragment is a function.
		if($this->is_callable($this->current_fragment)){
			return $content;
		}

		// Bail out if there are no query args or no media query.
		$query = parse_url($this->current_fragment,PHP_URL_QUERY);
		if(empty($query) || (FALSE === stripos($query,'beans_extension_compiler_media_query'))){
			return $content;
		}

		// Wrap the content in the query.
		return sprintf(
			"@media %s{\n%s\n}\n",
			_beans_utility::__get_global_value('beans_extension_compiler_media_query',wp_parse_args($query)),
			$content
		);

	}// Method


	/**
		[ORIGINAL]
			format_content()
		@access (private)
			Formal CSS,LESS and JS content.
		@param (string) $content
			Given content to process.
		@return (string)
		@reference
			[Plugin]/api/compiler/beans.php
			[Plugin]/api/compiler/vendor/lessc.php
			[Plugin]/api/compiler/vendor/minifier.php
			[Plugin]/include/constant.php
	*/
	private function format($content)
	{
		if('style' === $this->config['type']){
			if('less' === $this->config['format']){
				if(class_exists('\Beans_Lessc') === FALSE){
					require_once BEANS_EXTENSION_API_PATH['compiler'] . 'vendor/lessc.php';
				}
				$less = new \Beans_Lessc();
				$content = $less->compile($content);
			}

			if(!_beans_compiler::__is_dev_mode()){
				return $this->strip_whitespace($content);
			}
			return $content;
		}

		if('script' === $this->config['type'] && !_beans_compiler::__is_dev_mode() && $this->config['minify_js']){
			if(class_exists('\JSMin') === FALSE){
				require_once BEANS_EXTENSION_API_PATH['compiler'] . 'vendor/minifier.php';
			}
			$js_min = new \JSMin($content);
			return $js_min->min();
		}
		return $content;

	}// Method


	/**
		[ORIGINAL]
			replace_css_url()
		@access (private)
			Replace CSS URL shortcuts with a valid URL.
		@param (string) $content
			Given content to process.
		@return (string)
	*/
	private function replace_url($content)
	{
		return preg_replace_callback('#url\s*\(\s*[\'"]*?([^\'"\)]+)[\'"]*\s*\)#i',[$this,'convert_url'],$content);

	}// Method


	/* Method
	_________________________
	*/
	public function convert_url($matches)
	{
		/**
			[ORIGINAL]
				replace_css_url_callback()
			@access (public)
				Convert any CSS URL relative paths to absolute URLs.
			@param (array) $matches
				Matches to process,where 0 is the CSS' URL() and 1 is the URI.
			@return (string)
				Return the rebuilt path converted to an URL.
			@reference
				[Plugin]/utility/beans.php
		*/

		// Checks if the given input is a URL or data URI.
		if(_beans_utility::__is_uri($matches[1])){
			return $matches[0];
		}
		$base = $this->current_fragment;

		// Separate the placeholders and path.
		$paths = explode('../',$matches[1]);

		/**
		 * @since 1.0.1
		 * 	Walk backwards through each of the the fragment's directories,one-by-one.
		 * 	The `foreach` loop provides us with a performant way to walk the fragment back to its base path based upon the number of placeholders.
		*/
		foreach($paths as $path){
			$base = dirname($base);
		}

		// Make sure it is a valid base.
		if('.' === $base){
			$base = '';
		}

		// Rebuild the URL and make sure it is valid using the beans_path_to_url function.
		$url = _beans_utility::__path_to_url(trailingslashit($base) . ltrim(end($paths),'/\\'));

		return 'url("' . $url . '")';

	}// Method


	/**
		[ORIGINAL]
			init_config()
		@since 1.5.0
		@access (private)
			Initialize the configuration.
		@param (array) $config
			Runtime configuration parameters for the Compiler.
		@return (array)
	*/
	private function init_config(array $config)
	{
		// Fix dependencies,if "depedencies" is specified.
		if(isset($config['depedency'])){
			$config['depedency'] = $config['depedency'];
			unset($config['depedency']);
		}

		$defaults = array(
			'id' => FALSE,
			'type' => FALSE,
			'format' => FALSE,
			'fragment' => array(),
			'dependency' => FALSE,
			'in_footer' => FALSE,
			'minify_js' => FALSE,
			'version' => FALSE,
		);
		return array_merge($defaults,$config);

	}// Method


	/**
		[ORIGINAL]
			get_fragments_filemtime()
		@since 1.5.0
		@access (private)
			Get the fragments' modification times.
		@return (array)
	*/
	private function get_filemtime()
	{
		$fragment_filemtime = array();

		foreach($this->config['fragment'] as $index => $fragment){
			// Skip this one if the fragment is a function.
			if($this->is_callable($fragment)){continue;}

			if(file_exists($fragment)){
				/* phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case. */
				$fragment_filemtime[$index] = @filemtime($fragment);
			}
		}
		return $fragment_filemtime;

	}// Method


	/**
		[ORIGINAL]
			get_new_hash()
		@since 1.5.0
		@access (private)
			Get the new hash for the given fragments' modification times.
		@param (string) $hash
			The original hash to modify.
		@param (array) $fragments_filemtime
			Array of fragments' modification times.
		@return (string)
			Set the new hash which will trigger a new compiling.
	*/
	private function get_new_hash($hash,array $fragments_filemtime)
	{
		if(empty($fragment_filemtime)){
			return $hash;
		}

		// Set filemtime hash.
		$_hash = $this->hash($fragment_filemtime);
		$this->remove_modified_file($hash,$_hash);

		return $hash . '-' . $_hash;

	}// Method


	/**
		[ORIGINAL]
			remove_modified_files()
		@since 1.5.0
		@access (private)
			Remove any modified files.  A file is considered modified when:
			1. It has both a base hash and filemtime hash,separated by '-'.
			2. Its base hash matches the given hash.
			3. Its filemtime hash does not match the given filemtime hash.
		@param (string) $hash
			Base hash.
		@param (string) $filemtime_hash
			The filemtime hash (from hashing the fragments).
		@return (void)
		@reference
			[Plugin]/utility/beans.php
	*/
	private function remove_modified_file($hash,$filemtime_hash)
	{
		// List files and directories inside of the specified path.
		$items = _beans_utility::__scan_directory($this->dir);
		if(empty($items)){return;}

		foreach($items as $item){
			// Skip this one if it's a directory.
			/* phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case. */
			if(@is_dir($item)){continue;}

			// Skip this one if it's not the same type.
			if(pathinfo($item,PATHINFO_EXTENSION) !== $this->get_extension()){continue;}

			// Skip this one if it does not have a '-' in the filename.
			if(strpos($item,'-') === FALSE){continue;}
			$hash_parts = explode('-',pathinfo($item,PATHINFO_FILENAME));

			// Skip this one if it does not match the given base hash.
			if($hash_parts[0] !== $hash){continue;}

			// Skip this one if it does match the given filemtime's hash.
			if($hash_parts[1] === $filemtime_hash){continue;}

			// Clean up other modified files.
			/* phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Valid use case. */
			@unlink($this->dir . '/' . $item);
		}

	}// Method


	/**
		[ORIGINAL]
			strip_whitespace()
		@since 1.5.0
			Changed access to private.
		@access (private)
			Minify the CSS.
		@param (string) $content
			Given content to process.
		@return (string)
	*/
	private function strip_whitespace($content)
	{
		$replace = array(
			// Strip comments.
			'#/\*.*?\*/#s' => '',
			// Strip excess whitespace.
			'#\s\s+#' => ' ',
		);
		$search = array_keys($replace);
		$content = preg_replace($search,$replace,$content);

		// Strip all new lines and tabs.
		$content = str_replace(array("\r\n","\r","\n","\t"),'',$content);

		$replace = array(
			': ' => ':',
			'; ' => ';',
			'{' => '{',
			' }' => '}',
			',' => ',',
			'{ ' => '{',
			// Strip optional semicolons.
			';}' => '}',
			// Don't wrap multiple selectors.
			',\n' => ',',
			// Don't wrap closing braces.
			'\n}' => '}',
			// Put each rule on it's own line.
			'}' => "}\n",
			// Remove all line breaks.
			'\n' => '',
			// Remove the whitespace at start of each new line.
			"}\n " => "}\n",
		);
		$search = array_keys($replace);

		return trim(str_replace($search,$replace,$content));

	}// Method


	/**
		[ORIGINAL]
			is_function()
		@since 1.5.0
			Changed access to private.
		@access (private)
			Check if the given fragment is a callable.
		@param (mixed) $fragment
			Given fragment to check.
		@return (bool)
	*/
	private function is_callable($fragment)
	{
		return (is_array($fragment) || is_callable($fragment));

	}// Method


	/**
		[ORIGINAL]
			kill()
		@since 1.5.0
			Changed access to private.
		@access (private)
			Kill it :(
		@return (void)
		@reference
			[Plugin]/utility/beans.php
			[Plugin]/api/html/beans.php
	*/
	private function kill()
	{
		// Send report if set.
		if(_beans_utility::__get_global_value('beans_extension_send_compiler_report')){
			$this->report();
		}

		$html = _beans_html::__output('beans_compiler_error_title_text',sprintf(
			'<h2>%s</h2>',
			esc_html__('Not cool,Beans cannot work its magic :(','beans-extension')
		));

		$html .= _beans_html::__output('beans_compiler_error_message_text',sprintf(
			'<p>%s</p>',
			esc_html__('Your current install or file permission prevents Beans from working its magic. Please get in touch with Beans support. We will gladly get you started within 24 - 48 hours (working days).','beans-extension')
		));

		$html .= _beans_html::__output('beans_compiler_error_contact_text',sprintf(
			'<a class="button" href="https://www.getbeans.io/contact/?compiler_report=1" target="_blanc">%s</a>',
			esc_html__('Contact Beans Support','beans-extension')
		));

		/**
		 * @reference (WP)
		 * 	Retrieves a modified URL query string.
		 * 	https://developer.wordpress.org/reference/functions/add_query_arg/
		*/
		$html .= _beans_html::__output('beans_compiler_error_report_text',sprintf(
			'<p style="margin-top: 12px; font-size: 12px;"><a href="' . add_query_arg('beans_extension_send_compiler_report',TRUE) . '">%1$s</a>. %2$s</p>',
			esc_html__('Send us an automatic report','beans-extension'),
			esc_html__('We respect your time and understand you might not be able to contact us.','beans-extension')
		));

		// Kills WordPress execution and displays HTML page with an error message.
		wp_die(wp_kses_post($html));

	}// Method


	/**
		[ORIGINAL]
			report()
		@since 1.5.0
			Changed access to private.
		@access (private)
			Send report.
		@return (void)
		@reference
			[Plugin]/api/html/beans.php
	*/
	private function report()
	{
		/**
		 * @reference (WP)
		 * 	Sends an email, similar to PHP’s mail function.
		 * 	https://developer.wordpress.org/reference/functions/wp_mail/
		*/
		wp_mail(
			'hello@getbeans.io',
			'Compiler error',
			'Compiler error reported by ' . home_url(),
			array(
				'MIME-Version: 1.0' . "\r\n",
				'Content-type: text/html; charset=utf-8' . "\r\n",
				"X-Mailer: PHP \r\n",
				'From: ' . wp_specialchars_decode(get_option('blogname'),ENT_QUOTES) . ' < ' . get_option('admin_email') . '>' . "\r\n",
				'Reply-To: ' . get_option('admin_email') . "\r\n",
			));

		// Die and display message.
		$message = _beans_html::__output('beans_compiler_report_error_text',sprintf(
			'<p>%s<p>',
			esc_html__('Thanks for your contribution by reporting this issue. We hope to hear from you again.','beans-extension')
		));
		wp_die(wp_kses_post($message));

	}// Method


	/* Method
	_________________________
	*/
	public function set_filname()
	{
		/**
			[ORIGINAL]
				set_filname()
			@deprecated 1.5.0.
			@access (public)
				Set the filename for the compiled asset.
				This method has been replaced with{@see set_filename()}.
			@return (void)
		*/
		_deprecated_function(__METHOD__,'1.5.0','set_filename');
		$this->set_filename();

	}// Method


	/* Method
	_________________________
	*/
	public function get($property)
	{
		/**
			[ORIGINAL]
				__get()
			@since 1.5.0
			@access (public)
				Get the property's value.
			@param (string) $property
				Name of the property to get.
			@return (mixed)
		*/
		if(property_exists($this,$property)){
			return $this->{$property};
		}

	}// Method


}// Class
endif;
