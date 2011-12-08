<?php
class framewerk_routers_RouterREST extends framewerk_routers_RouterHTTP
{
	public function __construct()
	{
		parent::__construct();

		// Prepend the action with the verb. CRUD mapping to POST, GET, PUT, DELETE
		switch($_SERVER['REQUEST_METHOD'])
		{
			case 'POST':
				$verb = 'create';
				break;

			case 'PUT':
				$verb = 'update';
				break;

			case 'DELETE':
				$verb = 'delete';
				break;

			case 'GET':
			default:
				$verb = 'read';
				break;
		}

		$this->action = $verb . ucfirst($this->action);
	}

	public function getRequestData($source)
	{
		switch($source)
		{
			case framewerk_Request::SOURCE_REQUEST_BODY_JSON:
				return json_decode(file_get_contents('php://input'), true);
				break;

			default:
				return parent::getRequestData($source);
		}
	}
}
?>