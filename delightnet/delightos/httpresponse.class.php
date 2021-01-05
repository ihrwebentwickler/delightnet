<?php

/*
 * send response-data to browser-output
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;

class HttpResponse implements Response {

    private string $status = '200 OK';
    private array $headers = array();
    private ?string $body = null;

    /**
     * set server-http-status
     * @param string $status
     * @return void
     */
    public function setStatus(string $status): void {
        $this->status = $status;
    }

    /**
     * add new header by name
     * @param string $name
     * @param string $value
     * @return void
     */
    public function addHeader(string $name, string $value): void {
        $this->headers[$name] = $value;
    }

    /**
     * write header to body
     * @param string $data
     * @return void
     */
    public function write(string $data): void {
        $this->body = $data;
    }

    /**
     * send all headers from header-array
     * @return void
     */
    public function flush(): void {
        header("HTTP/1.0 {$this->status}");
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        print $this->body;
        $this->headers = array();
        $this->body = null;
    }
}