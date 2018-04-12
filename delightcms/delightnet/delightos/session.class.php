<?php
/*
 * realize Session-Envirement for web-env
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 */

namespace delightnet\delightos;

class Session {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            @session_start();
        }
    }

    /**
     * set global session-value ($_SESSION[$name])
     *
     * @param string $name
     * @param string $value
     */
    public function setSession($name, $value) {
        $_SESSION[$name] = $value;
    }

    /**
     * get global session-value ($_SESSION[$name])
     *
     * @param string $name
     * @return string $_SESSION[$name]
     */
    public function getSession($name) {
        if (!isset ($_SESSION[$name])) {
            return false;
        }

        return $_SESSION[$name];
    }

    /**
     * unset current session
     */
    public function unsetSession() {
        @session_destroy();
    }
}