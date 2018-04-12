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
    const SESS_CIPHER = 'aes-128-cbc';

    public function __construct() {
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_trans_sid', 0);
        ini_set('session.cookie_lifetime', 0);

        $this->sessionUserData = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/cmsadmin/configuration/users.ini", TRUE);
        $this->sessionSalt = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/cmsadmin/configuration/salt.ini", TRUE);
        $this->cookiename = "delightCMS";
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
        $intLogginStatus = (file_exists("../../cmsadmin/configuration/loginstatus.ini")) ? $filehandle->readFilecontent("../cmsadmin/configuration/loginstatus.ini") : -1;

        if ($intLogginStatus > 6 && $intLogginStatus < 100) {
            $filehandle->writeFilecontent("../../cmsadmin/configuration/loginstatus.ini", time());
            return "sorry";
        }

        if (time() > ($intLogginStatus + (60 * $intLockTimeInMinutes)) && $intLogginStatus > 100) {
            $filehandle->writeFilecontent("../../cmsadmin/configuration/loginstatus.ini", 0);
            return "login";
        }

        if ($intLogginStatus > 100) {
            return "sorry";
        }

        if ($user != "" && $password != "") {
            if ($user == $this->sessionUserData["users"]["user"] && $password == $this->sessionUserData["users"]["password"]) {
                $this->unsetSession();
                $this->setSession();
                $filehandle->writeFilecontent("../../cmsadmin/configuration/loginstatus.ini", 0);
                return "welcome";
            } else {
                $intLogginStatus++;
                $filehandle->writeFilecontent("../../cmsadmin/configuration/loginstatus.ini", $intLogginStatus);
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
        $ivlen = openssl_cipher_iv_length($cipher = self::SESS_CIPHER);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($this->sessionUserData["users"]["password"], $cipher, $this->_getSalt(), $options = OPENSSL_RAW_DATA, $iv);
        $ciphertext = base64_encode($iv ./*$hmac.*/
            $ciphertext_raw);

        @setcookie($this->cookiename, rtrim($ciphertext, '\0'));
    }

    /**
     * check current session of current state
     *
     * @return \stdClass
     */
    private function hasSession() {
        $c = base64_decode($_COOKIE[$this->cookiename]);
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $ciphertext_raw = substr($c, $ivlen/*+$sha2len*/);
        $decryptedSession = openssl_decrypt($ciphertext_raw, $cipher, $this->_getSalt(), $options = OPENSSL_RAW_DATA, $iv);

        if ($this->sessionUserData["users"]["password"] === $decryptedSession && $decryptedSession != "") {
            return true;
        } else {
            return false;
        }
    }

    private function _getSalt() {
        return $this->sessionSalt["env"]["key"];
    }

}
