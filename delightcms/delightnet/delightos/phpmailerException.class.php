<?php

/*
 * simple exception-handling of phpmailer 
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   1.00
 * 
 */

namespace delightnet\delightos;

class phpmailerException extends Exception {

    public function errorMessage() {
        $errorMsg = '<strong>' . $this->getMessage() . "</strong><br />\n";
        return $errorMsg;
    }

}