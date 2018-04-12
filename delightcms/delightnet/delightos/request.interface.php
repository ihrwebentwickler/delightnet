<?php
/*
 * The interface-definition of request-env
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;
interface Request {

    public function issetParameter($name);

    public function getParameter($name);

    public function getIp();

    public function getHttpHost();

    public function getParameterNames();

    public function getDocumentRoot();

    public function getServerRequestUri();

    public function getServerName();

    public function getHttpUserAgent();

    public function loadLocation($location);

    public function getHeaderAndServerEnvironment($name);
}