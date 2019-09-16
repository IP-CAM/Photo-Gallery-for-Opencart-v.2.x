<?php 
spl_autoload_register(function ($class_name) {
	$class_name = str_replace('\\', '/', $class_name);
	$class_name = strtolower($class_name);
    include DIR_APP . '/' .$class_name . '.php';
});