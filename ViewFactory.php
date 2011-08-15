<?php
/**
 * Returns the correct type of View object debending on the request environment
 * 
 * @author John Smith
 *
 */
class Framewerk_ViewFactory
{
	public static function getView()
	{
		if( isset($_SERVER['CONTENT_TYPE']) && strstr($_SERVER['CONTENT_TYPE'], 'application/json-rpc') !== false)
		{
			$view = 'Framewerk_Views_ViewJSONRPC';
		}
		else
		{
			$view = 'Framewerk_Views_ViewHTML';
		}

		return new $view;
	}
}