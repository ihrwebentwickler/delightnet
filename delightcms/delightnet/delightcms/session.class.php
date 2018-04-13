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
use delightnet\delightos\Security;

final class Session {
    private $sessionUserData;
    private $cookiename;

    private $Filehandle;
    private $Security;

    public function __construct() {
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_trans_sid', 0);
        ini_set('session.cookie_lifetime', 0);

        $this->sessionUserData = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/cmsadmin/configuration/users.ini", true);
        $this->cookiename = "delightCMS";

        $this->Filehandle = new Filehandle();
        $this->Security = new Security();
    }

    /**
     * check (startpoint to session-env) and set current user-session-rights
     *
     * @param string $cmd
     * @param string $user
     * @param string $password
     * @return \stdClass
     */
    public function setSessionEnvirement($cmd, $user, $password) {
        $intLockTimeInMinutes = 20;
        $intLogginStatus = (file_exists("../../cmsadmin/configuration/loginstatus.ini")) ? $this->Filehandle->readFilecontent("../cmsadmin/configuration/loginstatus.ini") : -1;

        if ($intLogginStatus > 6 && $intLogginStatus < 100) {
            $this->Filehandle->writeFilecontent("../../cmsadmin/configuration/loginstatus.ini", time());
            return "sorry";
        }

        if (time() > ($intLogginStatus + (60 * $intLockTimeInMinutes)) && $intLogginStatus > 100) {
            $this->Filehandle->writeFilecontent("../../cmsadmin/configuration/loginstatus.ini", 0);
            return "login";
        }

        if ($intLogginStatus > 100) {
            return "sorry";
        }

        if ($user != "" && $password != "") {
            if ($user == $this->sessionUserData["users"]["user"] && $password == $this->sessionUserData["users"]["password"]) {
                $this->unsetSession();
                $this->setSession();
                $this->Filehandle->writeFilecontent("../../cmsadmin/configuration/loginstatus.ini", 0);
                return "welcome";
            } else {
                $intLogginStatus++;
                $this->Filehandle->writeFilecontent("../../cmsadmin/configuration/loginstatus.ini", $intLogginStatus);
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
        setcookie($this->cookiename, '', 0);
    }

    /**
     * init a new session with decrypted session-values
     */
    private function setSession() {
        $ciphertext = $this->Security->encodeString($this->sessionUserData["users"]["password"]);
        @setcookie($this->cookiename, $ciphertext);
    }

    /**
     * check current session of current state
     *
     * @return \stdClass
     */
    private function hasSession() {
        $decryptedSession = $this->Security->decodeString($_COOKIE[$this->cookiename]);

        if ($this->sessionUserData["users"]["password"] === $decryptedSession && $decryptedSession != "") {
            return true;
        } else {
            return false;
        }
    }
}
