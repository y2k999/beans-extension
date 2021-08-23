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
if(class_exists('_beans_action') === FALSE) :
class _beans_action
{
/**
 * @since 1.0.1
 * 	Beans actions extends WP actions by registering each action with a unique ID.
 * 	While WP requires two or three arguments to remove an action,
 * 	Beans actions can be modified,replaced,removed or reset using only the ID as a reference.
 * 
 * [TOC]
 * 	__get_instance()
 * 	__construct()
 * 	beans_add_action()
 * 	beans_add_smart_action()
 * 	beans_modify_action()
 * 	beans_modify_action_hook()
 * 	beans_modify_action_callback()
 * 	beans_modify_action_priority()
 * 	beans_modify_action_arguments()
 * 	beans_replace_action()
 * 	beans_replace_action_hook()
 * 	beans_replace_action_callback()
 * 	beans_replace_action_priority()
 * 	beans_replace_action_arguments()
 * 	beans_remove_action()
 * 	beans_reset_action()
 * 	_beans_add_anonymous_action()
 * 	_beans_render_action()
 * 	_beans_get_action()
 * 	_beans_set_action()
 * 	_beans_unset_action()
 * 	_beans_merge_action()
 * 	_beans_get_current_action()
 * 	_beans_build_action_array()
 * 	_beans_when_has_action_do_render()
 * 	_beans_unique_action_id()
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
				Class constructor.
				This is only called once, since the only way to instantiate this is with the get_instance() method in trait (singleton.php).
			@global (array) $_beans_extension_registered_action
				Registerd actions global.
			@return (void)
			@reference
				[Plugin]/trait/singleton.php
		*/

		// Initialize action globals.
		global $_beans_extension_registered_action;
		if(!isset($_beans_extension_registered_action)){
			$_beans_extension_registered_action = array(
				'added' => array(),
				'modified' => array(),
				'removed' => array(),
				'replaced' => array(),
			);
		}

	}// Method


	/* Method
	_________________________
	*/
	public static function __add_action($id,$hook,$callback,$priority = 10,$args = 1)
	{
		/**
			[ORIGINAL]
				beans_add_action()
				https://www.getbeans.io/code-reference/functions/beans_add_action/
			@access (public)
				Hooks a callback (function or method) to a specific action event.
			@since 1.5.0
				Returns FALSE when action is not added via add_action.
				https://developer.wordpress.org/reference/functions/add_action/
				with the exception of being registered by ID within Beans in order to be manipulated by the other Beans actions functions.
			@param (string) $id
				The action's Beans ID,a unique string(ID) tracked within Beans for this action.
			@param (string) $hook
				The name of the action to which the `$callback` is hooked.
			@param (callable) $callback
				The name of the function|method you wish to be called when the action event fires.
			@param (int) $priority...,
				[Optional]
				Used to specify the order in which the callbacks associated with a particular action are executed.
				[Default] 10
				Lower numbers correspond with earlier execution.
				Callbacks with the same priority are executed in the order in which they were added to the action.
			@param (int) $args...,
				[Optional]
				The number of arguments the callback accepts.
				[Default] 1
			@return (bool)
				Will always return true(Validate action arguments?).
		*/
		$action = array(
			'hook' => $hook,
			'callback' => $callback,
			'priority' => $priority,
			'args' => $args,
		);

		/**
		 * @since 1.0.1
		 * 	Replace original if set.
		 * 	If the ID is set to be "replaced",then replace that(those) parameter(s).
		*/
		$replaced_action = self::__get_action($id,'replaced');
		if(!empty($replaced_action)){
			$action = array_merge($action,$replaced_action);
		}
		$action = self::__set_action($id,$action,'added',TRUE);

		/**
		 * @since 1.0.1
		 * 	Stop here if removed.
		 * 	If the ID is set to be "removed",then bail out.
		*/
		if(self::__get_action($id,'removed')){
			return FALSE;
		}

		/**
		 * @since 1.0.1
		 * 	Merge modified.
		 * 	If the ID is set to be "modified",then modify that(those) parameter(s).
		*/
		$modified_action = self::__get_action($id,'modified');
		if(!empty($modified_action)){
			$action = array_merge($action,$modified_action);
		}

		/**
		 * @reference (WP)
		 * 	Hooks a function on to a specific action.
		 * 	https://developer.wordpress.org/reference/functions/add_action/
		*/
		return add_action($action['hook'],$action['callback'],$action['priority'],$action['args']);

	}// Method


	/* Method
	_________________________
	*/
	public static function __smart_action($hook,$callback,$priority = 10,$args = 1)
	{
		/**
			[ORIGINAL]
				beans_add_smart_action()
				https://www.getbeans.io/code-reference/functions/beans_add_smart_action/
			@access (public)
				Set {@see beans_add_action()} using the callback argument as the action ID.
				This function is a shortcut of {@see beans_add_action()}.
				It does't require a Beans ID as it uses the callback argument instead.
			@since 1.5.0
				Returns FALSE when action is not added via add_action.
				https://developer.wordpress.org/reference/functions/add_action/
			@param (string) $hook
			@param (callable) $callback
			@param (int) $priority...,
			@param (int) $args...,
			@return (bool)
				Will always return true.
		*/
		return self::__add_action($callback,$hook,$callback,$priority,$args);

	}// Method


	/* Method
	_________________________
	*/
	public static function __modify_action($id,$hook = NULL,$callback = NULL,$priority = NULL,$args = NULL)
	{
		/**
			[ORIGINAL]
				beans_modify_action()
				https://www.getbeans.io/code-reference/functions/beans_modify_action/
			@since 1.5.0
				Improved action parameter filtering.
			@access (public)
				Modify one or more of the arguments for the given action,i.e. referenced by its Bean's ID.
				This function modifies a registered action using {@see beans_add_action()} or {@see beans_add_smart_action()}.
				Each optional argument must be set to NULL to keep the original value.
				The original action can be reset using {@see beans_reset_action()}.
			@param (string) $id
				The action ID
			@param (string)|(null) $hook...,
				[Optional]
				The new action's event name to which the $callback is hooked.
				Use NULL to keep the original value.
			@param (callable)|(null) $callback...,
				[Optional]
				The new callback (function or method) you wish to be called.
				Use NULL to keep the original value.
			@param(int)|(null) $priority...,
				[Optional].
				The new priority.
				Use NULL to keep the original value.
			@param (int)|(null) $args...,
				[Optional]
				The new number of arguments the $callback accepts.
				Use NULL to keep the original value.
			@return (bool)
		*/

		// If no changes were passed in,there's nothing to modify.Bail out.
		$action = self::__array_filter($hook,$callback,$priority,$args);
		if(empty($action)){
			return FALSE;
		}

		// If the action is registered,let's remove it.
		$current_action = self::__get_current($id);
		if(!empty($current_action)){
			/**
			 * @reference (WP)
			 * 	Removes a function from a specified action hook.
			 * 	https://developer.wordpress.org/reference/functions/remove_action/
			*/
			remove_action($current_action['hook'],$current_action['callback'],$current_action['priority']);
		}

		// Merge the modified parameters and register with Beans.
		$action = self::__merge_action($id,$action,'modified');

		// If there is no action to modify,bail out.
		if(empty($current_action)){
			return FALSE;
		}

		// Overwrite the modified parameters.
		$action = array_merge($current_action,$action);

		/**
		 * @reference (WP)
		 * 	Hooks a function on to a specific action.
		 * 	https://developer.wordpress.org/reference/functions/add_action/
		*/
		return add_action($action['hook'],$action['callback'],$action['priority'],$action['args']);

	}// Method


	/* Method
	_________________________
	*/
	public static function __modify_hook($id,$hook)
	{
		/**
			[ORIGINAL]
				beans_modify_action_hook()
				https://www.getbeans.io/code-reference/functions/beans_modify_action_hook/
			@since 1.5.0
				Return FALSE if the hook is empty or not a string.
			@access (public)
				Modify one or more of the arguments for the given action,i.e. referenced by its Bean's ID.
				This function is a shortcut of {@see beans_modify_action()}.
			@param (string) $id
				The action's Beans ID,a unique ID tracked within Beans for this action.
			@param (string) $hook
				The name of the new action to which the $callback is hooked.
				Use NULL to keep the original value.
			@return (bool)
		*/
		if(empty($hook) || !is_string($hook)){
			return FALSE;
		}
		return self::__modify_action($id,$hook);

	}// Method


	/* Method
	_________________________
	*/
	public static function __modify_callback($id,$callback)
	{
		/**
			[ORIGINAL]
				beans_modify_action_callback()
				https://www.getbeans.io/code-reference/functions/beans_modify_action_callback/
			@since 1.5.0
				Return FALSE if the callback is empty.
			@access (public)
				Modify the callback of the given action,i.e. referenced by its Bean's ID.
				This function is a shortcut of {@see beans_modify_action()}.
			@param (string) $id
				The action's Beans ID,a unique ID tracked within Beans for this action.
			@param (callable) $callback
				The name of the new function you wish to be called.
				Use NULL to keep the original value.
			@return (bool)
		*/
		if(empty($callback)){
			return FALSE;
		}
		return self::__modify_action($id,NULL,$callback);

	}// Method


	/* Method
	_________________________
	*/
	public static function __modify_priority($id,$priority)
	{
		/**
			[ORIGINAL]
				beans_modify_action_priority()
				https://www.getbeans.io/code-reference/functions/beans_modify_action_priority/
			@access (public)
				Modify the priority of the given action,i.e. referenced by its Bean's ID.
				This function is a shortcut of {@see beans_modify_action()}.
			@param (string) $id
				The action's Beans ID,a unique ID tracked within Beans for this action.
			@param (int)|(string) $priority
				The new priority.
				Use NULL to keep the original value.
			@return (bool)
		*/
		return self::__modify_action($id,NULL,NULL,$priority);

	}// Method


	/* Method
	_________________________
	*/
	public static function __modify_args($id,$args)
	{
		/**
			[ORIGINAL]
				beans_modify_action_arguments()
				https://www.getbeans.io/code-reference/functions/beans_modify_action_arguments/
			@access (public)
				Modify the number of arguments of the given action,i.e. referenced by its Bean's ID.
				This function is a shortcut of {@see beans_modify_action()}.
			@param (string) $id
				The action's Beans ID,a unique ID tracked within Beans for this action.
			@param (int)|(string) $args
				The new number of arguments the function accepts.
				Use NULL to keep the original value.
			@return (bool)
		*/
		return self::__modify_action($id,NULL,NULL,	NULL,$args);

	}// Method


	/* Method
	_________________________
	*/
	public static function __replace_action($id,$hook = NULL,$callback = NULL,$priority = NULL,$args = NULL)
	{
		/**
			[ORIGINAL]
				beans_replace_action()
				https://www.getbeans.io/code-reference/functions/beans_replace_action/
			@since 1.5.0
				Returns FALSE when no replacement arguments are passed.
			@access (public)
				Replace one or more of the arguments for the given action,i.e. referenced by its Bean's ID.
				This function replaces an action registered using {@see beans_add_action()} or {@see beans_add_smart_action()}.
				Each optional argument must be set to NULL to keep the original value.
				This function is not resettable as it overwrites the original action's argument(s).
				That means using {@see beans_reset_action()} will not restore the original action.
			@param (string) $id
				The action's Beans ID,a unique ID tracked within Beans for this action.
			@param (string)|(null) $hook...,
				[Optional]
				The new action's event name to which the $callback is hooked.
				Use NULL to keep the original value.
			@param (callable)|(null)$callback...,
				[Optional]
				The new callback (function or method) you wish to be called.
				Use NULL to keep the original value.
			@param (int)|(null) $priority...,
				[Optional]
				The new priority.
				Use NULL to keep the original value.
			@param (int)|(null) $args...,
				[Optional]
				The new number of arguments the $callback accepts.
				Use NULL to keep the original value.
			@return (bool)
		*/

		// If no changes were passed in,there's nothing to modify.Bail out.
		$action = self::__array_filter($hook,$callback,$priority,$args);
		if(empty($action)){
			return FALSE;
		}

		// Set and get the latest "replaced" action.
		$action = self::__merge_action($id,$action,'replaced');

		/**
		 * @since 1.0.1
		 * 	Modify the action.
		 * 	If there's a current action,merge it with the replaced one;else,it will be replaced when the original is added.
		*/
		$is_modified = self::__modify_action($id,$hook,$callback,$priority,$args);
		if($is_modified){
			self::__merge_action($id,$action,'added');
		}
		return $is_modified;

	}// Method


	/* Method
	_________________________
	*/
	public static function __replace_hook($id,$hook)
	{
		/**
			[ORIGINAL]
				beans_replace_action_hook()
				https://www.getbeans.io/code-reference/functions/beans_replace_action_hook/
			@access (public)
				Replace the action's event name (hook) for the given action,i.e. referenced by its Bean's ID.
				This function is a shortcut of {@see beans_replace_action()}.
			@since 1.5.0
				Return FALSE if the hook is empty or not a string.
			@param (string) $id
				The action's Beans ID,a unique ID tracked within Beans for this action.
			@param (string) $hook
				The name of the new action to which the $callback is hooked.
				Use NULL to keep the original value.
			@return (bool)
		*/
		if(empty($hook) || !is_string($hook)){
			return FALSE;
		}
		return self::__replace_action($id,$hook);

	}// Method


	/* Method
	_________________________
	*/
	public static function __replace_callback($id,$callback)
	{
		/**
			[ORIGINAL]
				beans_replace_action_callback()
				https://www.getbeans.io/code-reference/functions/beans_replace_action_callback/
			@since 1.5.0
				Return FALSE if the callback is empty.
			@access (public)
				Replace the callback of the given action,i.e. referenced by its Bean's ID.
				This function is a shortcut of {@see beans_replace_action()}.
			@param (string) $id
				The action's Beans ID,a unique ID tracked within Beans for this action.
			@param (string) $callback
				The name of the new function you wish to be called.
				Use NULL to keep the original value.
			@return (bool)
		*/
		if(empty($callback)){
			return FALSE;
		}
		return self::__replace_action($id,NULL,$callback);

	}// Method


	/* Method
	_________________________
	*/
	public static function __replace_priority($id,$priority)
	{
		/**
			[ORIGINAL]
				beans_replace_action_priority()
				https://www.getbeans.io/code-reference/functions/beans_replace_action_priority/
			@access (public)
				Replace the priority of the given action,i.e. referenced by its Bean's ID.
				This function is a shortcut of {@see beans_replace_action()}.
			@param (string) $id
				The action's Beans ID,a unique ID tracked within Beans for this action.
			@param (int) $priority
				The new priority.
				Use NULL to keep the original value.
			@return (bool)
		*/
		return self::__replace_action($id,NULL,NULL,$priority);

	}// Method


	/* Method
	_________________________
	*/
	public static function __replace_args($id,$args)
	{
		/**
			[ORIGINAL]
				beans_replace_action_arguments()
				https://www.getbeans.io/code-reference/functions/beans_replace_action_arguments/
			@access (public)
				Replace the number of arguments of the given action,i.e. referenced by its Bean's ID.
				This function is a shortcut of {@see beans_replace_action()}.
			@param (string) $id
				The action's Beans ID,a unique ID tracked within Beans for this action.
			@param (int) $args
				The new number of arguments the function accepts.
				Use NULL to keep the original value.
			@return (bool)
		*/
		return self::__replace_action($id,NULL,NULL,	NULL,$args);

	}// Method


	/* Method
	_________________________
	*/
	public static function __remove_action($id)
	{
		/**
			[ORIGINAL]
				beans_remove_action()
				https://www.getbeans.io/code-reference/functions/beans_remove_action/
			@access (public)
				Remove an action.
				This function removes an action registered using {@see beans_add_action()} or {@see beans_add_smart_action()}.
				The original action can be re-added using {@see beans_reset_action()}.
				This function is "load order" agnostic,meaning that you can remove an action before it's added.
			@since 1.5.0
				When no current action,sets "removed" to [Default] configuration.
			@param (string) $id
				The action's Beans ID,a unique ID tracked within Beans for this action.
			@return (bool)
		*/
		$action = self::__get_current($id);

		if(!empty($action)){
			/**
			 * @since 1.0.1
			 * 	When there is a current action,remove it.
			 * @reference (WP)
			 * 	Removes a function from a specified action hook.
			 * 	https://developer.wordpress.org/reference/functions/remove_action/
			*/
			remove_action($action['hook'],$action['callback'],$action['priority']);
		}
		else{
			// If the action is not registered yet,set it to a [Default] configuration.
			$action = array(
				'hook' => NULL,
				'callback' => NULL,
				'priority' => NULL,
				'args' => NULL,
			);
		}
		// Store as "removed".
		return self::__set_action($id,$action,'removed');

	}// Method


	/* Method
	_________________________
	*/
	public static function __reset_action($id)
	{
		/**
			[ORIGINAL]
				beans_reset_action()
				https://www.getbeans.io/code-reference/functions/beans_reset_action/
			@access (public)
				Reset an action.
				This function resets an action registered using {@see beans_add_action()} or {@see beans_add_smart_action()}.
				If the original values were replaced using {@see beans_replace_action()},these values will be used,as {@see beans_replace_action()} is not resettable.
			@since 1.5.0
				Bail out if the action does not need to be reset.
			@param (string) $id
				The action's Beans ID,a unique ID tracked within Beans for this action.
			@return (bool)
		*/
		self::__unset_action($id,'modified');
		self::__unset_action($id,'removed');

		// If there is no "added" action,bail out.
		$action = self::__get_action($id,'added');
		if(empty($action)){
			return FALSE;
		}

		// If there's no current action,return the "added" action.
		$current = self::__get_current($id);
		if(empty($current)){
			return $action;
		}

		/**
		 * @reference (WP)
		 * 	Removes a function from a specified action hook.
		 * 	https://developer.wordpress.org/reference/functions/remove_action/
		 * 	Hooks a function on to a specific action.
		 * 	https://developer.wordpress.org/reference/functions/add_action/
		*/
		remove_action($current['hook'],$current['callback'],$current['priority']);
		add_action($action['hook'],$action['callback'],$action['priority'],$action['args']);

		return $action;

	}// Method


	/* Method
	_________________________
	*/
	public static function __anonymous_action($hook,array $callback,$priority = 10,$args = 1)
	{
		/**
			[ORIGINAL]
				_beans_add_anonymous_action()
			@access (public)
				Add anonymous callback using a class since php 5.2 is still supported.
			@param (string) $hook
				The name of the action to which the $callback is hooked.
			@param (array) $callback
				The callback to register to the given $hook and arguments to pass.
			@param (int) $priority...,
				[Optional]
				Used to specify the order in which the functions associated with a particular action are executed.
				[Default] 10
				Lower numbers correspond with earlier execution,and functions with the same priority are executed in the order in which they were added to the action.
			@param (int) $args...,
				[Optional]
				The number of arguments the function accepts.
				[Default] 1
			@return (_Beans_Anonymous_Action)
			@reference
				[Plugin]/api/action/anonymous.php
		*/
		return new _beans_anonymous_action($hook,$callback,$priority,$args);

	}// Method


	/* Method
	_________________________
	*/
	public static function __render_action($hook)
	{
		/**
			[ORIGINAL]
				_beans_render_action()
			@access (public)
				Render action which can therefore be stored in a variable.
			@param (mixed) $hook
				Hook and possibly sub-hooks to be rendered.
			@return (bool)|(NULL)|(string)
		*/
		$args = func_get_args();

		// Return simple action if no sub-hook(s) is(are) set.
		if(!preg_match_all('#\[(.*?)\]#',$args[0],$sub_hooks)){
			return self::__get_callback($args);
		}

		$output = NULL;
		$prefix = current(explode('[',$args[0]));
		$variable_prefix = $prefix;
		$suffix = preg_replace('/^.*\]\s*/','',$args[0]);

		// Base hook.
		$args[0] = $prefix . $suffix;

		// If the base hook is registered,render it.
		self::__get_callback($args,$output);

		foreach((array)$sub_hooks[0] as $index => $sub_hook){
			$variable_prefix .= $sub_hook;
			$levels = array($prefix . $sub_hook . $suffix);

			// Cascade sub-hooks.
			if($index > 0){
				$levels[] = $variable_prefix . $suffix;
			}

			// Apply sub-hooks.
			foreach($levels as $level){
				$args[0] = $level;
				// If the level is registered,render it.
				self::__get_callback($args,$output);

				// Apply filter without square brackets for backwards compatibility.
				$args[0] = preg_replace('#(\[|\])#','',$args[0]);

				// If the backwards compatible $args[0] is registered,render it.
				self::__get_callback($args,$output);
			}
		}
		return $output;

	}// Method


	/**
		[ORIGINAL]
			_beans_get_action()
		@access (private)
			Get the action's configuration for the given ID and status.
			Returns `FALSE` if the action is not registered with Beans.
		@global (array) $_beans_extension_registered_action
			Registerd actions global.
		@param (string) $id
			The action's Beans ID,a unique ID tracked within Beans for this action.
		@param (string) $status
			Status for which to get the action.
		@return (array)|(bool)
		@reference
			[Plugin]/utility/beans.php
	*/
	private static function __get_action($id,$status)
	{
		// Custom global variable.
		global $_beans_extension_registered_action;

		// If the status is empty,return FALSE,as no actions are registered.
		$registered = _beans_utility::__get_global_value($status,$_beans_extension_registered_action);
		if(empty($registered)){
			return FALSE;
		}

		// If the action is empty,return FALSE.
		$id = self::__unique_id($id);

		$action = _beans_utility::__get_global_value($id,$registered);
		if(empty($action)){
			return FALSE;
		}
		return $action;

	}// Method


	/**
		[ORIGINAL]
			_beans_set_action()
		@access (private)
			Store the action's configuration for the given ID and status.
			What happens if the action's configuration is already registered?
			If the `$overwrite` flag is set to `TRUE`,then the new action's configuration is stored,overwriting the previous one.
			Else,the registered action's onfiguration is returned.
		@global (array) $_beans_extension_registered_action
			Registerd actions global.
		@param (string) $id
			The action's Beans ID,a unique ID tracked within Beans for this action.
		@param (array)|(mixed) $action
			The action configuration to store.
		@param (string) $status
			Status for which to store the action.
		@param (bool) $overwrite...,
			[Optional]
			When set to `TRUE`,the new action's configuration is stored,overwriting a previously stored configuration (if one exists).
		@return (array)|(mixed)
	*/
	private static function __set_action($id,$action,$status,$overwrite = FALSE)
	{
		$id = self::__unique_id($id);

		// If not overwriting,return the registered action (if it's registered).
		if(!$overwrite){
			$registered = self::__get_action($id,$status);
			if(!empty($registered)){
				return $registered;
			}
		}

		if(!empty($action) || 'removed' === $status){
			// Custom global variable.
			global $_beans_extension_registered_action;
			$_beans_extension_registered_action[$status][$id] = $action;
		}
		return $action;

	}// Method


	/**
		[ORIGINAL]
			_beans_unset_action()
		@access (private)
			Unset the action's configuration for the given ID and status.
			Returns `FALSE`
			 - if there are is no action registered with Beans actions for the given ID and status.
			Else,returns TRUE
			 - when complete.
		@global (array) $_beans_extension_registered_action
			Registerd actions global.
		@param (string) $id
			The action's Beans ID,a unique ID tracked within Beans for this action.
		@param (string) $status
			Status for which to get the action.
		@return (bool)
	*/
	private static function __unset_action($id,$status)
	{
		// Bail out if the ID is not registered for the given status.
		$id = self::__unique_id($id);
		if(FALSE === self::__get_action($id,$status)){
			return FALSE;
		}

		// Custom global variable.
		global $_beans_extension_registered_action;
		unset($_beans_extension_registered_action[$status][$id]);

		return TRUE;

	}// Method


	/**
		[ORIGINAL]
			_beans_merge_action()
		@access (private)
			Merge the action's configuration and then store it for the given ID and status.
			If the action's configuration has not already been registered with Beans,just store it.
		@param (string) $id
			The action's Beans ID,a unique ID tracked within Beans for this action.
		@param (array) $action
			The new action's configuration to merge and then store.
		@param (string) $status
			Status for which to merge/store this action.
		@return (array)
			Now store/register it.
	*/
	private static function __merge_action($id,array $action,$status)
	{
		$id = self::__unique_id($id);
		$registered = self::__get_action($id,$status);

		// If the action's configuration is already registered with Beans,merge the new configuration with it.
		if(!empty($registered)){
			$action = array_merge($registered,$action);
		}
		return self::__set_action($id,$action,$status,TRUE);

	}// Method


	/**
		[ORIGINAL]
			_beans_get_current_action()
		@since 1.5.0
			Bails out if there is no "added" action registered.
		@access (private)
			Get the current action,meaning get from the "added" and/or "modified" statuses.
		@param (string) $id
			The action's Beans ID,a unique ID tracked within Beans for this action.
		@return (array)|(bool)
	*/
	private static function __get_current($id)
	{
		// Bail out if the action is "removed".
		if(self::__get_action($id,'removed')){
			return FALSE;
		}

		// If there is no "added" action registered,bail out.
		$added = self::__get_action($id,'added');
		if(empty($added)){
			return FALSE;
		}

		// If the action is set to be modified,merge the changes and return the action.
		$modified = self::__get_action($id,'modified');
		if(!empty($modified)){
			return array_merge($added,$modified);
		}
		return $added;

	}// Method


	/**
		[ORIGINAL]
			_beans_build_action_array()
		@access (private)
			Build the action's array for only the valid given arguments.
		@param (string)|(null)$hook...,
			[Optional]
			The action event's name to which the $callback is hooked.
			Valid when not FALSEy,i.e. (meaning not `NULL`,`FALSE`,`0`,`0.0`,an empty string,or empty array).
		@param (callable)|(null)$callback...,
			[Optional]
			The callback (function or method) you wish to be called when the event fires.
			Valid when not FALSE,i.e. (meaning not `NULL`,`FALSE`,`0`,`0.0`,an empty string,or empty array).
		@param (int)|(null) $priority...,
			[Optional]
			Used to specify the order in which the functions associated with a particular action are executed.
			Valid when it's numeric,including 0.
		@param (int)|(null)$args..,
			[Optional]
			The number of arguments the callback accepts.
			Valid when it's numeric,including 0.
		@return (array)
			If no changes were passed in,there's nothing to modify.
			Bail out.
	*/
	private static function __array_filter($hook = NULL,$callback = NULL,$priority = NULL,$args = NULL)
	{
		$action = array();

		if(!empty($hook)){
			$action['hook'] = $hook;
		}

		if(!empty($callback)){
			$action['callback'] = $callback;
		}

		foreach(array('priority','args') as $arg_name){
			$arg = ${$arg_name};
			if(is_numeric($arg)){
				$action[$arg_name] = (int)$arg;
			}
		}
		return $action;

	}// Method


	/**
		[ORIGINAL]
			_beans_when_has_action_do_render()
		@access (private)
			Render all hooked action callbacks by firing {@see do_action()}.
			The output is captured in the buffer and then returned.
		@param (array) $args
			Array of arguments.
		@param (string) $output
			The output to be updated.
		@return (string)|(bool)
	*/
	private static function __get_callback(array $args,&$output = '')
	{
		/**
		 * @reference (WP)
		 * 	Checks if any action has been registered for a hook.
		 * 	https://developer.wordpress.org/reference/functions/has_action/
		*/
		if(!has_action($args[0])){
			return FALSE;
		}
		ob_start();
		/**
		 * @reference (WP)
		 * 	Execute functions hooked on a specific action hook.
		 * 	https://developer.wordpress.org/reference/functions/do_action/
		*/
		call_user_func_array('do_action',$args);
		$output .= ob_get_clean();

		return $output;

	}// Method


	/**
		[ORIGINAL]
		_beans_unique_action_id()
		@access (private)
			Make sure the action ID is unique.
		@param (mixed) $callback
			Callback to convert into a unique ID.
		@return (array)|(string)
	*/
	private static function __unique_id($callback)
	{
		if(is_string($callback)){
			return $callback;
		}

		if(is_object($callback)){
			$callback = array($callback,'');
		}
		else{
			$callback = (array)$callback;
		}

		// Treat object.
		if(is_object($callback[0])){
			if(function_exists('spl_object_hash')){
				// Return hash id for given object
				return spl_object_hash($callback[0]) . $callback[1];
			}
			// Returns the name of the class of an object.
			return get_class($callback[0]) . $callback[1];
		}

		// Treat static method.
		if(is_string($callback[0])){
			return $callback[0] . '::' . $callback[1];
		}
		return md5($callback);

	}// Method


}// Class
endif;
// new _beans_action();
_beans_action::__get_instance();
