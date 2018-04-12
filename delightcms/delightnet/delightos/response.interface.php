<?php
/*
 * The interface-definition of response-env
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;

interface Response {

    public function write($data);

    public function addHeader($name, $value);

    public function setStatus($status);

    public function flush();
}