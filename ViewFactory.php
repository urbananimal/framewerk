<?php
/**
 * Returns the correct type of View object debending on the request environment
 * 
 * @author John Smith
 *
 */
class Framewerk_ViewFactory
{
	public static function getView()
	{
		// Is this an AJAX request?
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER ['HTTP_X_REQUESTED_WITH']  == 'XMLHttpRequest')
		{
			return new Framewerk_View_ViewAjax;
		}
		else
		{
			return new Framewerk_View_ViewHtml;
		}
		
	}
}
?>