<?php
if(Config::getEnvironment() < framewerk_Config::ENV_LIVE) require_once 'Debug.php';


/**
 * Make all errors throw exceptions
 * 
 * @param unknown_type $errno
 * @param unknown_type $errstr
 * @param unknown_type $errfile
 * @param unknown_type $errline
 */
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");

/**
 * Dispatcher Class.
 * Handles all http requests. Instantiates an appropriate controller class for the current request.
 * Sets up the Controller's View with the correct paramters for the requested action
 * Attempts to call the requested action, else calls the controller's default action
 * 
 * @author John Smith
 * @version 1.1.3
 */
class framewerk_Dispatcher
{
	public function __construct()
	{
		// Get the router object
		$router = framewerk_RouterFactory::getRouter();

		// Instantiate the controller
		$controller_name = $router->getController();
		$class_name = 'controller_' . $controller_name;

		$controller = new $class_name;

		$action_name = $router->getAction();

		// Else, try and call the action sent in request string. If that does not exist, try and get the controllers default action
		$action =  is_callable(array($controller, $action_name)) ? $action_name : $controller->default_action;

		// Set some view defaults. Templates are mapped to ControllerName/actionName.tpl.php
		$controller->view->setController($controller_name);
		$controller->view->setAction($action);
		$controller->view->setRequestId($router->getRequestId());

		// Does this action have a definition in the controller?
		$action_definition_name = $action_name.'_definition';

		if(isset($controller->$action_definition_name))
		{
			$action_definition = $controller->$action_definition_name;

			$request_data = $router->getRequestData($action_definition['data_source']);

			// If data_source is empty, do not create the request object
			// This allows actions to actually check whether an action should be performed, or just view.
			if(!empty($request_data)) $controller->request = new framewerk_Request($action_definition, $request_data);
		}

		// Pass input data back to the view - before action, so action can overwrite if needed
		if($controller->request)
		{
			$controller->view->setData($controller->request->input_data_objects);
		}

		// Call the action
		$controller->$action();
		
		// Check for invalid / invalidated data, create notices
		if($controller->request)
		{
			if(!$controller->request->isValid())
			{
				foreach($controller->request->getInvalidObjects() as $field_name => $input_data_object)
				{
					// If this field has a message					
					if( ($message = $input_data_object->getError()) ) $controller->view->setNotice($message, framewerk_Notice::TYPE_ERROR, $field_name);
				}
			}
		}

		// Render the view.
		$controller->view->render();
	}
}
?>