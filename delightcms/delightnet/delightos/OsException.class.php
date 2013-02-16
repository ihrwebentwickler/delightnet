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

    public function getErrorMessage() {
        $errorMsg = "<strong style=\"color: red;\">
        Sorry, es ist ein Fehler im Programm aufgetreten.<br />
        Bitte machen Sie Ihre letzte &Auml;nderung r&uuml;ckg&auml;ngig oder wenden Sie sich an Ihren Entwickler.<br />
        <br />
        Fehlermeldung:<br />"
                . $this->getMessage() . "</strong>";

        return $errorMsg;
    }

}