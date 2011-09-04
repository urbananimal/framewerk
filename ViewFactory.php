<?php
/**
 * Returns the correct type of View object debending on the request environment
 * 
 * @author John Smith
 *
 */
class framewerk_ViewFactory
{
	public static function getView()
	{
		if( isset($_SERVER['CONTENT_TYPE']) && strstr($_SERVER['CONTENT_TYPE'], 'application/json-rpc') !== false)
		{
			$view = 'framewerk_views_ViewJSONRPC';
		}
		else
		{
			$view = 'framewerk_views_ViewHTML';
		}

		return new $view;
	}
}