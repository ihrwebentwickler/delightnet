<?php

/*
 * front-controller which roots request-handles to controller
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 */

namespace delightnet\delightos;

class FrontController {

    private CommandResolver $resolver;

    public function __construct(CommandResolver $resolver) {
        $this->resolver = $resolver;
    }

    /**
     * route, handle and flush the command to frontend-methods with encapsulated request- and response-data
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function handleRequest(Request $request, Response $response): void {
        $arrayCommands = $this->resolver->getCommand($request, $response);

        $strActionMethod = (string)$arrayCommands['commandClassAction'];
        $arrayCommands['commandClassRouting']->$strActionMethod($request, $response);
        $response->flush();
    }
}