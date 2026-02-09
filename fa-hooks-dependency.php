<?php

//Check for fa-hooks having been installed.

class fa-hooks-dependency
{
	private $found; //bool
	function __construct()
	{
		$this->found = false;
		global $path_to_root;
		$altPaths = [
				$path_to_root . '/modules/0fa-hooks/hooks.php',
				$path_to_root . '/modules/fa-hooks/hooks.php',
        	            	$path_to_root . '/modules/00-fa-hooks/hooks.php',
        	            	$path_to_root . '/modules/aa-fa-hooks/hooks.php'
        	        ];
		foreach ($altPaths as $altPath) 
		{
        		if (file_exists($altPath)) 
			{
				$this->found = true;
        	                break;
			}
		}
 	}
	function isInstalled()
	{
		return $this->found;
	}
}