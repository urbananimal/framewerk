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
		switch( isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null )
		{
			case 'application/json-rpc':
				$view = 'Framewerk_Views_ViewJSONRPC';
				break;
				
			default:
				$view = 'Framewerk_Views_ViewHTML';
		}

		return new $view;
	}
}