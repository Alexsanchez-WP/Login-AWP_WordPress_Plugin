<?php

/**
 * Mock WordPress hooks system for testing
 */

// Storage for registered actions and filters
$GLOBALS['wp_actions'] = [];
$GLOBALS['wp_filters'] = [];

// Storage for executed actions
$GLOBALS['wp_actions_executed'] = [];

/**
 * Adds an action hook.
 *
 * @param string   $hook_name       The name of the action.
 * @param callable $callback        The callback to be executed.
 * @param int      $priority        Priority of the action.
 * @param int      $accepted_args   Number of arguments the callback accepts.
 * @return true                     Always returns true.
 */
if (!function_exists('add_action')) {
    function add_action($hook_name, $callback, $priority = 10, $accepted_args = 1) {
        return add_filter($hook_name, $callback, $priority, $accepted_args);
    }
}

/**
 * Adds a filter hook.
 *
 * @param string   $hook_name       The name of the filter.
 * @param callable $callback        The callback to be executed.
 * @param int      $priority        Priority of the filter.
 * @param int      $accepted_args   Number of arguments the callback accepts.
 * @return true                     Always returns true.
 */
if (!function_exists('add_filter')) {
    function add_filter($hook_name, $callback, $priority = 10, $accepted_args = 1) {
        $idx = _wp_filter_build_unique_id($hook_name, $callback, $priority);
        
        $GLOBALS['wp_filters'][$hook_name][$priority][$idx] = [
            'function'      => $callback,
            'accepted_args' => $accepted_args
        ];
        
        return true;
    }
}

/**
 * Calls the callback functions that have been added to an action hook.
 *
 * @param string $hook_name The name of the action.
 * @param mixed  ...$args   Additional arguments to pass to the callback functions.
 * @return void
 */
if (!function_exists('do_action')) {
    function do_action($hook_name, ...$args) {
        // Record that this action was executed
        if (!isset($GLOBALS['wp_actions_executed'][$hook_name])) {
            $GLOBALS['wp_actions_executed'][$hook_name] = 0;
        }
        $GLOBALS['wp_actions_executed'][$hook_name]++;
        
        // If no callbacks registered, return
        if (!isset($GLOBALS['wp_filters'][$hook_name])) {
            return;
        }
        
        // Call all registered callbacks
        foreach ($GLOBALS['wp_filters'][$hook_name] as $priority => $callbacks) {
            foreach ($callbacks as $cb) {
                $function = $cb['function'];
                $accepted_args = $cb['accepted_args'];
                
                if (is_array($function)) {
                    // Object method call
                    $obj = $function[0];
                    $method = $function[1];
                    $obj->$method(...array_slice($args, 0, $accepted_args));
                } elseif (is_callable($function)) {
                    // Function call
                    $function(...array_slice($args, 0, $accepted_args));
                }
            }
        }
    }
}

/**
 * Calls the callback functions that have been added to a filter hook.
 *
 * @param string $hook_name The name of the filter.
 * @param mixed  $value     The value to filter.
 * @param mixed  ...$args   Additional arguments to pass to the callback functions.
 * @return mixed The filtered value.
 */
if (!function_exists('apply_filters')) {
    function apply_filters($hook_name, $value, ...$args) {
        // If no callbacks registered, return the value
        if (!isset($GLOBALS['wp_filters'][$hook_name])) {
            return $value;
        }
        
        // Process all registered callbacks
        foreach ($GLOBALS['wp_filters'][$hook_name] as $priority => $callbacks) {
            foreach ($callbacks as $cb) {
                $function = $cb['function'];
                $accepted_args = $cb['accepted_args'];
                
                // Prepare args - first arg is always the value
                $all_args = array_merge([$value], $args);
                $call_args = array_slice($all_args, 0, $accepted_args);
                
                if (is_array($function)) {
                    // Object method call
                    $obj = $function[0];
                    $method = $function[1];
                    $value = $obj->$method(...$call_args);
                } elseif (is_callable($function)) {
                    // Function call
                    $value = $function(...$call_args);
                }
            }
        }
        
        return $value;
    }
}

/**
 * Build a unique ID for a hook based on the callback, priority, and number of accepted args.
 *
 * @param string   $hook_name     Hook name.
 * @param callable $callback      Callback function.
 * @param int      $priority      Priority.
 * @return string                 Unique ID.
 */
if (!function_exists('_wp_filter_build_unique_id')) {
    function _wp_filter_build_unique_id($hook_name, $callback, $priority) {
        if (is_string($callback)) {
            return $callback;
        }
        
        if (is_object($callback)) {
            return spl_object_hash($callback) . $priority;
        }
        
        if (is_array($callback)) {
            if (is_object($callback[0])) {
                return spl_object_hash($callback[0]) . $callback[1] . $priority;
            } else {
                return $callback[0] . $callback[1] . $priority;
            }
        }
        
        return md5(serialize($callback)) . $priority;
    }
}

/**
 * Check if an action has been executed
 *
 * @param string $hook_name The action name
 * @return bool|int False if not executed, execution count if executed
 */
if (!function_exists('did_action')) {
    function did_action($hook_name) {
        return $GLOBALS['wp_actions_executed'][$hook_name] ?? false;
    }
}
