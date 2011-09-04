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

		// Has there been an error?
		if( ($errors = $this->renderNotices(Framewerk_Notice::TYPE_ERROR)) )
		{
			$invalid_fields = array();

			foreach($this->view_data as $name => $input_data_object)
			{
				if( !($input_data_object instanceof Framewerk_InputData) || $input_data_object->isValid() ) continue;
				$invalid_fields[] = $name;
			}

			$resp_type = array
			(
				'error' => array
				(
					'code' => 1,
					'message' => 'APPLICATION_ERROR',
					'data' => array
					(
						'notices' => $errors,
						'invalid_fields' => $invalid_fields
					)
				)
			);
		}
		else
		{
			$resp_type = array
			(
				'result' => array
				(
					'view_data' => $this->view_data,
					'elements' => $rendered_elements,
					'template' => $this->template_name ? $this->captureTemplateOutput(Config::getTemplatePath().'/'.$this->controller.'/'.$this->template_name.'.tpl.php') : null,
					'redirect' => $this->redirect_location,
					'title' => $this->getTitle(),
					'notices' => $this->renderNotices(Framewerk_Notice::TYPE_SUCCESS)
				)
			);
		}

		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Fri, 20 Dec 1985 12:00:00 GMT');
		header('Content-type: application/json-rpc');

		$response = array
		(
			'id' =>  $this->request_id,
			'jsonrpc' => '2.0',
		) + $resp_type;

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
	protected function renderNotices($notice_type = null)
	{
		return Framewerk_Notice::getNotices($notice_type);
	}
}
?>