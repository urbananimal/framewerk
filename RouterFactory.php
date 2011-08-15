<?php 
class Framewerk_RouterFactory
{
	public static function getRouter()
	{
		if( isset($_SERVER['CONTENT_TYPE']) && strstr($_SERVER['CONTENT_TYPE'], 'application/json-rpc') !== false)
		{
			$router = 'Framewerk_Routers_RouterJSONRPC';
		}
		else
		{
			$router = 'Framewerk_Routers_RouterHTTP';
		}

		return new $router;
	}
}
?>