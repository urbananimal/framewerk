<?php 
class framewerk_RouterFactory
{
	public static function getRouter()
	{
		if( isset($_SERVER['CONTENT_TYPE']) && strstr($_SERVER['CONTENT_TYPE'], 'application/json-rpc') !== false)
		{
			$router = 'framewerk_routers_RouterJSONRPC';
		}
		else
		{
			$router = 'framewerk_routers_RouterHTTP';
		}

		return new $router;
	}
}
?>