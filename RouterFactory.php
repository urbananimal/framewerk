<?php 
class Framewerk_RouterFactory
{
	public static function getRouter()
	{
		switch( isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null )
		{
			case 'application/json-rpc':
				$router = 'Framewerk_Routers_RouterJSONRPC';
				break;
				
			default:
				$router = 'Framewerk_Routers_RouterHTTP';
		}

		return new $router;
	}
}
?>