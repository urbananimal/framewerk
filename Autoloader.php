<?php
/**
 * This simplified autoloader uses a specific naming convention to resolve file paths.
 * i.e. controller_App.php resolves to [Application Directory]/controller/App.php
 * 
 * All paths are relative to the aplication's main directory, except for core files.
 * Core files are loaded relative to the location of this file (framewerk's core directory)
 * i.e. framewerk_Database resolves to [framewerk core]/Database.php
 * 
 * @author John Smith
 * @version 1.1.0
 */
function __autoload($class)
{
	// Becuase file / class naming conventions are awesome, this shit is easy.
	$file_path = (substr($class, 0, 9) == 'framewerk' ? substr(dirname(__FILE__), 0, -9) : Config::getApplicationRoot() . '/') . str_replace('_', '/', $class).'.php';

	//echo 'Attempting to load: ' . $file_path . '<br/>';

	if(!file_exists($file_path))
	{
		// File does not exist, redirect
		header("HTTP/1.0 404 Not Found");
		exit;		
	}

	//echo 'including ' . realpath($file_path).'<br/>';
	include $file_path;
}
?>