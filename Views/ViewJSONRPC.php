<?php
class Framewerk_Views_ViewJSONRPC extends Framewerk_Views_View
{
	private $redirect_location;

	private $html;

	public function __construct()
	{
		$this->html = new Framewerk_Views_Helpers_HelperHTML($this);
	}

	public function render()
	{
		// Render any elements we have
		$rendered_elements = array();

		foreach($this->elements as $element_name => $element_template)
		{
			$rendered_elements[$element_name] = $this->renderElement($element_name, true);
		}

		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Fri, 20 Dec 1985 12:00:00 GMT');
		header('Content-type: application/json-rpc');

		$response = array
		(
			'id' =>  $this->request_id,
			'jsonrpc' => '2.0',
			'result' => array
			(
				'view_data' => $this->view_data,
				'elements' => $rendered_elements,
				'template' => $this->template_name ? $this->captureTemplateOutput(Config::getTemplatePath().'/'.$this->controller.'/'.$this->template_name.'.tpl.php') : null,
				'redirect' => $this->redirect_location,
				'title' => $this->getTitle()
			)
		);

		echo json_encode($response);
		exit;
	}

	/**
	 * Allows a JavaScript redirect to the specified location
	 * @param unknown_type $location
	 */
	public function redirect($where)
	{
		$this->redirect_location = $where;
		$this->render();
	}

	/**
	 * When called from within a template, the output should be included.
	 * When called from within ViewJSONRPC itself, the output should be returned.
	 *
	 * @see Framewerk_Views_View::renderElement()
	 */
	protected function renderElement($element_name, $return_output = false)
	{
		if(!isset($this->elements[$element_name])) return;
		
		$path = Config::getElementPath().'/' . $this->elements[$element_name] . '.tpl.php';
		
		if($return_output)
		{
			return $this->captureTemplateOutput($path);
		}

		//else
		include $path;
	}

	/**
	 * Capture the output from a template so we can send it via JSON-RPC.
	 * 
	 * @param string $path_to_template
	 */
	private function captureTemplateOutput($path_to_template)
	{
		ob_start();
		include $path_to_template;
		return ob_get_clean();
	}

	/**
	 * 
	 * @see Framewerk_View::renderNotices()
	 */
	protected function renderNotices($notice_type)
	{
		return Framewerk_Notice::getNotices($notice_type);
	}
}
?>