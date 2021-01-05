<?php
/*
 * The interface-definition of response-env
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;

interface Response {

    public function write(string $data);

    public function addHeader(string $name, string $value);

    public function setStatus(string $status);

    public function flush();
}