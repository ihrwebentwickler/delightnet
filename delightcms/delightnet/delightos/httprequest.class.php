<?php

/*
 * handle, save and outsourcing http-request and server-env-data
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * 
 */

namespace delightnet\delightos;

class HttpRequest implements Request {

    private $parameters;

    public function __construct() {
        $this->parameters = $_REQUEST;
        $this->serverEnv = $_SERVER;
    }

    /**
     * check of existing request-parameter
     *
     * @param string $name
     * @return bool isset($this->parameters[$name])
     */
    public function issetParameter($name) {
        return isset($this->parameters[$name]);
    }

    /**
     * return http-request-parameter if exists
     *
     * @param string $name
     * @return bool $this->parameters[$name] or ""
     */
    public function getParameter($name) {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }

        return "";
    }

    /**
     * return visitor-ip-adress
     *
     * @return string getenv("REMOTE_ADDR")
     */
    public function getIp() {
        return getenv("REMOTE_ADDR");
    }

    /**
     * return visitor-ip-adress
     *
     * @return string getenv("REMOTE_ADDR") or null
     */
    public function getHttpHost() {
        if (isset($this->serverEnv['HTTP_HOST'])) {
            return $this->serverEnv['HTTP_HOST'];
        }

        return null;
    }

    /**
     * return array with all parameter-names of request-entries
     *
     * @return array_keys($this->parameters)
     */
    public function getParameterNames() {
        return array_keys($this->parameters);
    }


    /**
     * return server-document-root
     *
     * @return string
     */
    public function getDocumentRoot() {
        if (isset($this->serverEnv['DOCUMENT_ROOT'])) {
            return $this->serverEnv['DOCUMENT_ROOT'];
        }

        return null;
    }


    /**
     * return server-request-uri
     *
     * @return $this->serverEnv['REQUEST_URI'] or null
     */
    public function getServerRequestUri() {
        if (isset($this->serverEnv['REQUEST_URI'])) {
            return $this->serverEnv['REQUEST_URI'];
        }

        return null;
    }

    /**
     * return server-name
     *
     * @return $this->serverEnv['SERVER_NAME'] or null
     */
    public function getServerName() {
        if (isset($this->serverEnv['SERVER_NAME'])) {
            return $this->serverEnv['SERVER_NAME'];
        }

        return null;
    }

    /**
     * return user-agent of user-browser
     *
     * @return $this->serverEnv['HTTP_USER_AGENT'] or null
     */
    public function getHttpUserAgent() {
        if (isset($this->serverEnv['HTTP_USER_AGENT'])) {
            return $this->serverEnv['HTTP_USER_AGENT'];
        }
        return null;
    }

    /**
     * load new location by header
     */
    public function loadLocation($location) {
        // session_write_close();
        header("location:" . $location . ".html");
    }

    /**
     * get server-property if exists
     * @param string $name
     * @return array $this->serverEnv[[server-property]] || null
     */
    public function getHeaderAndServerEnvironment($name) {
        $nameHttp = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        $nameServer = strtoupper(str_replace('-', '_', $name));

        if (isset($this->serverEnv[$nameHttp])) {
            return $this->serverEnv[$nameHttp];
        }

        if (isset($this->serverEnv[$nameServer])) {
            return $this->serverEnv[$nameServer];
        }

        return null;
    }
}