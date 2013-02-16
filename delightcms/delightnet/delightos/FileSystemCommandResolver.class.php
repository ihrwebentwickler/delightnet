<?php

/*
 * basic class for loading command-files
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   5.10
 * 
 */

namespace delightnet\delightos;

use delightnet\delightos\Request;
use delightnet\delightos\Response;

class FileSystemCommandResolver implements CommandResolver {

    private $defaultCommand;

    public function __construct() {
        $this->defaultCommand = "default";
    }

    public function getCommand(Request $request, Response $response) {
        if ($request->issetParameter('cmd')) {
            $command = $this->loadCommand($request->getParameter('cmd'), $request, $response);
        } else {
            $command = $this->loadCommand($this->defaultCommand, $request, $response);
        }

        return $command;
    }

    protected function loadCommand($cmd, Request $request, Response $response) {
        if (strpos($request->getServerRequestUri(), "cmsadmin") === false) {
            $strClass = 'delightnet\\delightos\\' . 'Template';
        } else {
            if ($cmd != $this->defaultCommand && file_exists("../delightnet/delightcms/commands/" . $cmd . "Command.class.php")) {
                $strClass = 'delightnet\\delightcms\\commands\\' . $cmd . 'Command';
            } else {
                $strClass = '\\delightnet\\delightcms\\commands\\defaultCommand';
            }
        }

        $command = new $strClass($request, $response);
        return $command;
    }

}