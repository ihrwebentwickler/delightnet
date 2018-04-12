<?php
/*
 * autoloading of cms-envirement
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightcms
 * @version   3.3
 * 
 */

namespace delightnet\delightos;

class Autoloader
{
    public static $instance;
    private $arrayExtension = array('.class.php', '.interface.php', '.config.php');

    public static function init() {
        if (self::$instance === NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        spl_autoload_register(array($this, 'loadBasicAutoload'));
    }

    private function loadBasicAutoload($class) {
        $class = $_SERVER['DOCUMENT_ROOT'] . '/' . preg_replace('#\\\\#', '/', $class);

        $strFileExtension = false;
        foreach ($this->arrayExtension as $extension) {
            if (file_exists($class . $extension)) {
                $strFileExtension = $extension;
                break;
            }
        }

        require($class . $strFileExtension);
    }
}