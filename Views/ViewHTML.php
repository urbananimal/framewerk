<?php
class Framewerk_Views_ViewHTML extends Framewerk_Views_View
{
	// An array of CSS and JS filenames to be include in the <head> of the HTML template.
	private $head_includes = array();
	// The template to be rendered. Default to 'index'.
	protected $template_name = 'index';
	// The name of the layout
	private $layout_name;
	// The HTML <title> property value
	public $page_title;

	private $html; // An instance of HelperHtml class.

	// An array of ads to be displayed by Ad Manger
	private $ad_manager_ads = array();

	public function __construct()
	{
		$this->html = new Framewerk_Views_Helpers_HelperHTML($this);
	}

	/**
	 * (non-PHPdoc)
	 * @see Framewerk_View#render()
	 * 
	 * @param $force_template - ignore the layout and only render the template. Useful when used from within templates
	 */
	public function render($force_template = false)
	{
		// Load the layout if there is one and it's not overridden by $force_template
		if($this->layout_name && !$force_template)
		{
			// If we have a layout, load that
			include Config::getLayoutPath().'/'.$this->layout_name.'/main.tpl.php';
			return;
		}

		// Else, directly load the template
		include Config::getTemplatePath().'/'.$this->controller.'/'.$this->template_name.'.tpl.php';
	}

	public function setLayout($layout_name)
	{
		$this->layout_name = $layout_name;
	}

	/**
	 * To add CSS or JavaScript <head> includes to an HTML document. Can be called by controllers, or from with Templates.
	 * 
	 * @param $filenames array
	 * @return void
	 */
	public function setHeadInclude(array $filenames)
	{
		$this->head_includes = array_merge($this->head_includes, $filenames);
	}
	
	/**
	 * Overwrites parent action. Allows a template to be set based up the current action name.
	 * 
	 * @see Framewerk_View#setAction($action)
	 */
	public function setAction($action)
	{
		parent::setAction($action);
		$this->setTemplate($action);
	}

	private function renderHeadIncludes()
	{
		$head_string = '';

		foreach($this->head_includes as $filename)
		{
			if(($file_ext = substr($filename, -3))  == 'css')
			{
				$relative_path = Config::getCssDirectory() . '/' . $filename;
				$head_string .= '<link type="text/css" rel="stylesheet" href="/'. $relative_path . '?'. filemtime($relative_path) . '"/>';
			}
			else if($file_ext == '.js')
			{
				$relative_path = Config::getJsDirectory() . '/' . $filename;
				$head_string .= '<script type="text/javascript" src="/'.$relative_path . '?' . filemtime($relative_path) . '"></script>';
			}
		}

		echo $head_string;
	}

	protected function renderElement($element_name)
	{
		if(!isset($this->elements[$element_name])) return;
		
		include Config::getElementPath().'/' . $this->elements[$element_name] .'.tpl.php';
	}

	protected function renderNotices($notice_type)
	{
		include Config::getLayoutPath().'/'.$this->layout_name.'/' . ($notice_type == Framewerk_Notice::TYPE_ERROR ? 'noticeError' : 'noticeSuccess') . '.tpl.php';
	}

	/**
	 * This method allows us to add to the adverts that will be rendered by Google Ad Manager.
	 * 
	 * @param $ads array
	 * @return void
	 */
	public function setAds(array $ads)
	{
		$this->ad_manager_ads = array_merge($this->ad_manager_ads, $ads);
	}
	
	private function getAds()
	{
		return $this->ad_manager_ads;
	}
	
	public function redirect($where)
	{
		header('Location: '. $where);
		exit;
	}
}
?>