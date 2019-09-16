<?php 
namespace Controller;

class Account extends Controller
{	
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		
	}

	public function login()
	{	
		$errors = [];
		$user_id = false;
		if(!empty($_POST)) {			
			if(!empty($_POST['login'])) {
				$login = $_POST['login'];
			} else {
				$errors[] = 'login';
			}
			if(!empty($_POST['password'])) {
				$password = $_POST['password'];
			} else {
				$errors[] = 'password';
			}
			if(!$errors) {
				$model_user = new \Model\User();
				if(!$model_user->get($login)) {
					$errors[] = 'Not registered';
				} else {
					$user_id = $model_user->login($login, $password);
					if(!$user_id) {
						$errors[] = 'Wrong password';
					}
				}
			} 
		}
		if($errors || !$user_id) {
			$this->data['errors'] = $errors;
		} else {
			$this->data['redirect'] = '/gallery';
		}
		$this->toJson();
	}

	public function register()
	{
		$errors = [];
		$user_id = false;
		if(!empty($_POST)) {
			$errors = [];
			if(!empty($_POST['login'])) {
				$login = $_POST['login'];
			} else {
				$errors[] = 'login';
			}
			if(!empty($_POST['password'])) {
				$password = $_POST['password'];
			} else {
				$errors[] = 'password';
			}
			if(!$errors) {
				$user = new \Model\User();
				if($user->get($login)) {
					$errors[] = 'existing user';
				} else {
					$user_id = $user->create($login, $password);
				}				
			}
		}
		if($errors || !$user_id) {
			$this->data['errors'] = $errors;
			
		} else {
			$this->data['redirect'] = '/gallery';
		}
		$this->toJson();
	}

	public function logout()
	{
		session_destroy();
		header('Location: /');
	}
}