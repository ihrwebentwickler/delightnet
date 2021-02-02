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
        $strClass = 'delightnet\\delightos\\' . 'FrontendTemplate';
        $arrayCommands['commandClassAction'] = 'buildTemplate';
        $stringDirEnv = "public/";
        $arrayCommands['commandClassRouting'] = new $strClass($request, $response, $stringDirEnv);

        return $arrayCommands;
    }
}