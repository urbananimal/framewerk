<?php 
class framewerk_RouterFactory
{
	const TYPE_REST = 1;

	public static function getRouter()
	{
		if( ($forced_router = Config::getRouter()) && $forced_router == self::TYPE_REST)
		{
			$router = 'framewerk_routers_RouterREST';
		}
		elseif( isset($_SERVER['CONTENT_TYPE']) && strstr($_SERVER['CONTENT_TYPE'], 'application/json-rpc') !== false)
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