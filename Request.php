<?php
/**
 * Stores an in instance of InputData for each piece of $_REQUEST data, that has been defined for the current action.
 * Filters each piece of $_REQUEST data with the rules specified in the controller for the current action.
 * Invalidates / Validates the InputData object according to the pattern defined in the current action.
 * 
 * @author John Smith
 * @version 0.0.2
 */
class framewerk_Request
{
	const SOURCE_POST = 0;
	const SOURCE_GET = 1;
	const SOURCE_REQUEST = 2;
	const SOURCE_REQUEST_BODY_JSON = 3;

	public $input_data_objects = array();

	public function __construct(array $action_definition, $source_data)
	{
		// Loop over each 'expected field' and create an InputData object for each
		if(!isset($action_definition['expected_fields'])) throw new Exception('Bad action definition.');

		foreach($action_definition['expected_fields'] AS $param_name => $input_definition)
		{
			//Get the submitted value for this field
			$input_data_value = isset($source_data[$param_name]) ? $source_data[$param_name] : (isset($input_definition['alias']) && isset($source_data[$input_definition['alias']]) ? $source_data[$input_definition['alias']] : null);
			
			// Add the InpurData object to the request's array of input data objects
			$this->input_data_objects[(isset($input_definition['alias']) ? $input_definition['alias'] : $param_name)] = new framewerk_InputData($input_definition, $input_data_value);
		}		
	}

	/**
	 * If all input_data objects in the request are valid, the request as a whole will be valid.
	 * 
	 * @return bool
	 */
	public function isValid()
	{
		foreach($this->input_data_objects as $input_object)
		{
			if(!$input_object->isValid()) return false;
		}
		
		return true;
	}
	
	/**
	 * Overloading. Allows easy access to input_data objects from within a controller
	 * 
	 * @param string $input_data_name
	 * @return instanceof input_data
	 */
	public function __get($input_data_name)
	{
		return $this->input_data_objects[$input_data_name];
	}
	
	public function __isset($input_data_name)
	{
		return isset($this->input_data_objects[$input_data_name]);
	}
	
	/**
	 * Returns an array of all request data that is invalid.
	 * 
	 * @return array
	 */
	public function getInvalidObjects()
	{
		$invalid_objects = array();
		
		foreach($this->input_data_objects as $name => $input_data_object)
		{
			if(!$input_data_object->isValid())
			{
				$invalid_objects[$name] = $input_data_object;
			}
		}

		return $invalid_objects;
	}
	
	/**
	 * Returns an array of valus from the Request object.
	 * 
	 * @param $bool_valid - return valid or invalid values
	 * @return array
	 */
	public function extractValues($bool_valid = true)
	{		
		$values = array();
		
		foreach($this->input_data_objects AS $key => $input_data_object)
		{
			if($input_data_object->isValid() == $bool_valid)
			{
				$values[$key] = $input_data_object->getValue();
			}
		}

		return $values;
	}
	
	
	public function extractNotices($bool_valid = true)
	{
		$values = array();
		foreach($this->input_data_objects AS $key => $input_data_object)
		{
			if($input_data_object->isValid() == $bool_valid)
			{
				$values[$key] = $input_data_object->getError();
			}
		}
		return $values;
	}
}
?>