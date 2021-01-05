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
     * @return void
     */
    public function setSession(string $name, string $value): void {
        $_SESSION[$name] = $value;
    }

    /**
     * get global session-value ($_SESSION[$name])
     *
     * @param string $name
     * @return string
     */
    public function getSession(string $name): string {
        if (!isset ($_SESSION[$name])) {
            return "";
        }

        return $_SESSION[$name];
    }

    /**
     * unset current session
     * @return void
     */
    public function unsetSession(): void {
        @session_destroy();
    }
}