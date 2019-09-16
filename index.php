<?php 
session_start();

include_once('config.php');
include_once('lib/loader.php');
include_once('lib/controller.php');
include_once('lib/model.php');
include_once('lib/db.php');

use DB\MySQLi as db;

if(!empty($_GET['_route_'])) {
  $route = explode('/', $_GET['_route_']);
} else {
  $route = ['index'];
}
  
if(!empty($route[0])) {
  $class = $route[0];
} else {
  $class = 'index';
} 

if(!empty($route[1])) {
  $method = $route[1];
} else {
  $method = 'index';
}

if(!empty($route[2])) {
  $option = $route[2];
} else {
  $option = '';
}

include_once('controller/'.$class.'.php');
$path = "\Controller\\". $class;
$controller = new $path();

if(method_exists($controller,$method)) {
  if($option) {
    $controller->$method($option);
  } else {
    $controller->$method();
  }    
}  
