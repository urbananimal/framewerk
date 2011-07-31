<?php
/**
 * An object representation for expected data as defined in the current action.
 * 
 * @author John Smith
 * @version 1.0.1
 *
 */
class Framewerk_InputData
{	
	private $value;
	private $regex;
	private $min_length;
	private $max_length;
	private $is_array;
	private $optional;
	private $error;
	private $number_range;

	private $valid = false; // default to false

	/**
	 * Configures this input data object
	 * @param $data_definition array
	 * @return void
	 */
	public function __construct($input_definition, $value)
	{
		// Assign the object's value
		$this->value = $value;

		// If no value was sent, but a default has been defined
		// @gotcha - Should a defualt value be NULL, isset() would return false. Instead use array_key_exists().
		if(!$this->value && array_key_exists('default', $input_definition))
		{
			$this->value = $input_definition['default'];
			$this->setStatus(true);
			return;
		}

		// Set the regex
		if(isset($input_definition['regex'])) $this->regex = $input_definition['regex'];
		
		// Set the min length
		if(isset($input_definition['min_length'])) $this->min_length = $input_definition['min_length'];
		
		// Set the max length
		if(isset($input_definition['max_length'])) $this->max_length = $input_definition['max_length'];
		
		// Set whether we expect an array of data or not
		if(isset($input_definition['is_array'])) $this->is_array = $input_definition['is_array'];
	
		// Is this optional
		if(isset($input_definition['optional'])) $this->optional = $input_definition['optional'];
		
		// Set the default value if no value is present
		if(isset($input_definition['default_value']) && !$this->value) $this->value = $input_definition['default_value'];
		
		// Set the default error message
		if(isset($input_definition['error'])) $this->error = $input_definition['error'];
		
		// Set the min value
		if(isset($input_definition['number_range'])) $this->number_range = $input_definition['number_range'];

		// Validate the object
		$object_valid = $this->filterData($value);

		// Set the status
		$this->setStatus($object_valid);
	}

	/**
	 * Recursively filter the value(s) for this InputData object
	 * Checks: 
	 * Whether data $value is an array and we expect an array
	 * min_value
	 * max_value
	 * regex
	 * optional
	 * 
	 * @param $value
	 * @return boolean
	 */
	private function filterData($value)
	{
		if(is_array($value))
		{
			// Make sure we are actually expecting an array
			if(!$this->is_array) return false;
		
			// Filter each value
			foreach($value as $val)
			{
				if(!$this->filterData($val)) return false; // There was some data that did not match the filter
			}
			// All data was valid
			return true;
		}

		// Actually filter

		// If this is optional and we received an empty string, take precedence over other checks.
		if($this->optional && $value == '') return true;

		// Check max length
		if($this->max_length && strlen($value) > $this->max_length) return false;

		// Check min length
		if($this->min_length && strlen($value) < $this->min_length) return false;

		// Check regex
		if($this->regex && !preg_match($this->regex, $value)) return false;

		// Make sure the number falls within the correct range and precision
		if($this->number_range)
		{
			if(!is_numeric($value)) return false;
			
			$dp = strpos($value, '.');
			
			// Number contains decimal
			if($dp !== false)
			{
				// Decimal precision not allowed
				if($this->number_range[2] === 0) return false;

				// Too precise
				if(strlen(substr($value, $dp + 1)) > $this->number_range[2]) return false;
			}

			// Within the range
			if($value < $this->number_range[0] || $value > $this->number_range[1]) return false;
		}

		// It passed all checks, data is valid
		return true;		
	}
	
	public function isValid()
	{
		return $this->valid;
	}
	
	public function setStatus($bool, $hide_default_message = false)
	{
		$this->valid = $bool;
		
		if($hide_default_message)
		{
			$this->error = null;
		}
	}
		
	public function getFilter()
	{
		return $this->filter;
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	public function getError()
	{
		return $this->error;
	}
	
	public function setError($error_msg)
	{
		$this->error = $error_msg;
		$this->valid = false;
	}

}
?>