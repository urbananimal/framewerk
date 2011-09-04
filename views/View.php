<?php 
abstract class framewerk_views_View
{
	protected $template_name;

	protected $view_data = array(); // All data available to views

	protected $elements = array(); // Elements can be set within a controller then rendered from the template

	protected $notices = array(); // Holds any notices that may have been set.

	protected $controller; // The current controller.
	protected $action; // The current action being performed.
	protected $request_id;
	protected $title;

	public function setData(array $view_data)
	{
		// Most recently passed data takes precedence
		$this->view_data = $view_data + $this->view_data;
	}

	public function setTemplate($template_name)
	{
		$this->template_name = $template_name;
	}

	public function setTitle($title, $append = false)
	{
		$this->title = ($append ? $this->title : '') . $title;
	}
	
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Adds a notice to be rendered by the View's renderNotices() method.
	 * 
	 * @param $notice string
	 * @return void
	 */
	public function setNotice($notice, $notice_type = framewerk_Notice::TYPE_ERROR)
	{
		framewerk_Notice::setNotice($notice, $notice_type);
	}

	/**
	 * 
	 * @param $element array - a key => value pair of element_name => element template filename
	 * @return void
	 */
	public function setElement(array $elements)
	{
		// Most recently passed element takes precedence
		$this->elements = $elements + $this->elements;
	}
	
	/**
	 * Removes an element from the array so we can opt out for certain actions i.e. pages/error404
	 * 
	 * @param $element string - an element name
	 * @return unknown_type
	 */
	public function unsetElement($element)
	{
		if(isset($this->elements[$element]))
		{
			unset($this->elements[$element]);
		}
	}

	/**
	 * Object overloading to make accessing view data easier from witin templates.
	 * Checks whether the data is an instance of InputData, if so, returns only the value.
	 * 
	 * @param $var_name
	 * @return mixed
	 */
	public function __get($var_name)
	{
		// @todo - InputData objects now have __toString() method. Might be able to use that, rather than the below.
		return ($var = $this->view_data[$var_name]) instanceof framewerk_InputData ? $var->getValue() : $var;
	}

	/**
	 * Returns data from the view in it's original format, whether it's an object or not.
	 * Useful for helper classes.
	 * @param $var_name
	 * @return mixed
	 */
	public function getRawData($var_name)
	{
		return $this->view_data[$var_name];
	}

	public function __isset($var_name)
	{
		return isset($this->view_data[$var_name]);
	}
	
	public function setController($controller)
	{
		$this->controller = $controller;
	}
	
	public function setAction($action)
	{
		$this->action = $action;
	}

	public function setRequestId($request_id)
	{
		$this->request_id = $request_id;
	}

	// Outputs the finalised content of the view to the user.
	abstract public function render();

	// Renders additional elements 
	abstract protected function renderElement($element_name);
	
	// Render any notices set in the controller
	abstract protected function renderNotices($notice_type);
	
	abstract public function redirect($where);
}
?>