<?php
/**
 * This simplified autoloader uses a specific naming convention to resolve file paths.
 * i.e. Controller_App.php resolves to [Application Directory]/Controller/App.php
 * 
 * All paths are relative to the aplications main directory, except for Core files.
 * Core files are loaded relative to the location of this file (Framewerk's core directory)
 * i.e. Framewerk_Database resolves to [Framewerk Core]/Database.php
 * 
 * @author John Smith
 * @version 1.1.0
 */
function __autoload($class)
{
	// Becuase file / class naming conventions are awesome, this shit is easy.
	$file_path = (substr($class, 0, 9) == 'Framewerk' ? substr(dirname(__FILE__), 0, -9) : Config::getApplicationRoot() . '/') . str_replace('_', '/', $class).'.php';

	//echo 'Attempting to load: ' . $file_path . '<br/>';

	if(!file_exists($file_path))
	{
		// File does not exist, redirect
		header("HTTP/1.0 404 Not Found");
		return;		
	}

	//echo 'including ' . realpath($file_path).'<br/>';
	include $file_path;
}
?>