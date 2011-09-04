<?php
/**
 * Facilliates communication and logic between models and views.
 * 
 * @author John Smith
 * @version 1.0.1
 */
abstract class framewerk_Controller
{	
	public $view; // Instance of View class.
	public $request; // Instance of Request class

	public $default_action; // If no action is included in the request, this action will be invoked.

	public function __construct()
	{
		$this->view = framewerk_ViewFactory::getView();
	}
}
?>