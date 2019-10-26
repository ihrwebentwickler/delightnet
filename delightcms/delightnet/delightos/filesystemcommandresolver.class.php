<?php

/*
 * basic class for loading command-files
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;

class FileSystemCommandResolver implements CommandResolver {

    private $defaultCommand;

    public function __construct() {
        $this->defaultCommand = "default";
    }

    /**
     * get and load variable command-methods of front- or backend-env.
     *
     * @param Request $request
     * @param Response $response
     * return array $arrayCommands
     */
    public function getCommand(Request $request, Response $response) {
        $cmd = ($request->issetParameter('cmd')) ? $request->getParameter('cmd') : $this->defaultCommand;
        $arrayCommands = $this->loadCommand($cmd, $request, $response);
        return $arrayCommands;
    }

    /**
     * The frontend-env creates the buildTemplate-
     * method of delightos/Template.class, by backend-use the execute-method of the variable command-class is loaded
     *
     * @param string $cmd
     * @param Request $request
     * @param Response $response
     * return array $arrayCommands
     */
    protected function loadCommand($cmd, Request $request, Response $response) {
        if (strpos($request->getServerRequestUri(), "cmsadmin") === false) {
            $strClass = 'delightnet\\delightos\\' . 'FrontendTemplate';
            $arrayCommands['commandClassAction'] = 'buildTemplate';
            $stringDirEnv = "public/";
        } else {
            if ($cmd != $this->defaultCommand && file_exists("../delightnet/delightcms/commands/" . $cmd . "Command.class.php")) {
                $strClass = 'delightnet\\delightcms\\commands\\' . $cmd . 'Command';
            } else {
                $strClass = '\\delightnet\\delightcms\\commands\\defaultCommand';
            }

            $stringDirEnv = "../cmsadmin/";
            $arrayCommands['commandClassAction'] = 'execute';
        }

        $arrayCommands['commandClassRouting'] = new $strClass($request, $response, $stringDirEnv);
        return $arrayCommands;
    }

}