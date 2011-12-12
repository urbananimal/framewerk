<?php
/**
 * Returns the correct type of View object debending on the request environment
 * 
 * @author John Smith
 *
 */
class framewerk_ViewFactory
{
	const TYPE_JSON = 1;

	public static function getView()
	{
		if( ($view = Config::getView()) && $view == self::TYPE_JSON )
		{
			$view = 'framewerk_views_ViewJSON';
		}
		elseif( isset($_SERVER['CONTENT_TYPE']) && strstr($_SERVER['CONTENT_TYPE'], 'application/json-rpc') !== false)
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