<?php

namespace delightnet\delightos;

use delightnet\delightos\Request;

interface CommandResolver {

    public function getCommand(Request $request, Response $response);
}