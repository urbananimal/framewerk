<?php
class framewerk_routers_RouterHTTP extends framewerk_routers_Router
{
	public function __construct()
	{
		// If no controller param is set, use the default one.
		$this->controller = isset($_GET['controller']) ? $_GET['controller'] : Config::getDefaultController();

		if(isset($_GET['action']))
		{
			$this->action =  $_GET['action'];
		}
	}

	public function getRequestData($source)
	{
		switch($source)
		{
			case framewerk_Request::SOURCE_GET:

				$get_params = array();
				foreach(explode('/', $_GET['params']) as $key => $val)
				{
					if($val !== '') $get_params['param_'.($key+1)] = $val;
				}

				unset($_GET['controller'], $_GET['action'], $_GET['params']);

				// Add the re-written params to the GET global.
				$request_data = $_GET += $get_params;
				break;

			case framewerk_Request::SOURCE_REQUEST:
					$request_data = $_POST + $_GET;
					break;

			case framewerk_Request::SOURCE_POST:
			default:
					$request_data = $_POST;
					break;
		}

		return $request_data;
	}
}
?>