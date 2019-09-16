<?php 
namespace Model;

class User extends Model
{	
	function __construct()
	{
		parent::__construct();
	}
	
	public function create($login, $password)
	{
		if($login && $password) {
			$hash = password_hash($password, PASSWORD_DEFAULT);
			$this->db->query("INSERT INTO user (login, password, status) VALUES ('" . $this->db->escape($login) . "', '" . $this->db->escape($hash) . "', 1)");
			$user_id = $this->db->getLastId();
		} else {
			return false;
		}
		$session_id = session_create_id();
		$_SESSION['session_id'] = $session_id;
		$_SESSION['logged'] = true;
		$_SESSION['user_id'] = $user_id;
		return $user_id;
		
	}
	
	public function get($login)
	{
		if($login) {
			$query = $this->db->query("SELECT * FROM user WHERE login = '" . $this->db->escape($login) . "'");
			if($query->num_rows) {
				return $query->row;
			}			
		}
	}

	public function login($login, $password)
	{
		$user = $this->get($login);
		if($user) {
			if(password_verify($password, $user['password'])) {
				$session_id = session_create_id();
				$_SESSION['session_id'] = $session_id;
				$_SESSION['logged'] = true;
				$_SESSION['user_id'] = $user['user_id'];
				return $user['user_id'];
			}
		}
	}

	public function isLogged() {
		return !empty($_SESSION['logged']);
	}
	public function getUserId() {
		if(!empty($_SESSION['user_id'])) {
			return $_SESSION['user_id'];
		}
	}
}