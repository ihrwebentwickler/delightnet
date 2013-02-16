<?php

/*
 * Standard-Registry for objects in plugin-layers
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   1.00
 * 
 */

namespace delightnet\delightos;

class Registry {
    private static $registry = array();

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    /**
     * set key and value to Registry
     *  
     * @param string $key
     * @param string $value
     * @return \stdClass
     */
    public static function set($key, $value) {
        if (!isset(self::$registry[$key])) {
            self::$registry[$key] = $value;
            return true;
        }
    }

    /**
     * read value from registry by key
     * 
     * @return array
     */
    public static function get($key) {
        if (isset(self::$registry[$key])) {
            return self::$registry[$key];
        }
        return null;
    }

    /**
     * read whole registy
     * 
     * @return array
     */
    public static function getAll() {
        return self::$registry;
    }

    /**
     * remove value from registry by key
     * 
     * @return array
     */
    public static function remove($key) {
        if (isset(self::$registry[$key])) {
            unset(self::$registry[$key]);
            return true;
        }
        return false;
    }

    /**
     * remove whole regitry
     * 
     * @return true
     */
    public static function removeAll() {
        self::$registry = array();
        return;
    }

}