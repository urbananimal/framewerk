<?php
/**
 * A helper class that should make building HTML forms quicker.
 * 
 * @author John Smith.
 *
 */
class Framewerk_Views_Helpers_HelperHTML
{
	private $view; // The view object. I hate myself.
	
	public function __construct( Framewerk_Views_View $view )
	{
		$this->view = $view;
	}
	
	public function inputElement($name_attribute, array $element_attributes = array())
	{
		// If a CSS class is already specified for the element, add 'text' as an additional CSS class.
		if(isset($element_attributes['class']))
		{
			$element_attributes['class'] .= ' text';
		}
		else
		{
			// Add 'text' as the only CSS class.
			$element_attributes['class'] = 'text';
		}

		// Set the element type, if it hasn't been specifically set to 'password'.
		if(!isset($element_attributes['type']))
		{
			$element_attributes['type'] = 'text';
		}

		// Special case for the 'readonly' attribute. Because it is gay.
		if(isset($element_attributes['readonly']) && !$element_attributes['readonly']) unset($element_attributes['readonly']);

		// try and get the field from the view
		if(isset($this->view->$name_attribute))
		{
			$request_data = $this->view->getRawData($name_attribute);

			if($request_data instanceof Framewerk_InputData)
			{
				$value_attribute = $request_data->getValue();

				// If the requset data was invalid, denote the input element as such
				if(!$request_data->isValid())
				{
					$element_attributes['class'] .= ' error';
				}
			}
			else // Normal variable passed
			{
				$value_attribute = $request_data;
			}
			
			unset($element_attributes['value']);
		}
		// Was the value set in the template? Use it, else overwritten. For example text etc.
		else if(isset($element_attributes['value']))
		{
			$value_attribute = $element_attributes['value'];
			$element_attributes['class'] .= ' eg';
			unset($element_attributes['value']);
		}
		else // else, the data does not exist
		{
			$value_attribute = '';	
		}

		// Output the input field. Hot.
		echo '<input name="'.$name_attribute.'" value="'.$value_attribute.'"'. $this->formatElementAttributesString($element_attributes) .'/>'."\n";
	}
	
	public function textAreaElement($name_attribute, array $element_attributes = array())
	{
		if(isset($element_attributes['class']))
		{
			$element_attributes['class'] .= ' textarea';
		}
		else
		{
			$element_attributes['class'] = 'textarea';
		}

		// try and get the field from the view
		if(!isset($this->view->$name_attribute))
		{
			$field_value = '';
		}

		// else, the data exists in the view
		else 
		{
			$request_data = $this->view->getRawData($name_attribute);

			if($request_data instanceof Framewerk_InputData)
			{
				$field_value = $request_data->getValue();
				// If the requset data was invalid, denote the input element as such
				if(!$request_data->isValid())
				{
					$element_attributes['class'] .= ' error';
				}
			}
			else // Normal variable passed
			{
				$field_value = $request_data;
			}
		}

		echo '<textarea name="'.$name_attribute.'"'.$this->formatElementAttributesString($element_attributes).'>'.$field_value.'</textarea>'."\n";
	}
	
	/**
	 * 
	 * @param $name_attribute
	 * @param $select_options_key - the key of the array passed to the view, to populate <option> fields.
	 * @param $element_attributes 
	 */
	public function selectElement($name_attribute, $select_options_key, array $element_attributes = array())
	{
		if(isset($element_attributes['class']))
		{
			$element_attributes['class'] .= ' select';
		}
		else
		{
			$element_attributes['class'] = 'select';
		}

		// See if a value has been submitted
		if(!isset($this->view->$name_attribute))
		{
			$value_attribute = null;

			// Not submitted, has a default been passed in the view?
			if(isset($element_attributes['value']))
			{
				$value_attribute = $element_attributes['value'];

				// Remove it from the $element_attributes, so it's not formatted as a normal attribute
				unset($element_attributes['value']);
			}
		}
		else // else, the data exists in the view
		{
			$request_data = $this->view->getRawData($name_attribute);

			if($request_data instanceof Framewerk_InputData)
			{
				$value_attribute = $request_data->getValue();

				// If the requset data was invalid, mark the input element as such
				if(!$request_data->isValid())
				{
					$element_attributes['class'] .= ' error';
				}
			}
			else // Normal variable passed from the controller
			{
				$value_attribute = $request_data;
			}
		}

		$output_string = '<select name="'.$name_attribute.'"'.$this->formatElementAttributesString($element_attributes).'>';

		// Now go through each option. If the we find a match, mark as selected.
		$options = $this->view->getRawData($select_options_key);

		foreach($options as $key => $val)
		{
			// Type cast - submitted values should always be a string
			$key = (string) $key;

			$output_string .= '<option value="'.$key.'" '.( ($value_attribute === $key) || ( is_array($value_attribute) && in_array($key, $value_attribute, true) ) ? 'selected="selected"' : '').'>'.$val.'</option>'."\n";
		}
	
		echo $output_string."</select>\n";
	}
	
	/**
	 * 
	 * @param $name_attribute
	 * @param $radio_options_key - the key for the radio options passed to the view
	 * @param $element_attributes
	 * @return void
	 */
	public function radioElements($name_attribute, $radio_options_key, array $element_attributes = array())
	{
		if(isset($element_attributes['class']))
		{
			$element_attributes['class'] .= ' radio';
		}
		else
		{
			$element_attributes['class'] = 'radio';
		}

		// try and get the radio options from the view
		$options = $this->view->getRawData($radio_options_key);
		
		if(!isset($this->view->$name_attribute))
		{
			$field_value = '';
		}
		
		// else, the data exists in the view
		else 
		{
			$request_data = $this->view->getRawData($name_attribute);
			if($request_data instanceof Framewerk_InputData)
			{
				$field_value = $request_data->getValue();
				// If the requset data was invalid, denote the input element as such
				if(!$request_data->isValid())
				{
					$element_attributes['class'] .= ' error';
				}
			}
			else // Normal variable passed
			{
				$field_value = $request_data;
			}
		}
		
		if(isset($element_attributes['value']))
		{
			$field_value = $element_attributes['value'];
		}

		$element_attributes_string = $this->formatElementAttributesString($element_attributes);
		
		foreach($options as $key => $val)
		{
			$key = (string) $key;
			echo '<label><input type="radio" name="'.$name_attribute.'" value="'.$key.'" '.(($field_value == $key) ? 'checked="checked"' : '').$element_attributes_string.'>'.$val.'</label>';
		}
	}

	public function checkboxElement($name_attribute, array $element_attributes = array())
	{
		if(isset($element_attributes['class']))
		{
			$element_attributes['class'] .= ' checkbox';
		}
		else
		{
			$element_attributes['class'] = 'checkbox';
		}
		
		# try and get the field from the view
		if(!isset($this->view->$name_attribute))
		{
			$field_value = '';
		}
		
		# else, the data exists in the view
		else 
		{
			$request_data = $this->view->getRawData($name_attribute);
			
			if($request_data instanceof Framewerk_InputData)
			{
				$field_value = $request_data->getValue();
				// If the requset data was invalid, denote the input element as such
				if(!$request_data->isValid())
				{
					$element_attributes['class'] .= ' error';
				}
			}
			else // Normal variable passed
			{
				$field_value = $request_data;
			}
			
			// Should the checkbox be checked?
			if($field_value)
			{
				$element_attributes['checked'] = 'checked';
			}
		}
		# Output the input field. Hot.
		echo '<input type="checkbox" name="'.$name_attribute.'"'.$this->formatElementAttributesString($element_attributes).'/>'."\n";
	}
	
	/**
	 * Formats an array of html element attributes into a string.
	 * e.g. array('class' => 'myclass2', id => 'cool_box')
	 * would return: class="myclass2" id="cool_box"
	 * 
	 * @param $element_attributes
	 * @return string
	 */
	private function formatElementAttributesString(array $element_attributes)
	{
		$attribute_string = ' '; // Has an initial space.
		
		foreach($element_attributes as $attribute_name => $attribute_value)
		{
			$attribute_string .= ' '.$attribute_name .'="'.$attribute_value.'"';
		}
		
		return $attribute_string;
	}
}
?>