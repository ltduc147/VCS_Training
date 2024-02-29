<?php
session_start();

require_once('./controllers/BaseController.php');

$controller = ucfirst((strtolower($_REQUEST['controller'] ?? 'Auth')) . 'Controller');
$action = $_REQUEST['action'] ?? 'login';

require "./controllers/${controller}.php";

$object = new $controller;

$object->$action();

?>