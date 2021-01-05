<?php
/*
 * interface-definition for class delightos/FileSystemCommandResolver
 * FileSystemCommandResolver resolves commands and loads template-env-classes
 * of frontend-env
 *
 *
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 *
 */


namespace delightnet\delightos;

interface CommandResolver {
    public function getCommand(Request $request, Response $response);
}