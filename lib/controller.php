<?php 
namespace Controller;

class Controller
{
	protected $data = [];
	function __construct()
	{
		
	}
	protected function render($template)
	{		
		$file = DIR_APP . '/view/' . $template . '.tpl';	
		if (is_file($file)) {
			extract($this->data);

			ob_start();

			require($file);

			echo ob_get_clean();
			return;
		}

		trigger_error('Error: Could not load template ' . $file . '!');
		exit();
	}

	protected function toJson()
	{
		echo json_encode($this->data);
	}

	protected function load($class)
	{
		include_once(DIR_APP . $class . '.php');
	}

	
}