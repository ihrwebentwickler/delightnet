<?php
/*
 * Frontcontroller Start
 *
 *
 * @author Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @version 3.0 (19.11.2011)
 */

namespace delightnet\delightos;

ini_set("display_errors", true);
error_reporting(E_ALL);

require_once "delightnet/delightos/Autoloader.class.php";
Autoloader::init();

$resolver = new FileSystemCommandResolver();
$controller = new FrontController($resolver);

$request = new HttpRequest();
$response = new HttpResponse();

$controller->handleRequest($request, $response);
// echo memory_get_usage(true);

