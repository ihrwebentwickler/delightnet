<?php
/*
 * The interface-definition of request-env
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;
interface Request {

    public function issetParameter(string $name);

    public function getParameter(string $name);

    public function getIp();

    public function getHttpHost();

    public function getParameterNames();

    public function getDocumentRoot();

    public function getServerRequestUri();

    public function getServerName();

    public function getHttpUserAgent();

    public function loadLocation(string $location);

    public function getHeaderAndServerEnvironment(string $name);
}