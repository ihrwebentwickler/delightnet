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

use Exception;

class phpmailerException extends Exception {
    /**
     * generate error-message by exception-handling of phpmailer-env
     *
     * @return string $errorMsg
     */
    public function errorMessage() {
        $errorMsg = '<strong>' . $this->getMessage() . "</strong><br />\n";
        return $errorMsg;
    }
}