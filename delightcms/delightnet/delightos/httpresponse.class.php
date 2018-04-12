<?php

/*
 * send response-data to browser-output
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;

class HttpResponse implements Response {

    private $status = '200 OK';
    private $headers = array();
    private $body = null;

    /**
     * set server-http-status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * add new header by name
     */
    public function addHeader($name, $value) {
        $this->headers[$name] = $value;
    }

    /**
     * write header to body
     */
    public function write($data) {
        $this->body = $data;
    }

    /**
     * send all headers from header-array
     */
    public function flush() {
        header("HTTP/1.0 {$this->status}");
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        print $this->body;
        $this->headers = array();
        $this->body = null;
    }
}