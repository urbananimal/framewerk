<?php 
abstract class Framewerk_Routers_Router
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