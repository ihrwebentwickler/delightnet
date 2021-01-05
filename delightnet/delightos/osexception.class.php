<?php

/*
 * simple exception handling os
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   0.88
 * 
 */

class OsException extends Exception {
    /**
     * generate error-message by exception-handling
     *
     * @return string $errorMsg
     */
    public function getErrorMessage(): string {
        return "<strong style=\"color: red;\">
        Sorry, es ist ein Fehler im Programm aufgetreten.<br />
        Bitte machen Sie Ihre letzte &Auml;nderung r&uuml;ckg&auml;ngig oder wenden Sie sich an Ihren Entwickler.<br />
        <br />
        Fehlermeldung:<br />"
            . $this->getMessage() . "</strong>";
    }
}