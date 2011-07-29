<?php
/**
 * Facilliates communication and logic between models and views.
 * 
 * @author John Smith
 * @version 1.0.1
 */
abstract class Framewerk_Controller
{	
	public $view; // Instance of View class.
	public $request; // Instance of Request class

	public $default_action; // If no action is included in the request, this action will be invoked.

	public function __construct()
	{
		$this->view = Framewerk_ViewFactory::getView();
	}

	/**
	 * Convenience method for header('Location'.blah blah uri);
	 * Sends a redirect to the user's browser
	 * 
	 * @param string $location
	 */
	protected function redirect($location)
	{
		if($this->isAjaxRequest())
		{
			$this->view->setRedirect($location);
			$this->view->render();
			exit;
		}
		else
		{
			header('Location: '.$location);
		}

		exit;
	}

	/**
	 * Allow returns true when HTTP_X_REQUESTED_WITH is set and == 'XMLHttpRequest'
	 * @return boolean
	 */
	protected function isAjaxRequest()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER ['HTTP_X_REQUESTED_WITH']  == 'XMLHttpRequest';
	}
}
?>