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
		switch( $_SERVER['CONTENT_TYPE'] )
		{
			case 'application/json-rpc':
				$view = 'Framewerk_View_ViewJSON-RPC';
				break;
				
			default:
				$view = 'Framewerk_View_ViewHtml';
		}

		return new $view;
	}
}