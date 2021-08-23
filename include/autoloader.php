<?php
/**
 * Fired during plugin activation.
 * @package Beans_Extension
 * @license GPL3.0+
 * @since 1.0.1
*/

/**
 * Inspired by Beans Framework WordPress Theme
 * @link https://www.getbeans.io
 * @author Thierry Muller
 * 
 * Inspired by WeCodeArt WordPress Theme
 * @link https://www.wecodeart.com/
 * @author Bican Marian Valeriu
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
if(class_exists('_beans_autoloader') === FALSE) :
class _beans_autoloader
{
/**
 * @since 1.0.1
 * 	The class autoloader.
 * 
 * [TOC]
 * 	__construct()
 * 	register()
 * 	get_path()
*/

	/**
		@access (private)
			Class properties.
		@var (array) $cached_path
			Cached file paths.
		@var (array) $separator
			Namespace separator.
	*/
	private $cached_path = array();
	private $separator = '_';


	/* Constructor
	_________________________
	*/
	public function __construct()
	{
		/**
			@access (public)
				Register given function as __autoload() implementation
				https://www.php.net/manual/en/function.spl-autoload-register.php
			@return (void)
		*/
		spl_autoload_register([$this,'register']);

	}// Method


	/* Method
	_________________________
	*/
	protected function register($classname)
	{
		/**
			@access (protected)
				The class autoloader.
				Finds the path to a class that we're requiring and includes the file.
			@param (string)$classname
				The name of the class we're trying to load.
			@return (bool)|(void)
		*/

		// Not a Beans_Extension file, early exit.
		if(FALSE === strpos($classname,'Beans_Extension')){
			return FALSE;
		}

		// Check if we've got it cached and ready.
		if(isset($this->cached_path[$classname]) && file_exists($this->cached_path[$classname])){
			require_once $this->cached_path[$classname];
			return;
		}

		// If no chach, run get_paths, set cache and include.
		$path = $this->get_path($classname);

		if(file_exists($path)){
			$this->cached_path[$classname] = $path;
			require_once $path;
			return;
		}

	}// Method


	/**
		@access (protected)
			Get an array of possible paths for the file.
		@param (string) $class_name
			The name of the class we're trying to load.
		@return (array)
			[Plugin]/include/constant.php
	*/
	protected function get_path($classname)
	{
		// Trim namespace
		$classname = str_replace('Beans_Extension\\','',$classname);

		$classname = ltrim($classname,'\\');
		$classname = str_replace('Beans_Extension\\','',$classname);

		$path = '';

		if(preg_match("/^_beans_admin_/",$classname)){
			$classname = str_replace(substr($classname,0,13),'',$classname);

			// Build the filename
			$exploded = explode($this->separator,$classname);

			switch(strtolower($exploded[1])){
				case 'tab' :
					$path = BEANS_EXTENSION_API_PATH['admin'] . 'tab/' . strtolower($exploded[0]) . '.php';
					break;
				case 'data' :
					$path = BEANS_EXTENSION_API_PATH['admin'] . 'tab/data/' . strtolower($exploded[0]) . '.php';
					break;
				case 'app' :
					$path = BEANS_EXTENSION_API_PATH['admin'] . 'tab/app/' . strtolower($exploded[0]) . '.php';
					break;
			}
		}
		elseif(!preg_match("/^_beans_admin_/",$classname) && preg_match("/^_beans_/",$classname)){
			$classname = str_replace(substr($classname,0,7),'',$classname);

			// Build the filename
			$exploded = explode($this->separator,$classname);
			$classname = strtolower(end($exploded));

			if($classname === 'meta'){
				if(($exploded[0] === 'post') || ($exploded[1] === 'post')){
					$classname = 'post-meta';
				}
				if(($exploded[0] === 'term') || ($exploded[1] === 'term')){
					$classname = 'term-meta';
				}
			}

			if($classname === 'utility'){
				$path = BEANS_EXTENSION_API_PATH[$classname] . 'beans.php';
			}
			elseif($classname === 'component'){
				$path = BEANS_EXTENSION_API_PATH['include'] . $classname . '.php';
			}
			elseif($classname === 'accessibility'){
				$path = BEANS_EXTENSION_API_PATH['asset'] . $classname . '.php';
			}
			elseif(in_array($exploded[0],array('control','runtime','anonymous','optimize','attribute'),TRUE)){
				$path = BEANS_EXTENSION_API_PATH[$classname] . $exploded[0] . '.php';
			}
			else{
				$path = BEANS_EXTENSION_API_PATH[$classname] . 'beans.php';
			}
		}

		// Return paths
		return $path;

	}// Method


}// Class
endif;
new _beans_autoloader();
// _theme_autoloader::__get_instance();
