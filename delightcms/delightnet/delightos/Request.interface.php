<?php

namespace delightnet\delightos;

interface Request {

    public function issetParameter($name);

    public function getParameter($name);

    public function getIp();

    public function getHttpHost();

    public function getParameterNames();

    public function getServerRequestUri();

    public function getServerName();
    
    public function getHttpUserAgent();

    public function loadNewHeaderLocation($location);

    public function getHeaderAndServerEnvironment($name);
}