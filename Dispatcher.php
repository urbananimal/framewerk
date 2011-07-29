<?php
if(Config::getEnvironment() < Framewerk_Config::ENV_LIVE) require_once 'Debug.php';

/**
 * Dispatcher Class.
 * Handles all http requests. Instantiates an appropriate controller class for the current request.
 * Sets up the Controllers View with the correct paramters for the requested action
 * Attempts to call the requested action, else calls the controller's default action
 * 
 * @author John Smith
 * @version 1.1.3
 */
class Framewerk_Dispatcher
{
	public function __construct()
	{
		// If no controller param is set, use the default one.
		$controller_name = isset($_GET['controller']) ? $_GET['controller'] : Config::getDefaultController();

		// Don't care whether it exists here, let the Autoloader figure that out.
		$class = 'Controller_'.$controller_name;
		// Instantiate the controller
		$controller = new $class;

		// If an action has been passed to route(), attempt to call that.
		// Else, try and call the action sent in request string. If that does not exist, try and get the controllers default action
		$current_action =  isset($_GET['action']) && is_callable(array($controller, $_GET['action'])) ? $_GET['action'] : $controller->default_action;
		
		// Set some view defaults. Templates are mapped to ControllerName/actionName.tpl.php
		$controller->view->setController($controller_name);
		$controller->view->setAction($current_action);
		
		// Does this action have a definition in the controller?
		$action_definition_name = $current_action.'_definition';

		if(isset($controller->$action_definition_name))
		{
			$action_definition = $controller->$action_definition_name;

			$get_params = array();
			
			// Prepare the GET params
			if(isset($_GET['params']))
			{
				foreach(explode('/', $_GET['params']) as $key => $val)
				{
					if($val !== '') $get_params['param_'.($key+1)] = $val;
				}

				unset($_GET['controller'], $_GET['action'], $_GET['params']);
				
				// Add the re-written params to the GET global.
				$_GET += $get_params;
			}

			// Get the data source, as defined in the controller
			$defined_data_source = isset($action_definition['data_source']) ? $action_definition['data_source'] : null;

			switch($defined_data_source)
			{
				case Framewerk_Request::SOURCE_GET:
						$data_source = $_GET;
						break;
				case Framewerk_Request::SOURCE_REQUEST:
						$data_source = $_POST + $_GET;
						break;
				case Framewerk_Request::SOURCE_POST: 
				default:
						$data_source = $_POST;
			}

			// If data_source is empty, do not create the request object
			// This allows actions to actually check whether an action should be performed, or just view.
			if(!empty($data_source) || $_SERVER['REQUEST_METHOD'] == 'POST') // Source not empty, or request was POST
			{
				// Create the request object for this action
				$controller->request = new Framewerk_Request($action_definition, $data_source);
			}
		}

		// Pass input data back to the view - before action, so action can overwrite if needed
		if($controller->request)
		{
			$controller->view->setData($controller->request->input_data_objects);
		}

		// Call the action
		$controller->$current_action();
		
		// Check for invalid / invalidated data, create notices
		if($controller->request)
		{
			if(!$controller->request->isValid())
			{
				foreach($controller->request->getInvalidObjects() as $input_data)
				{
					// If this field has a message					
					if( ($message = $input_data->getError()) )
					{
						$controller->view->setNotice($message);
					}
				}
			}
		}

		// Render the view.
		$controller->view->render();
	}
}
?>