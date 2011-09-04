<?php 
abstract class framewerk_routers_Router
{
	protected $controller;
	protected $action;

	public function getAction()
	{
		return $this->action;
	}

	public function getController()
	{
		return $this->controller;
	}

	abstract public function getRequestData($source);
	
	public function getRequestId(){}
}
?>