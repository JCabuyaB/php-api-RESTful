<?php
require_once 'controllers/routes_controller.php';
require_once 'controllers/courses_controller.php';
require_once 'controllers/clients_controller.php';

$routes = new Routes();
$routes->index();