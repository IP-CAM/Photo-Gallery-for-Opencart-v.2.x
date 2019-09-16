<?php 
namespace Model;

class Model
{
	public $data = [];
	public $db;
	function __construct()
	{
		$this->db = new \DB\MySQLi('localhost', DB_LOGIN, DB_PASS, DB_DATABASE, $port = '3306');
	}
}