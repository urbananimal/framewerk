<?php
class framewerk_views_ViewJSON extends framewerk_views_View
{
	private $redirect_location;

	public function render()
	{
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Fri, 20 Dec 1985 12:00:00 GMT');
		header('Content-type: application/json');

		echo json_encode($this->view_data);
		exit;
	}

	/**
	 * Allows a JavaScript redirect to the specified location
	 * @param unknown_type $location
	 */
	public function redirect($where){}

	protected function renderElement($element_name, $return_output = false){}

	/**
	 * 
	 * @see framewerk_View::renderNotices()
	 */
	protected function renderNotices($notice_type = null)
	{
		die('maybe a 404 or something');
		return framewerk_Notice::getNotices($notice_type);
	}
}
?>