<?php 
namespace Controller;

class Index extends Controller
{	
	function __construct()
	{
		parent::__construct();
	}
	public function index() {
		$user = new \Model\User();
		if($user->isLogged()) {
			header('Location: /gallery');
		} else {
			$this->render('index');
		}
	}
}