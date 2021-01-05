<?php

/*
 * basic class for loading command-files
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;

class FileSystemCommandResolver implements CommandResolver {

    private string $defaultCommand;

    public function __construct() {
        $this->defaultCommand = "default";
    }

    /**
     * get and load variable command-methods of front- or backend-env.
     *
     * @param Request $request
     * @param Response $response
     * @return array $arrayCommands
     */
    public function getCommand(Request $request, Response $response): array {
        return $this->loadCommand($request, $response);
    }

    /**
     * The frontend-env creates the buildTemplate-
     * method of delightos/Template.class, by backend-use the execute-method of the variable command-class is loaded
     *
     * @param Request $request
     * @param Response $response
     * @return array $arrayCommands
     */
    protected function loadCommand(Request $request, Response $response): array {
        $arrayCommands = [];
        if (strpos($request->getServerRequestUri(), "cmsadmin") === false) {
            $strClass = 'delightnet\\delightos\\' . 'FrontendTemplate';
            $arrayCommands['commandClassAction'] = 'buildTemplate';
            $stringDirEnv = "public/";
            $arrayCommands['commandClassRouting'] = new $strClass($request, $response, $stringDirEnv);
        }

        return $arrayCommands;
    }

}