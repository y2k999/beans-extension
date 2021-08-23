<?php 
/**
 * Trait for this plugin.
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
if(trait_exists('_trait_hook') === FALSE) :
trait _trait_hook
{
/**
 * @since 1.0.1
 * 	Define shared method for actions and filters.
 * 
 * [TOC]
 * 	set_parameter_callback()
 * 	set_parameter_hook()
 * 	invoke_hook()
*/


	/* Setter
	_________________________
	*/
	public function set_parameter_callback($args = array())
	{
		/**
			@access (public)
				Build params for WP action/filter hooks depending on uniqueue callback names.
			@param (array) $args
				The collection of hooks that is being registered (that is, actions or filters).
			@return (array)
				The collection of hooks that is being registered (that is, actions or filters).
			@reference
				[Plugin]/include/constant.php
		*/
		if(empty($args)){return;}

		$return = array();

		// $class = __utility_get_class(get_class($this));

		foreach($args as $key => $value){
			if(!isset($value['tag']) || !isset($value['hook'])){break;}

			$return[] = array(
				'beans_id' => isset($value['beans_id']) ? $value['beans_id'] : self::$_class . $key,
				'tag' => $value['tag'],
				'hook' => $value['hook'],
				'callback' => $key,
				'priority' => isset($value['priority']) ? $value['priority'] : BEANS_EXTENSION_PRIORITY['default'],
				'args' => isset($value['args']) ? $value['args'] : 1,
			);
		}
		return $return;

	}// Method


	/* Setter
	_________________________
	*/
	public function set_parameter_hook($args = array())
	{
		/**
			@access (public)
				Build params for WP action/filter hooks depending on uniqueue hook names.
			@param (array) $args
				The collection of hooks that is being registered (that is, actions or filters).
			@return (array)
				The collection of hooks that is being registered (that is, actions or filters).
			@reference
				[Plugin]/include/constant.php
		*/
		if(empty($args)){return;}

		$return = array();

		foreach($args as $key => $value){
			if(!isset($value['tag']) || !isset($value['callback'])){break;}

			$return[] = array(
				'beans_id' => isset($value['beans_id']) ? $value['beans_id'] : self::$_class . $key,
				'tag' => $value['tag'],
				'hook' => $key,
				'callback' => $value['callback'],
				'priority' => isset($value['priority']) ? $value['priority'] : BEANS_EXTENSION_PRIORITY['default'],
				'args' => isset($value['args']) ? $value['args'] : 1,
			);
		}
		return $return;

	}// Method


	/* Method
	_________________________
	*/
	public function invoke_hook($args)
	{
		/**
			@access (public)
				Execute requested action/filter hooks.
			@param (array) $args
				The collection of hooks that is being registered (that is, actions or filters).
			@return (void)
			@reference (Beans)
				Hooks a function on to a specific action.
				https://www.getbeans.io/code-reference/functions/beans_add_action/
		*/
		if(empty($args)){return;}

		foreach($args as $item){
			if($item['tag'] === 'beans_add_action'){
				$item['tag'](
					$item['beans_id'],
					$item['hook'],
					[$this,$item['callback']],
					$item['priority'],
					$item['args']
				);
			}
			else{
				$item['tag'](
					$item['hook'],
					[$this,$item['callback']],
					$item['priority'],
					$item['args']
				);
			}
		}

	}// Method


}// Trait
endif;
