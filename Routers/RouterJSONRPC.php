<?php
class Framewerk_Routers_RouterJSONRPC extends Framewerk_Routers_Router
{
	private $request_data;
	private $request_id;

	public function __construct()
	{
		$this->controller = isset($_GET['controller']) ? $_GET['controller'] : Config::getDefaultController();

		$request = json_decode(file_get_contents('php://input'));

		$this->action = isset($request->method) ? $request->method : null;

		$this->request_data = isset($request->params) ? $request->params : null;
		
		$this->request_id = $request->id;
	}

	public function getRequestData($source)
	{
		return (array) $this->request_data;
	}

	public function getRequestId()
	{
		return $this->request_id;
	}
}
?>