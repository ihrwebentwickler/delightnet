<?php
/*
 * initLoader.php
 * Set include-path and register auto-loading, resolve command(cmd),
 * build request- and -response-env and handle request for
 * frontend-templating
 *
 *
 * @author Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @version 5.11 (01.03.2014)
 *
 */
use delightnet\delightos as os;
set_include_path(dirname(__DIR__));

spl_autoload_extensions('.class.php,.interface.php');
spl_autoload_register();

$resolver = new os\FileSystemCommandResolver();
$controller = new os\FrontController($resolver);

$request = new os\HttpRequest();
$response = new os\HttpResponse();

$controller->handleRequest($request, $response);

// activate for memory-stats
// echo memory_get_usage(true);