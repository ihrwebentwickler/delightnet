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

    private array $parameters;

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
    public function issetParameter(string $name): bool {
        return isset($this->parameters[$name]);
    }

    /**
     * return http-request-parameter if exists
     *
     * @param string $name
     * @return bool $this->parameters[$name] or ""
     */
    public function getParameter(string $name) {
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
    public function getIp(): string {
        return getenv("REMOTE_ADDR");
    }

    /**
     * return visitor-ip-adress
     *
     * @return string getenv("REMOTE_ADDR")
     */
    public function getHttpHost(): string {
        if (isset($this->serverEnv['HTTP_HOST'])) {
            return $this->serverEnv['HTTP_HOST'];
        }

        return "";
    }

    /**
     * return array with all parameter-names of request-entries
     *
     * @return array($this->parameters)
     */
    public function getParameterNames(): array {
        return array_keys($this->parameters);
    }


    /**
     * return server-document-root
     *
     * @return string
     */
    public function getDocumentRoot(): string {
        if (isset($this->serverEnv['DOCUMENT_ROOT'])) {
            return $this->serverEnv['DOCUMENT_ROOT'];
        }

        return "";
    }


    /**
     * return server-request-uri
     *
     * @return string
     */
    public function getServerRequestUri() : string{
        if (isset($this->serverEnv['REQUEST_URI'])) {
            return $this->serverEnv['REQUEST_URI'];
        }

        return "";
    }

    /**
     * return server-name
     *
     * @return string
     */
    public function getServerName(): string {
        if (isset($this->serverEnv['SERVER_NAME'])) {
            return $this->serverEnv['SERVER_NAME'];
        }

        return "";
    }

    /**
     * return user-agent of user-browser
     *
     * @return string
     */
    public function getHttpUserAgent(): string {
        if (isset($this->serverEnv['HTTP_USER_AGENT'])) {
            return $this->serverEnv['HTTP_USER_AGENT'];
        }

        return "";
    }

    /**
     * load new location by header
     * @param string $location
     * @return void
     */
    public function loadLocation(string $location): void {
        header("location:" . $location . ".html");
    }

    /**
     * get server-property if exists
     * @param string $name
     * @return array $this->serverEnv[[server-property]] || null
     */
    public function getHeaderAndServerEnvironment(string $name): array {
        $nameHttp = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        $nameServer = strtoupper(str_replace('-', '_', $name));

        if (isset($this->serverEnv[$nameHttp])) {
            return $this->serverEnv[$nameHttp];
        }

        if (isset($this->serverEnv[$nameServer])) {
            return $this->serverEnv[$nameServer];
        }

        return [];
    }
}