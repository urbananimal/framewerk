<?php 
class Framewerk_Views_ViewJSONRPC extends Framewerk_Views_View
{
	private $redirect_location;
 
	public function render()
	{
		// Render any elements we have
		$rendered_elements = array();

		$path_to_elements = Config::getElementPath();

		foreach($this->elements as $element_name => $element_template)
		{
			$rendered_elements[$element_name] = $this->renderElement($path_to_elements . '/'. $element_template);
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
				//'template' => $template,
				'redirect' => $this->redirect_location
			)
		);

		echo json_encode($response);

		/*
		 * 
		 *echo json_encode(
			array
			(		
				'view_data' => $this->view_data,
				'notices' => array(
					'error' => $this->renderNotices(Framewerk_Notice::TYPE_ERROR), 
					'success' => $this->renderNotices(Framewerk_Notice::TYPE_SUCCESS)
				),
				'redirect' => $this->redirect_location,
				//'elements' => $rendered_elements,
				'template' => $this->template_name ? $this->captureTemplateOutput(Config::getTemplatePath().'/'.$this->controller.'/'.$this->template_name.'.tpl.php') : null
				
			)
		);
		*/

		exit;
	}

	/**
	 * Allows a JavaScript redirect to the specified location
	 * (When used with ajax.js
	 * @param unknown_type $location
	 */
	public function setRedirect($location)
	{
		$this->redirect_location = $location;
	}
	
	protected function renderElement($element_path)
	{
		 return $this->captureTemplateOutput($element_path);
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