<?php

/*
 * realize Session-Envirement for CMS-Admin
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   3.11
 * 
 */

namespace delightnet\delightcms;

use delightnet\delightos\Filehandle;

final class SessionAndSecurityData {

    private $cookiename;
    private $cypher;
    private $mode;

    public function __construct() {
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_trans_sid', 0);
        ini_set('session.cookie_lifetime', 0);

        $this->sessionUserData = parse_ini_file("cmsadmin/configuration/users.ini", TRUE);
        $this->sessionSalt = parse_ini_file("cmsadmin/configuration/salt.ini", TRUE);

        $this->cookiename = "delightCMS";
        $this->cypher = "rijndael_256";
        $this->mode = "ecb";
    }

    /**
     * check (startpoint to session-env) and set current user-session-rights 
     *  
     * @param object Filehandle
     * @param string $cmd
     * @param string $user
     * @param string $password
     * @return \stdClass
     */
    public function setSessionEnvirement(Filehandle $filehandle, $cmd, $user, $password) {
        $intLockTimeInMinutes = 20;
        $intLogginStatus = (file_exists("../cmsadmin/configuration/loginstatus.ini")) ? $filehandle->readFilecontent("../cmsadmin/configuration/loginstatus.ini") : -1;
        
        if ($intLogginStatus > 6 && $intLogginStatus < 100) {
            $filehandle->writeFilecontent("../cmsadmin/configuration/loginstatus.ini", time());
            return "sorry";
        }

        if (time() > ($intLogginStatus + (60 * $intLockTimeInMinutes)) && $intLogginStatus > 100) {
            $filehandle->writeFilecontent("../cmsadmin/configuration/loginstatus.ini", 0);
            return "login";
        }

        if ($intLogginStatus > 100) {
            return "sorry";
        }
        
        if ($user != "" && $password != "") {
            if ($user == $this->sessionUserData["users"]["user"] && $password == $this->sessionUserData["users"]["password"]) {
                $this->unsetSession();
                $this->setSession();
                $filehandle->writeFilecontent("../cmsadmin/configuration/loginstatus.ini", 0);
                return "welcome";
            } else {
                $intLogginStatus++;
                $filehandle->writeFilecontent("../cmsadmin/configuration/loginstatus.ini", $intLogginStatus);
                return "error";
            }
        }

        if ($cmd == "login" || $cmd == "") {
            $this->unsetSession();
        }

        if ($cmd == "logout") {
            $this->unsetSession();
            return "logout";
        }

        $boolHasSession = $this->hasSession();

        if ($boolHasSession === false) {
            return "login";
        } else {
            return $cmd;
        }
    }

    /**
     * unset current cms-session
     */
    public function unsetSession() {
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        @session_destroy();

        // Löschen aller Session-Variablen.
        setcookie($this->cookiename, '', 0);

        unset($GLOBALS['user']);
        unset($GLOBALS['password']);
    }

    /**
     * init a new session with decrypted session-values
     */
    private function setSession() {
        $mcrypt = mcrypt_module_open($this->cypher, "", $this->mode, "");
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($mcrypt), MCRYPT_RAND);
        @mcrypt_generic_init($mcrypt, $this->sessionSalt["env"]["key"] . session_id(), $iv);
        $crypted = mcrypt_generic($mcrypt, $this->sessionUserData["users"]["password"]);
        @mcrypt_generic_deinit($mcrypt);
        @setcookie($this->cookiename, $crypted, 0);
    }

    /**
     * check current session of current state
     * 
     * @return \stdClass
     */
    private function hasSession() {
        $mcrypt = mcrypt_module_open($this->cypher, "", $this->mode, "");
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($mcrypt), MCRYPT_RAND);
        @mcrypt_generic_init($mcrypt, $this->sessionSalt["env"]["key"] . session_id(), $iv);
        $decrypted = (string) @mdecrypt_generic($mcrypt, $_COOKIE[$this->cookiename]);
        @mcrypt_generic_deinit($mcrypt);

        if (strcmp($decrypted, $this->sessionUserData["users"]["password"]) == true && $decrypted != "") {
            return true;
        } else {
            return false;
        }
    }

}
