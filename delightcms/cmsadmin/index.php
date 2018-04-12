<?php
/*
 * Frontcontroller Start CMS Admin
 *
 *
 * @author Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @version 3.0 (19.11.2011)
 */

namespace delightnet\delightos;

ini_set("display_errors", true);
error_reporting(E_ALL);

require_once "../delightnet/delightcms/delightcmsErrorHandler.php";
set_error_handler ('delightcmsErrorHandler');

require_once "../delightnet/delightcms/Autoloader.class.php";
Autoloader::init();

$resolver = new FileSystemCommandResolver();
$controller = new FrontController($resolver);

$request = new HttpRequest();
$response = new HttpResponse();

$controller->handleRequest($request, $response);