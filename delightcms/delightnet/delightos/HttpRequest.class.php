<?php

/*
 * handle, save and outsourcing http-request and server-enviroment-data
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   1.60
 * 
 */

namespace delightnet\delightos;

class HttpRequest implements Request {

    private $parameters;

    public function __construct() {
        $this->parameters = $_REQUEST;
    }

    public function issetParameter($name) {
        return isset($this->parameters[$name]);
    }

    public function getParameter($name) {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }
        return "";
    }

    public function getIp() {
        return getenv("REMOTE_ADDR");
    }

    public function getHttpHost() {
        return $_SERVER['HTTP_HOST'];
    }

    public function getParameterNames() {
        return array_keys($this->parameters);
    }

    public function getServerRequestUri() {
        return $_SERVER["REQUEST_URI"];
    }

    public function getdocumentRoot() {
        return stripslashes($_SERVER["DOCUMENT_ROOT"]);
    }
 
    public function getServerName() {
        return $_SERVER["SERVER_NAME"];
    }

    public function getHttpUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function loadNewHeaderLocation($location) {
        header("location:" . $location . ".html");
    }

    public function getHeaderAndServerEnvironment($name) {
        $nameHttp = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        $nameServer = strtoupper(str_replace('-', '_', $name));

        if (isset($_SERVER[$nameHttp])) {
            return $_SERVER[$nameHttp];
        }

        if (isset($_SERVER[$nameServer])) {
            return $_SERVER[$nameServer];
        }

        return null;
    }

}