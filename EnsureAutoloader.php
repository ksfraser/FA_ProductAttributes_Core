<?php

class EnsureAutoloader
{
	function __construct()
	{
	}
	static function ensureAutoloaderLoaded() 
	{
        	// Use __DIR__ to find the autoloader path relative to this hooks.php file
        	// hooks.php is at: modules/FA_ProductAttributes/hooks.php
        	// autoloader is at: modules/FA_ProductAttributes/vendor/autoload.php
        	$autoloader = __DIR__ . '/vendor/autoload.php';

		if (file_exists($autoloader)) 
		{
			require_once $autoloader;
			// Debug: Check if autoloader was loaded	
			if (function_exists('spl_autoload_functions')) 
			{
				$functions = spl_autoload_functions();
				if (is_array($functions) && count($functions) > 0) 
				{
					error_log("FA_ProductAttributes: Autoloader loaded successfully from: " . $autoloader);
				} else 
				{
					error_log("FA_ProductAttributes: Autoloader loaded but no functions registered from: " . $autoloader);
				}
			}
		} else 
		{
			error_log("FA_ProductAttributes: Autoloader not found at: " . $autoloader);
		}
	}
}