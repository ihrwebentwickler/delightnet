<?php
/*
 * initLoader.php
 * Set include-path and register auto-loading, resolve command(cmd),
 * build request- and -response-env and handle request for
 * frontend-templating
 *
 *
 * @author Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @version 6 (26.02.2018)
 *
 */

use delightnet\delightos as os;

spl_autoload_register();
spl_autoload_extensions('.class.php,.interface.php');

$resolver = new os\FileSystemCommandResolver();
$controller = new os\FrontController($resolver);

$request = new os\HttpRequest();
$response = new os\HttpResponse();

$controller->handleRequest($request, $response);

// activate for memory-stats
// echo memory_get_usage(true);