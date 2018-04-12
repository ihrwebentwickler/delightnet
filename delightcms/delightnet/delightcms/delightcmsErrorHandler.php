<?php
/**
* simple error-handler function of cms-env
*  
* @param string $errorcode
* @param string $errormessage
* @param string $file
* @param string $row
* @return \stdClass
*/
function delightcmsErrorHandler($errorcode, $errormessage, $file, $row) {
    switch ($errorcode) {
        /*
          case E_NOTICE:
          case E_USER_NOTICE:
          echo 'HINWEIS: In Datei <' . basename($file) . '>, Zeile <' . $row . '> wurde folgender Fehler ausgelöst:';
          echo $errormessage;
          echo ' Bitte wenden Sie sich an Ihren Webmaster';
          break;
         */

        /*
        case E_WARNING:
        case E_USER_WARNING:

            echo 'WARNUNG: In Datei <' . basename($file) . '>, Zeile <' . $row . '> wurde folgender Fehler ausgelöst:';
            echo $errormessage;
            echo ' Bitte wenden Sie sich an Ihren Webmaster';
            break;
       
        
        case E_ERROR:
        case E_USER_ERROR:
            echo 'FATALER FEHLER: In Datei <' . basename($file) . '>, Zeile <' . $row . '> wurde folgender Fehler ausgelöst:';
            echo $errormessage;
            break;
        
        default:
        // echo 'FEHLER: Es ist ein unbekannter Fehler aufgetreten! Bitte wenden Sie sich an Ihren Webmaster';
        // break;
         * 
         */
    }

    return true;
}
