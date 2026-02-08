<?php

// Global Hook Manager for FrontAccounting Module Extensions
// This file should be included early in FA's bootstrap process

// Load fa-hooks autoloader if available
$faHooksAutoload = __DIR__ . '/0fa-hooks/vendor/autoload.php';
if (file_exists($faHooksAutoload)) {
    require_once $faHooksAutoload;
} else {
    // Fallback to original name for development
    $faHooksAutoload = __DIR__ . '/fa-hooks/vendor/autoload.php';
    if (file_exists($faHooksAutoload)) {
        require_once $faHooksAutoload;
    }
}

// Load FA function mocks early to prevent undefined function errors
// Only load in testing/development environments
if (defined('FA_TESTING') || getenv('FA_TESTING') || isset($_SERVER['FA_TESTING'])) {
    $famockPath = __DIR__ . '/composer-lib/vendor/ksfraser/famock/php/FAMock.php';
    if (file_exists($famockPath)) {
        require_once $famockPath;
    }
}

// Initialize global hook manager if not already done
if (!isset($GLOBALS['fa_hooks'])) {
    // Try to load via composer autoloader first
    if (class_exists('\Ksfraser\FA_Hooks\HookManager')) {
        $GLOBALS['fa_hooks'] = new \Ksfraser\FA_Hooks\HookManager();
    } else {
        // Fallback: direct require (for development)
        $hookManagerPath = __DIR__ . '/fa-hooks/src/Ksfraser/FA_Hooks/HookManager.php';
        if (file_exists($hookManagerPath)) {
            require_once $hookManagerPath;
            $GLOBALS['fa_hooks'] = new \Ksfraser\FA_Hooks\HookManager();
        } else {
            // Last resort: old location
            $oldPath = __DIR__ . '/composer-lib/src/Ksfraser/FA_ProductAttributes/Hooks/HookManager.php';
            if (file_exists($oldPath)) {
                require_once $oldPath;
                $GLOBALS['fa_hooks'] = new \Ksfraser\FA_ProductAttributes\Hooks\HookManager();
            }
        }
    }
}

/**
 * Get the global hook manager instance
 *
 * @return \Ksfraser\FA_Hooks\HookManager|\Ksfraser\FA_ProductAttributes\Hooks\HookManager|null
 */
function fa_hooks() {
    return $GLOBALS['fa_hooks'] ?? null;
}

/**
 * Apply filters to a value using the global hook manager
 *
 * @param string $filter_name The name of the filter
 * @param mixed $value The value to filter
 * @return mixed The filtered value
 */
function apply_filters($filter_name, $value) {
    $hooks = fa_hooks();
    if ($hooks && method_exists($hooks, 'apply_filters')) {
        return $hooks->apply_filters($filter_name, $value);
    }
    return $value;
}

/**
 * Add a filter callback to the global hook manager
 *
 * @param string $filter_name The name of the filter
 * @param callable $callback The callback function
 * @param int $priority The priority (lower numbers run first)
 * @return bool True on success
 */
function add_filter($filter_name, $callback, $priority = 10) {
    $hooks = fa_hooks();
    if ($hooks && method_exists($hooks, 'add_filter')) {
        return $hooks->add_filter($filter_name, $callback, $priority);
    }
    return false;
}