<?php

/*
 * front-controller roots request-handles to controller
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   1.20
 * 
 */

namespace delightnet\delightos;

use delightnet\delightos\Response;
use delightnet\delightos\Request;
use delightnet\delightos\CommandResolver;

class FrontController {

    private $resolver;

    public function __construct(CommandResolver $resolver) {
        $this->resolver = $resolver;
    }

    public function handleRequest(Request $request, Response $response) {
        $command = $this->resolver->getCommand($request, $response);
        
        if (method_exists($command, 'buildTemplate') == false) {
            // complete error handling-class in near future
            exit();
        }
        
        $command->buildTemplate($request, $response);
        $response->flush();
    }

    public function handleCommandRequest(Request $request, Response $response) {
        $command = $this->resolver->getCommand($request, $response);
        $command->execute($request, $response);
        $response->flush();
    }

}