<?php
class Framewerk_Routers_JSONRPC extends Framewerk_Routers_Router
{
	private $request_data;

	public function __construct()
	{
		$this->controller = isset($_GET['controller']) ? $_GET['controller'] : Config::getDefaultController();

		$request = json_decode($HTTP_RAW_POST_DATA);
		
		$this->action = $request->method;

		$this->request_data = $request->params;
	}

	public function getRequestData($source)
	{
		return $this->request_data;
	}
}
?>